<?php 

namespace App\Services;

use App\Entities\Inscription;
use App\Entities\User;
use App\Entities\Utilisateur;
use App\Models\UserModel;
use App\MS\App;
use App\MS\Register;
use BlitzPHP\Database\Config\Services;
use BlitzPHP\Http\Request;
use Exception;

class Registration
{
    public function register(Request $request, User $user, bool $asAdmin = false): int
    {
        $validated = $request->validate([
            'nbr_compte' => ['required', 'numeric', 'between:1,2'],
            'parrain'    => [ $asAdmin ? 'required' : 'nullable', 'string', 'exists:utilisateurs,ref'],
            'pays'       => ['required'],
            'tel'        => ['required'],
            'mdp'        => [ $asAdmin ? 'required' : 'nullable', 'string', 'min:6'],                       // mot de passe du membre
            'email'      => ['required', 'email'],
            'password'   => ['required', 'current_password'], 
        ], [
            'tel:required'              => 'Veuillez entrer le numéro de téléphone du membre',
            'email:required'            => 'Veuillez entrer l\'adresse email du membre',
            'email:email'               => 'L\'adresse email que vous avez entrer est invalide',
            'mdp:required'              => 'Veuillez entrer un mot de passe au membre à ajouter',
            'mdp:min'                   => 'Veuillez entrer un mot de passe ayant au moins 6 caracteres',
            'password:required'         => 'Veuillez entrer votre mot de passe pour valider l\'inscription',
            'password:current_password' => 'Mot de passe incorrect',
            'parrain:required'          => 'Veuillez entrer l\'identifiant du parrain du membre à ajouter',
            'parrain:exists'            => 'Le parrain renseigné n\'existe pas',
        ]);

        $parrain = Utilisateur::where('ref', $validated['parrain'] ?: $user->ref)->with('filleuls')->first();

        if ($parrain->filleuls->count() > 1) {
            if ($parrain->niveau == 0) {
                model(UserModel::class)->valideNiveau($parrain, 1);
            }

            throw new Exception('Le parrain de reference `' . $parrain->ref.'` a déjà les 2 filleuls direct requis');
        } else if ($parrain->filleuls->count() == 1) {
            if ($validated['nbr_compte'] == 2) {
                throw new Exception('Le parrain de reference `' . $parrain->ref.'` a déjà les 1 filleul, il ne lui reste plus qu\'une inscription possible');
            } 
            model(UserModel::class)->valideNiveau($parrain, 1);
        } else if ($validated['nbr_compte'] == 2) {
            model(UserModel::class)->valideNiveau($parrain, 1);
        }

        if (! $asAdmin) {
            $prix_inscription = App::getPrixInscription($user);
            $montant          = $prix_inscription * $validated['nbr_compte'];

            if ($montant > $user->solde_recharge) {
                throw new Exception('
                    Le solde de votre compte est insuffisant. 
                    Veuillez recharger votre compte pour enregistrer ' . $validated['nbr_compte'] . ' nouveaux membres.
                ');
            }
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
            
            while (($asAdmin && $i > 0) || (!$asAdmin && $i > 0 && $user->solde_recharge >= $prix_inscription)) {
                $registered = $register->process(array_pop($refs));

                if (!$asAdmin) {
                    $user->utilisateur->decrement('solde_recharge', $prix_inscription);
                }

                $comptes_crees++;
			    $i--;
            }

            if (!$asAdmin) {
                Inscription::create([
                    'user_id'    => $registered->user_id,
                    'inscriptor' => $user->id,
                    'nbr'        => $comptes_crees,
                    'ip'         => $request->clientIp(),
                    'ua'         => $request->userAgent(),
                ]);
            }

            Services::event()->trigger('user:register', $registered, [
                'password' => $register->password,
            ]);    

            $db->commit();
        }
        catch(Exception $e) {
            $db->rollback();

            throw new Exception('Une erreur s\'est produite lors de l\'enregistrement Veuillez reessayer');
        }

        return $comptes_crees;
    }
}