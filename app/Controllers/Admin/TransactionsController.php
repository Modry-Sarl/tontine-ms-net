<?php

namespace App\Controllers\Admin;

use App\Controllers\AppController;
use App\Entities\Retrait;
use App\Entities\Transaction;
use App\MS\Payment;
use Exception;

class TransactionsController extends AppController
{
    /**
     * Liste des approbations de retraits
     */
    public function formApprobations()
    {
        $data['tab'] = $statut = $this->request->string('tab', 'pending');

        $data['approbations'] = Retrait::with('user')
            ->where('statut', $statut)
            ->paginate();

        return $this->render('approbations', $data);
    }

    /**
     * Traitement des approbations de retrait
     */
    public function processApprobations()
    {
        $validated = $this->request->validate([
            'action' => 'required|in:validated,rejected',
            'ref'    => 'required:exists:retraits',
        ]);

        $retrait = Retrait::with('user')->where('ref', $validated['ref'])->first();

        if (!empty($retrait->process_at)) {
            return back()->withErrors('Cette demande a déjà été traitée.');
        }

        $db = service('database');
        
        try {
            $db->beginTransaction();
            
            if ($validated['action'] === 'validated') {
                if ($retrait->montant > $retrait->user->{'solde_' . $retrait->compte}) {
                    $rejected_reason     = 'Retrait rejété car le solde ' . $retrait->compte . ' de l\'utilisateur est insuffisant';
                    $validated['action'] = 'rejected';
                    goto update_retrait;
                }

                $retrait->user->decrement('solde_' . $retrait->compte, $retrait->montant);
                
                $montant = to_cfa($retrait->montant);
                $sender = Payment::send([
                    'amount' => $montant,
                    'phone'  => simple_tel($retrait->tel),
                ], $retrait->meta['use_eum'] ?? false);
                
                if (empty($sender) || !is_array($sender) || !isset($sender['success']) || $sender['success'] == false) {
                   throw new Exception('[' . (($sender ?? [])['message'] ?? ''). ']');
                }
                
                Transaction::create([
                    'user_id'                 => $retrait->user_id,
                    'numero'                  => substr($sender['phonenumber'], 3, 9),
                    'montant'                 => to_dollar($sender['amount'] ?? $montant),
                    'frais'                   => 0,
                    'type'                    => 'sortie',
                    'statut'                  => in_array($sender['success'], ['1', 1]),
                    'message'                 => $sender['message'],
                    'operateur'               => $sender['operator'] ?? '',
                    'operator_transaction_id' => $sender['operator_transaction_id'],
                    'ip'                      => $retrait->meta['ip'] ?? $this->request->clientIp(),
                    'ua'                      => $retrait->meta['ua'] ?? $this->request->userAgent(),
                    'dt'                      => $retrait->meta['dt'] ?? date('Y-m-d H:i:s'),
                ]);
            }
        
            update_retrait: 
            $retrait->update([
                'statut'          => $validated['action'],
                'process_at'      => date('Y-m-d H:i:s'),
                'process_by'      => $this->user->utilisateur->id,
                'rejected_reason' => $rejected_reason ?? null,
            ]);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();

            return back()->withErrors('Une erreur s\'est produite lors du transfert: ' . $e->getMessage());
        }

        $message = $rejected_reason ?? match($validated['action']) {
            'validated' => 'Retrait approuvé avec succès. <b>' . $retrait->montant . ' $</b> transferé à ' . $retrait->tel,
            'rejected'  => 'Demande de retrait rejétée avec succès.',
        };

        return redirect()->to(link_to('admin.transactions.approbations') . '?tab=' . $validated['action'])->with('success', $message);
    }

}