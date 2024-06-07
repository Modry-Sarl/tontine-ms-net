<?php

namespace App\Controllers;

use App\Services\Registration;
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
    public function process($_, Registration $registrationService)
    {
        try {
            $comptes_crees = $registrationService->register($this->request, $this->user, false);
         } catch (Exception $e) {
             $message = $e instanceof ValidationException ? $e->getErrors()->all() : $e->getMessage();
             
             return $this->backHTMX('dashboard/register/htmx-form-response', $message);
         }
 
         return $this->backHTMX('dashboard/register/htmx-form-response', 'Ajout effectué avec succès . '.$comptes_crees.' compte(s) a/ont été(s) crées', true);
    }
}
