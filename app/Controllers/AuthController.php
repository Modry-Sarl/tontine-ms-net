<?php

namespace App\Controllers;

use App\Models\UserModel;
use BlitzPHP\Schild\Config\Services;

class AuthController extends AppController
{
    public function login()
    {
        $credentials = $this->request->validate([
            'login' => ['required', 'string'],
            'password' => ['required']
        ]);

        $user = model(UserModel::class)->findForAuth($credentials['login']);

        if (!$user) {
            return back()->withInput()->withErrors('Utilisateur non reconnu ou mot de passe invalide.');
        }
        
        if (! Services::passwords()->verify($credentials['password'], $user->password_hash)) {
            return back()->withInput()->withErrors('Utilisateur non reconnu ou mot de passe invalide.');
        }

        auth('session')->remember($this->request->boolean('remember'))->login($user);

        return redirect()->route('dashboard');
    }

    public function logout() 
    {
        auth('session')->logout();

        return redirect()->route('dashboard');
    }
}
