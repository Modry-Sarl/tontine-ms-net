<?php

namespace App\MS;

use App\Entities\User;
use App\Entities\Utilisateur;
use App\Models\UserModel;
use BlitzPHP\Facades\Storage;
use BlitzPHP\Schild\Config\Services;
use BlitzPHP\Utilities\String\Text;
use Exception;

class Register
{
    private array $data = [];

    public string $password = '';
    private string $password_hash = '';

    private ?User $user = null;

    private UserModel $provider;

    private bool $exist = true;

    public function __construct(array $data, string $password)
    {
        $this->provider = model(UserModel::class);

        unset($data['password']);
        $data['tel'] = simple_tel($data['tel']);
        $this->data  = $data;

        $this->findUser();
        $this->findPassword($password);
    }


    public function process(?string $ref = null): Utilisateur
    {
        if (empty($ref)) {
            $ref = App::generateIdUser();
        }
        
        $user = $this->findOrCreateUser();

        $utilisateur = Utilisateur::create([
            'user_id' => $user->id,
            'ref'     => $ref,
            'parrain' => $this->data['parrain'] ?? null,
            'main'    => $this->exist === false,
        ]);

        return $utilisateur;
    }

    /**
     * 'Recupere le mot de passe hasher a utiliser
     */
    public function findPassword(string $password): void
    {
        if ($this->user) {
            $this->password_hash = $this->user->password_hash;
        } else {
            if (empty($password)) {
                $password = $this->data['tel'] ?? Text::random(8);
            }
            $this->password = $password;
            $this->password_hash = Services::passwords()->hash($this->password);
        }
    }

    /**
     * Recupere un utilisateur avec les meme donnees si il existe
     */
    private function findUser(): void
    {
        $user =  User::where('tel', $this->data['tel'])->first();
        if ($user) {
            $this->user = $this->provider->findById($user->user_id, true);
        } else {
            $this->user = $this->provider->findByCredentials(['email' => $this->data['email']]);
        }
    }

    private function findOrCreateUser(): User
    {
        if (!$this->user) {
            $user = new User();

            $user->fill([
                'username'   => $this->randomUsername(),
                'tel'        => $this->data['tel'],
                'num_compte' => time(),
                'pays'       => $this->data['pays'],
            ]);

            try {
                $user->setEmail($this->data['email']);
                $user->setPasswordHash($this->password_hash);
                $user->save();
                $user->saveEmailIdentity();

                // Ajouter au groupe par défaut
                $this->provider->addToDefaultGroup($user);
            } catch (Exception $e) {
                throw $e;
            }

            Services::event()->emit('register', $user);

            // Activer l'utilisateur
            $user->activate();

            $this->user = $user;

            $this->exist = false;
        }
        
        return $this->user;
    }

    private function randomUsername(): string 
    {
        static $username = '';

        if (!empty($username)) {
            return $username;
        }

        $names = explode("\n", Storage::get('attached/words.txt'));
        $names = array_filter($names);
        $names = array_map('trim', $names);
        
        return $username = $names[array_rand($names)];
	}
}
