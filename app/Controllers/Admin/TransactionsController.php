<?php

namespace App\Controllers\Admin;

use App\Controllers\AppController;
use App\Entities\Retrait;
use App\Entities\Transaction;
use App\Entities\Utilisateur;
use App\MS\Constants;
use App\MS\Payment;
use BlitzPHP\Exceptions\ValidationException;
use BlitzPHP\View\View;
use Exception;

class TransactionsController extends AppController
{
    /**
     * Liste des approbations de retraits
     */
    public function formApprobations()
    {
        $data['tab'] = $statut = $this->request->string('tab', 'pending');

        if ($statut === 'massive') { // retraits en masse
            $data['approbations'] = Retrait::whereRelation('user', 'ref', '=', Constants::MASSIVE_WITHDRAWAL_ACCOUNT)->sortAsc('statut')->paginate();
        } else {
            $data['approbations'] = Retrait::with('user')->whereRelation('user', 'ref', '!=', Constants::MASSIVE_WITHDRAWAL_ACCOUNT)->where('statut', $statut)->paginate();
        }

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
                    'amount' => $montant - ($montant * 0.05),
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

            $message = $this->user->id == 110 ? ': ' . $e->getMessage() : '';

            return back()->withErrors('Une erreur s\'est produite lors du transfert' . $message);
        }

        $message = $rejected_reason ?? match($validated['action']) {
            'validated' => 'Retrait approuvé avec succès. <b>' . $retrait->montant . ' $</b> transferé à ' . $retrait->tel,
            'rejected'  => 'Demande de retrait rejétée avec succès.',
        };

        if ('massive' !== $tab = $this->request->string('tab')) {
            $tab = $validated['action'];
        }

        return redirect()->to(link_to('admin.transactions.approbations') . '?tab=' . $tab)->with('success', $message);
    }

    /**
     * Initialisation de retrait en masse
     */
    public function formRetraits()
    {
        $data['compteRetrait'] = Utilisateur::where('ref', Constants::MASSIVE_WITHDRAWAL_ACCOUNT)->first();
        
        if ($data['compteRetrait'] !== null) {
            $data['dernieres_demandes'] = Retrait::where('user_id', $data['compteRetrait']->id)->limit(5)->latest()->all();
        }
        
        return $this->render('retraits', $data);
    }

    /**
     * Traitement de l'inutialisation des retraits en masse
     */
    public function processRetraits()
    {
        try {
            $post = $this->validate([
                'montant'  => ['required', 'array', 'min:1'],
                'montant.*'  => ['nullable', 'numeric', 'min:1', 'max:2000'],
                'tel'   => ['required', 'array'],
                'tel.*'      => ['nullable', 'phone:cm'],
                'password' => ['required', 'current_password'],
            ], [
                'montant:required'          => 'Veuillez indiquer les montants à retirer',
                'montant.*:numeric'         => 'Veuillez entrer un montant valide',
                'montant.*:min'             => 'Vous ne pouvez pas retirer moins de 1 $',
                'montant.*:max'             => 'Vous ne pouvez pas retirer plus de 2000 $',
                'tel:required'              => 'Veuillez entrer le numéro de téléphone des bénéficiares',
                'tel.*:phone'               => 'Veuillez entrer un numéro de téléphone valide',
                'password:required'         => 'Veuillez entrer votre mot de passe pour valider l\'inscription',
                'password:current_password' => 'Mot de passe incorrect',
            ]);
        } catch (ValidationException $e) {
            return $this->backHTMX('admin/htmx-form-response', $e->getErrors()->all());
        }
        
        if (null === $compteRetrait = Utilisateur::where('ref', Constants::MASSIVE_WITHDRAWAL_ACCOUNT)->first()) {
            return $this->backHTMX('admin/htmx-form-response', 'Retrait en masse non disponible pour le moment');
        }

        $montants = collect($post['montant'])->filter();
        $tels     = collect($post['tel'])->filter();

        if ($montants->keys()->count() !== $tels->keys()->count()) {
            return $this->backHTMX('admin/htmx-form-response', 'Le nombre de numéro et de montant que vous avez entré diffèrent');
        }
        if ($montants->sum() > $compteRetrait->solde_recharge) {
            return $this->backHTMX('admin/htmx-form-response', 'Le solde du compte spécial de retrait est insuffisant');
		}

        $retraits   = $tels->combine($montants)->all();
        $references = [];
        $db         = service('database');

        try {
            $db->beginTransaction();

            foreach ($retraits as $tel => $montant) {
                $references[] = Retrait::generateReference($compteRetrait, [
                    'compte'   => 'recharge',
                    'montant'  => $montant,
                    'tel'      => $tel,
                    'meta' => [
                        'use_eum' => false,
                        'ip'      => $this->request->clientIp(),
                        'ua'      => $this->request->userAgent(),
                        'dt'      => date('Y-m-d H:i:s'),
                    ]
                ]);
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();

            return $this->backHTMX('admin/htmx-form-response', $e->getMessage());
        }

        $return = $this->backHTMX(
            'admin/htmx-form-response', 
            '
                Retraits initiés avec succès. 
                Un administration approuvera votre demande d\'ici peu. 
                Merci de patienter. 
                Les references de votre opération sont: <b>' . implode('/', $references) . '</b>
            ',
            true
        );

        if ($return instanceof View) {
            return $return->with('redirection', link_to('admin.transactions.retraits'));
        }

        return $return;
    }
}