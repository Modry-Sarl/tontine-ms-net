<?php

namespace App\Controllers;

use App\Entities\Utilisateur;
use App\Models\UserModel;

class AdminerController extends AppController
{
    protected $helpers = [
        'scl'
    ];

    /**
     * Informations utiles
     */
    public function infos()
    {
        return view('dashboard/adminer/infos');
    }

    /**
     * Comptes
     */
    public function comptes()
    {
        $comptes = $this->user->comptes()->get();

        return view('dashboard/adminer/comptes')->with('comptes', $comptes);
    }
    
    /**
     * Progression du membre
     */
    public function progression()
    {
        $niveaux = $this->user->niveaux->map(fn($n) => $n->niveau)->all();

        $iteration = $this->getIteration();

        return view('dashboard/adminer/progression', compact('niveaux', 'iteration'));
    }

    /**
     * Liste des filleuls du membre
     */
    public function filleuls()
    {
        $filleuls  = $this->user->utilisateur->getListFilleulsAttribute(true);
        $iteration = $this->getIteration();

        return view('dashboard/adminer/filleuls', compact('filleuls', 'iteration'));
    }

    /**
     * Arbre de filleuls
     */
    public function arbre()
    {
        if (! $user = Utilisateur::with('user')->where('ref', $this->request->ref)->first()) {
            return show404('Utilisateur non trouvé');
        }

        $filleuls = model(UserModel::class)->listFilleuls($user, ['limit' => 5], true);
        
        return view('dashboard/adminer/arbre', compact('filleuls', 'user'));
    }
}
