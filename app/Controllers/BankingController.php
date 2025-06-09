<?php

namespace App\Controllers;

use App\Entities\Retrait;
use App\Entities\Transaction;
use App\Entities\Utilisateur;
use App\MS\Payment;
use BlitzPHP\Container\Services;
use BlitzPHP\Exceptions\ValidationException;
use Exception;

class BankingController extends AppController
{
    /**
     * Formulaire de recharge de compte
     */
    public function formRecharge($data = null)
    {
        if (! is_array($data)) {
            $data = [];
        }

        if (empty($ref = $this->request->payment_ref)) { // monetbil
            $ref = $this->request->tx_ref; // flutterwave
        }
        
        $data['status'] = $this->request->status;

        if (in_array($data['status'], ['cancelled'])) {
            Payment::removeRef($ref);
        }
        else if (in_array($data['status'], ['success', 'successful'])) {
            /** @var PaymentController $controller */
            $controller = Services::factory(PaymentController::class);
            $controller->initialize($this->request, $this->response, $this->logger);
            $controller->notify($ref);

            return redirect()->route('recharge')->with('success', 'Recharge effectuée avec succès');
        }

        return view('dashboard/banking/recharge', $data)->with([
            'derniere_recharge' => $this->user->utilisateur->transactions()->entrees()->latest()->first()
        ]);
    }

    /**
     * Traitement du formulaire de la recharge
     */
    public function processRecharge()
    {
        try {
            $validated = $this->validate([
                'montant' => ['required', 'numeric', 'min:1', 'max:2000']
            ], [
                'montant:required' => 'Veuillez indiquer le montant de la recharge',
                'montant:numeric'  => 'Veuillez entrer un montant valide',
                'montant:min'      => 'Vous ne pouvez pas recharger moins de 1 $',
                'montant:max'      => 'Vous ne pouvez pas recharger plus de 2000 $',
            ]);
        } catch (ValidationException $e) {
            return $this->backHTMX('dashboard/banking/htmx-form-recharge', $e->getErrors()->all());
        }

        $montant = !is_online() ? 100 : to_cfa($validated['montant'], 'entree');
        $frais   = ceil(0.01 * $montant);

        try {
            $data = [
                'payment' => Payment::service(Payment::FLUTTERWAVE)->init([
                    'frais'  => $frais,
                    'amount' => (int) ($montant + $frais),
                    'phone'  => simple_tel($this->user->tel),
                    'user'   => $this->user->utilisateur->id,
                    'username' => $this->user->username,
                    'useremail' => $this->user->getEmail(),
                ]),
                'service' => Payment::FLUTTERWAVE,
                'montant' => $validated['montant'],
            ];
        } catch (Exception $e) {
            return $this->backHTMX('dashboard/banking/htmx-form-recharge', $e->getMessage());
        }

        if ($this->isHTMX()) {
            return view('dashboard/banking/htmx-form-recharge', $data);   
        }
        
        return $this->formRecharge($data);
    }   

    /**
     * Formulaire de retrait
     */
    public function formRetrait($data = null)
    {
        if (! is_array($data)) {
            $data = [];
        }

        return view('dashboard/banking/retrait', $data)->with([
            'dernier_retrait' => $this->user->utilisateur->transactions()->sorties()->latest()->first()
        ]);
    }

    /**
     * Traitement du formulaire du retrait
     */
    public function processRetrait()
    {
        try {
            $validated = $this->validate([
                'montant'  => ['required', 'numeric', 'min:1', 'max:2000'],
                'compte'   => ['required', 'in:principal,recharge'],
                // 'tel'      => ['required', 'phone:cm'],
                'password' => ['required', 'current_password'],
            ], [
                'montant:required'          => 'Veuillez indiquer le montant à retirer',
                'montant:numeric'           => 'Veuillez entrer un montant valide',
                'montant:min'               => 'Vous ne pouvez pas retirer moins de 1 $',
                'montant:max'               => 'Vous ne pouvez pas retirer plus de 2000 $',
                'compte:required'           => 'Veuillez selectionner le compte à débiter pour le retrait',
                'compte:in'                 => 'Veuillez selectionner un compte valide',
                'tel:require'               => 'Veuillez entrer le numéro de téléphone du bénéficiare',
                'tel:phone'                 => 'Veuillez entrer un numéro de téléphone valide',
                'password:required'         => 'Veuillez entrer votre mot de passe pour valider l\'inscription',
                'password:current_password' => 'Mot de passe incorrect',
            ]);

        } catch (ValidationException $e) {
            return $this->backHTMX('dashboard/banking/htmx-form-retrait', $e->getErrors()->all());
        }
        
        if ($validated['montant'] > $this->user->{'solde_' . $validated['compte']}) {
            return $this->backHTMX('dashboard/banking/htmx-form-retrait', 'Votre solde '.$validated['compte'].' est insuffisant');
		}
        
        try {
            $reference = Retrait::generateReference($this->user->utilisateur, [
                'compte'   => $validated['compte'],
                'montant'  => $validated['montant'],
                'tel'      => $validated['tel'] ?? $this->user->tel,
                'meta' => [
                    'use_eum' => $this->request->boolean('eum'),
                    'ip'      => $this->request->clientIp(),
                    'ua'      => $this->request->userAgent(),
                    'dt'      => date('Y-m-d H:i:s'),
                ]
            ]);
        } catch (Exception $e) {
            return $this->backHTMX('dashboard/banking/htmx-form-retrait', $e->getMessage());
        }

        return $this->backHTMX('dashboard/banking/htmx-form-retrait', 
            'Transaction initiée avec succès. Un administration approuvera votre retrait d\'ici peu. Merci de patienter. La reference de votre opération est <b>'.$reference.'</b>',
            true
        );
    } 

