<?php

namespace App\Controllers;

use App\Entities\Notification;
use App\Entities\Utilisateur;
use App\Models\UserModel;
use App\MS\Constants;
use BlitzPHP\Schild\Models\LoginModel;
use BlitzPHP\View\View;

class DashboardController extends AppController
{

    public function index()
    {
        $comptes = $this->user->comptes()->all();
        
        $data = [
            'derniere_connexion'  => model(LoginModel::class)->previousLogin($this->user),
        ];

        $this->updateMatrice($comptes);

        return $this->view('dashboard', $data)->with('comptes', $comptes);
    }


    /**
     * Mise a jour de la matrice et donc de l'argent
     * 
     * @param array<Utilisateur> $comptes
     */
    private function updateMatrice($comptes)
    {
        $model = model(UserModel::class);

        $principal = array_filter($comptes, fn($c) => $c->main)[0] ?? null;
        
        foreach ($comptes as $compte) {
            $filleuls = $model->listFilleuls($compte);
                
            // On met a jour son niveau et donc, son argent					
            for ($i = 1; $i <= Constants::NBR_NIVEAU; $i++) {
                $nb_filleuls = count($filleuls[$i]);
                
                if ($nb_filleuls >= Constants::nbrFilleulByNiveau($i))  {
                    $model->valideNiveau($compte, $i);
                }
            }

            // on transfert l'argent vers le compte principal
            if ($principal && $principal->ref !== $compte->ref) {
                $solde = $compte->solde_principal;
                if ($solde > 0) {
                    $compte->decrement('solde_principal', $solde);
                    $principal->increment('solde_principal', $solde);

                    Notification::virementFonds($principal, $compte, $solde, 'entree');
					Notification::virementFonds($compte, $principal, $solde, 'sortie');
                }
            }
        }
        
        $this->user->utilisateur = Utilisateur::find($this->user->utilisateur->id);
        View::share('_user', $this->user);
    }
}
