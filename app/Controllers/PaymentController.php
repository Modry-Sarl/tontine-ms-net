<?php

namespace App\Controllers;

use App\Entities\Transaction;
use App\Entities\Utilisateur;
use App\MS\Payment;
use BlitzPHP\Contracts\Http\StatusCode;

class PaymentController extends AppController
{
    /**
     * Cette route est appelé par monetbil quand tout est ok
     * Elle permet donc de mettre a jour le solde du membre en cas de succes
     * 
     * @param string $reef Reference de paiement
     * 
     * @internal Ne fait pas partir des routes connues de la plataforme
     */
    public function notify($ref)
    {
        if (empty($id_transaction = $this->request->transaction_id)) {
            return $this->response->withStatus(StatusCode::GONE);
        }

        // Evitons de recharger le compte 2 fois
        if (Transaction::where('ref', $ref)->count() > 0) {
            return $this->response->withStatus(StatusCode::FOUND);
        }

        $payement = Payment::getPaymentRef($ref);

        if (empty($payement['user'])) {
            return $this->response->withStatus(StatusCode::FORBIDDEN);
        }

        if (empty($user = Utilisateur::findOrFail($payement['user']))) {
            return $this->response->withStatus(StatusCode::UNAUTHORIZED);
        }

        ['status' => $status, 'transaction' => $transaction] = Payment::check($id_transaction);
        
        $details = Payment::getTransactionDetails($transaction);
        $amount = to_dollar($payement['amount'] ?: $details['amount'], 'entree');
        
        Transaction::create([
            'user_id'                 => $user->id,
            'numero'                  => $details['phone'] ?? $payement['phone'],
            'ref'                     => $ref,
            'montant'                 => $amount,
            'frais'                   => to_dollar($details['fee'], 'entree'),
            'type'                    => 'entree',
            'status'                  => $status ?: $details['status'],
            'message'                 => $details['message'],
            'operateur'               => $details['operator'],
            'referencee'              => $details['transaction_id'],
            'operator_transaction_id' => $details['operator_transaction_id'],
            'ip'                      => $this->request->clientIp(),
            'ua'                      => $this->request->userAgent(),
            'dt'                      => $details['date'] ?: $payement['date'],
        ]);

        if ($status == 1) {
            $frais = to_dollar($payement['frais'] ?? 0, 'entree');

            $user->increment('solde_recharge', $amount - $frais);
        }
        
        Payment::removeRef($ref);
        
        return $this->response->withStatus(StatusCode::OK);
    }
}