    /**
     * Formulaire de transfert
     */
    public function formTransfert($data = null)
    {
        if (! is_array($data)) {
            $data = [];
        }

        return view('dashboard/banking/transfert', $data)->with([
            'dernier_transfert' => $this->user->utilisateur->transactions()->transferts()->latest()->first()
        ]);
    }

    /**
     * Traitement du formulaire du transfert
     */
    public function processTransfert()
    {
        try {
            $validated = $this->validate([
                'montant'  => ['required', 'numeric', 'min:1', 'max:2000'],
                'compte'   => ['required', 'in:principal,recharge'],
                'cible'   => ['required', 'in:principal,recharge'],
                'ref'      => ['required', 'exists:utilisateurs'],
                'password' => ['required', 'current_password'],
            ], [
                'montant:required'          => 'Veuillez indiquer le montant à retirer',
                'montant:numeric'           => 'Veuillez entrer un montant valide',
                'montant:min'               => 'Vous ne pouvez pas retirer moins de 1 $',
                'montant:max'               => 'Vous ne pouvez pas retirer plus de 2000 $',
                'compte:required'           => 'Veuillez selectionner le compte à débiter lors du transfert',
                'compte:in'                 => 'Veuillez selectionner un compte de retrait valide',
                'cible:required'            => 'Veuillez selectionner le compte à créditer lors du transfert pour le retrait',
                'cible:in'                  => 'Veuillez selectionner une compte de dépôt valide',
                'ref:required'              => 'Veuillez entrer l\'identifiant du bénéficiare',
                'ref:exists'                => 'Identifiant non reconnu',
                'password:required'         => 'Veuillez entrer votre mot de passe pour valider l\'inscription',
                'password:current_password' => 'Mot de passe incorrect',
            ]);
        } catch (ValidationException $e) {
            return $this->backHTMX('dashboard/banking/htmx-form-transfert', $e->getErrors()->all());
        }
        
		if ($this->user->ref == $validated['ref'] && $validated['compte'] == $validated['cible']) {
            return $this->backHTMX('dashboard/banking/htmx-form-transfert', 'Vous ne pouvez pas déplacer l\'argent dans le meme compte.');	
		}

        $solde       = 'solde_' . $validated['compte'];
        $solde_cible = 'solde_' . $validated['cible'];
        
		if ($validated['montant'] > $this->user->{$solde}) {
            return $this->backHTMX('dashboard/banking/htmx-form-transfert', 'Votre solde '.$validated['compte'].' est insuffisant');
		}
        
        $db = Services::database();
    
        try {
            $db->beginTransaction();
           
            $this->user->utilisateur->decrement($solde, $validated['montant']);
            Utilisateur::where('ref', $validated['ref'])->increment($solde_cible, $validated['montant']);
            
            Transaction::create([
                'user_id'                 => $this->user->utilisateur->id,
                'numero'                  => $validated['ref'],
                'montant'                 => $validated['montant'],
                'frais'                   => 0,
                'type'                    => 'transfert',
                'statut'                  => 1,
                'message'                 => '',
                'operateur'               => $validated['compte'],
                'operator_transaction_id' => $validated['cible'],
                'ip'                      => $this->request->clientIp(),
                'ua'                      => $this->request->userAgent(),
                'dt'                      => date('Y-m-d H:i:s'),
            ]);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            return $this->backHTMX('dashboard/banking/htmx-form-transfert', $e->getMessage());
        }

        return $this->backHTMX('dashboard/banking/htmx-form-transfert', 'Transfert effectué avec succès.', true);
    } 

}
