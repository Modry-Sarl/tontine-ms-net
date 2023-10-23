<?php

namespace App\Controllers;

use App\Entities\Inscription;
use App\Entities\Utilisateur;
use App\Models\UserModel;
use App\MS\App;
use App\MS\Register;
use BlitzPHP\Database\Config\Services;
use BlitzPHP\Exceptions\ValidationException;
use Exception;

class RegisterController extends AppController
{
    /**
     * Formulaire d'inscription de membre
     */
    public function form()
    {
        return view('dashboard/register/form')->with([
            'dernier_inscrit' => $this->user->inscriptions()->with('utilisateur')->latest()->first()?->utilisateur
        ]);
    }

    /**
     * Listes des inscriptions realisees par un membre
     */
    public function inscriptions()
    {
        return view('dashboard/register/inscriptions')->with([
            'inscriptions' => $this->user->inscriptions()->with('utilisateur')->latest()->all()
        ]);
    }

    /**
     * Traitement de l'inscription d'un membre
     */
    public function process()
    {
        try {
            $validated = $this->request->validate([
                'nbr_compte' => ['required', 'numeric', 'between:1,2'],
                'parrain'    => ['nullable', 'string', 'exists:utilisateurs,ref'],
                'pays'       => ['required'],
                'tel'        => ['required'],
                'mdp'        => ['nullable', 'string', 'min:6'],                       // mot de passe du membre
                'email'      => ['required', 'email'],
                'password'   => ['required', 'current_password'], 
            ], [
                'tel:required'              => 'Veuillez entrer le numéro de téléphone du membre',
                'email:required'            => 'Veuillez entrer l\'adresse email du membre',
                'email:email'               => 'L\'adresse email que vous avez entrer est invalide',
                'mdp:min'                   => 'Veuillez entrer un mot de passe ayant au moins 6 caracteres',
                'password:required'         => 'Veuillez entrer votre mot de passe pour valider l\'inscription',
                'password:current_password' => 'Mot de passe incorrect',
                'parrain:exists'            => 'Le parrain renseigné n\'existe pas',
            ]);
        } catch (ValidationException $e) {
            return $this->backHTMX('dashboard/register/htmx-form-response', $e->getErrors()->all());
        }

        $parrain = Utilisateur::where('ref', $validated['parrain'] ?: $this->user->ref)->with('filleuls')->first();

        if ($parrain->filleuls->count() > 1) {
            if ($parrain->niveau == 0) {
                model(UserModel::class)->valideNiveau($parrain, 1);
            }

            return $this->backHTMX('dashboard/register/htmx-form-response', 'Le parrain de reference `' . $parrain->ref.'` a déjà les 2 filleuls direct requis');
        } else if ($parrain->filleuls->count() == 1) {
            if ($validated['nbr_compte'] == 2) {
                return $this->backHTMX('dashboard/register/htmx-form-response', 'Le parrain de reference `' . $parrain->ref.'` a déjà les 1 filleul, il ne lui reste plus qu\'une inscription possible');
            } 
            model(UserModel::class)->valideNiveau($parrain, 1);
        } else if ($validated['nbr_compte'] == 2) {
            model(UserModel::class)->valideNiveau($parrain, 1);
        }

		$prix_inscription = App::getPrixInscription($this->user);
		$montant          = $prix_inscription * $validated['nbr_compte'];

		if ($montant > $this->user->solde_recharge) {
            return $this->backHTMX('dashboard/register/htmx-form-response', '
                Le solde de votre compte est insuffisant. 
                Veuillez recharger votre compte pour enregistrer ' . $validated['nbr_compte'] . ' nouveaux membres.
            ');
		}

        $refs = [];
		for ($i = 0; $i < $validated['nbr_compte']; $i++) {
            $refs[] = App::generateIdUser(); 
		}
        
        $comptes_crees = 0;
        $i             = (int) $validated['nbr_compte'];

        $register = new Register([
            'pays'    => $validated['pays'],
            'tel'     => $validated['tel'],
            'email'   => $validated['email'],
            'parrain' => $parrain->ref,
        ], $validated['mdp'] ?? '');

        $db = Services::database();
    
        try {
            $db->beginTransaction();
            
            while ($i > 0 && $this->user->solde_recharge >= $prix_inscription) {
                $registered = $register->process(array_pop($refs));

                $this->user->utilisateur->decrement('solde_recharge', $prix_inscription);

                $comptes_crees++;
			    $i--;
            }

            Inscription::create([
                'user_id'    => $registered->user_id,
                'inscriptor' => $this->user->id,
                'nbr'        => $comptes_crees,
                'ip'         => $this->request->clientIp(),
                'ua'         => $this->request->userAgent(),
            ]);

            Services::event()->trigger('user:register', $registered, [
                'password' => $register->password,
            ]);    

            $db->commit();
        }
        catch(Exception $e) {
            $db->rollback();

            return $this->backHTMX('dashboard/register/htmx-form-response', 'Une erreur s\'est produite lors de l\'enregistrement Veuillez reessayer');
        }

        return $this->backHTMX('dashboard/register/htmx-form-response', 'Ajout effectué avec succès . '.$comptes_crees.' compte(s) a/ont été(s) crées', true);
    }
}
