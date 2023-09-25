<?php

namespace App\Controllers;

use App\MS\Constants;

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

        $iteration = Constants::NBR_NIVEAU;
        $pack = strtolower($this->user->pack);

        if ($pack === 'argent') {
            $iteration -= 10;
        } elseif ($pack === 'or') {
            $iteration -= 5;
        }

        return view('dashboard/adminer/progression', compact('niveaux', 'iteration'));
    }
}
