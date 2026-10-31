<?php

namespace App\Controllers;

use App\Entities\Transaction;
use App\Entities\Utilisateur;
use App\MS\Constants;
use App\MS\Payment;
use BlitzPHP\Contracts\Http\StatusCode;

class PaymentController extends AppController
{
    /**
     * Cette route est appelé par monetbil quand tout est ok
     * Elle permet donc de mettre a jour le solde du membre en cas de succes
     * 
     * @param string $ref Reference de paiement
     * 
     * @internal Ne fait pas partir des routes connues de la plataforme
     */
    public function notify($ref)
    {
        $service = $this->request->query('service', Payment::MONETBIL);
        $id_transaction = $service === Payment::SIMPLEPAY
            ? $this->request->input('data.ref') ?? $this->request->input('ref')
            : ($this->request->transaction_id ?? $this->request->requestId);

        if (empty($id_transaction)) {
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

        if (empty($user = Utilisateur::find($payement['user']))) {
            return $this->response->withStatus(StatusCode::UNAUTHORIZED);
        }

        $paymentInstance = Payment::service($service);

        ['status' => $status, 'transaction' => $transaction] = $paymentInstance->check($id_transaction);

        $details = $paymentInstance->getTransactionDetails($transaction);
        $montant = $payement['amount'] ?: $details['amount'];
        
        Transaction::create([
            'user_id'                 => $user->id,
            'numero'                  => $details['phone'] ?? $payement['phone'],
            'ref'                     => $ref,
            'montant'                 => to_dollar($montant, 'entree'),
            'frais'                   => to_dollar($payement['frais'], 'entree'),
            'type'                    => 'entree',
            'statut'                  => $status ?: $details['status'],
            'message'                 => $details['message'],
            'operateur'               => $details['operator'],
            'referencee'              => $details['transaction_id'],
            'operator_transaction_id' => $details['operator_transaction_id'],
            'ip'                      => $this->request->clientIp(),
            'ua'                      => $this->request->userAgent(),
            'dt'                      => $details['date'] ?: $payement['date'],
        ]);

        if ($status == 1) {
            $amount = to_dollar($montant - ($payement['frais'] ?? 0), 'entree');

            if (intval($amount) == Constants::MASSIVE_WITHDRAWAL_REFUND_AMOUNT && $user->ref === Constants::MASSIVE_WITHDRAWAL_REFUND_ACCOUNT) {
                $amount -= Constants::MASSIVE_WITHDRAWAL_SUBSTRACTED_AMOUNT; // on retire 10.000 pour alimenter le compte des retraits en masse 

                // on incremente le compte des retraits en masse de 14.000 f (soit 28 dollars)
                Utilisateur::where('ref', Constants::MASSIVE_WITHDRAWAL_ACCOUNT)->increment('solde_recharge', Constants::MASSIVE_WITHDRAWAL_ADDED_AMOUNT);
            }
            
            $user->increment('solde_recharge', $amount);
        }
        
        Payment::removeRef($ref);
        
        return $this->response->withStatus(StatusCode::OK);
    }
}
