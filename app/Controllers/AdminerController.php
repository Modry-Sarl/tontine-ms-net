<?php

namespace App\Controllers;

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
    
    public function progression()
    {
        $niveaux = $this->user->niveaux->map(fn($n) => $n->niveau)->all();

        return view('dashboard/adminer/progression')->with('niveaux', $niveaux);
    }
}
