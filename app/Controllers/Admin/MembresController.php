<?php

namespace App\Controllers\Admin;

use App\Controllers\AppController;
use App\Entities\User;
use App\Entities\Utilisateur;
use App\Models\UserModel;
use BlitzPHP\Exceptions\ValidationException;
use Exception;

class MembresController extends AppController
{
    protected $helpers = ['scl'];

    public function index()
    {
        $data['search'] = $search = $this->request->search;

        /** @var \BlitzPHP\Database\Builder\BaseBuilder $db */
        $db = service('builder');

        $db = $db->select('utilisateurs.id')->from('utilisateurs')->join('users', ['users.id' => 'utilisateurs.user_id']);
        if (!empty($search)) {
            $db = $db->orWhereLike([
                'ref'      => $search,
                'tel'      => simple_tel($search),
                'username' => $search
            ]);
        }

        $ids = $db->values('id');

        $data['users'] = Utilisateur::with('user')->whereIn('id', $ids)->paginate(25, [], 'page', $this->request->integer('page'));
        
        return $this->render($data);
    }

    public function show()
    {
        $data['ref'] = $this->request->ref;
        $data['tab'] = $this->request->data('tab', 'generalites');
        $model = model(UserModel::class);

        if (! empty($data['ref'])) {
            $user = Utilisateur::with(['user'])->where('ref', $data['ref'])->first();

            if (! empty($user)) {
                $data['user']         = $user;
                
                if ($data['tab'] == 'generalites') {
                    $data['niveaux']      = $user->niveaux->map(fn($n) => $n->niveau)->all();
                    $data['iteration']    = $this->getIteration($user->pack);
                    $data['filleuls']     = $model->listFilleuls($user, null, true);
                    $data['nbr_filleuls'] = $model->makeCountFilleuls($data['filleuls']);
                }

                else if ($data['tab'] == 'transactions') {
                    $transactions = $user->transactions;

                    $data['entrees']    = $transactions->filter(fn($t) => $t->type === 'entree');
                    $data['sorties']    = $transactions->filter(fn($t) => $t->type === 'sortie');
                    $data['transferts'] = $transactions->filter(fn($t) => $t->type === 'transfert');
                }

                else if ($data['tab'] == 'inscriptions') {
                    $data['inscriptions'] = $user->user->inscriptions()->with('utilisateur')->latest()->all();
                }
            }
        }

        return $this->render($data);
    }

    public function formConfig()
    {
        $data['ref'] = $this->request->ref;
        $data['tab'] = $this->request->data('tab', 'profil');
        $model = model(UserModel::class);

        if (! empty($data['ref'])) {
            $user = Utilisateur::with(['user'])->where('ref', $data['ref'])->first();

            if (! empty($user)) {
                $data['user'] = $user;
            }
        }

        return $this->render('config', $data);
    }

    public function processConfig()
    {
        $tab = $this->request->data('tab', 'profil');

        if (empty($tab) || !in_array($tab, ['profil', 'access', 'identity', 'permutation'])) {
            return redirect()->back();
        }

        $rules = [
            'password' => ['required', 'current_password'],
            'ref'      => ['required', 'exists:utilisateurs'],
        ];
        $messages = [
            'ref:required'              => 'Veuillez entrer l\'identifiant du membre',
            'ref:exists'                => 'Identifiant non reconnu',
            'password:required'         => 'Veuillez entrer votre mot de passe pour valider la modification',
            'password:current_password' => 'Mot de passe incorrect',
        ];

        if ($tab == 'profil') {
            $rules += [
                'username' => ['required', 'string', 'between:1,25']
            ];
            $messages += [
                'username:required' => 'Veuillez indiquer le pseudonyme du membre',
                'username:string'   => 'Veuillez entrer un pseudonyme valide',
                'username:between'  => 'Le pseudonyme doit etre entre 1 et 25 caracteres',
            ];
        } elseif ($tab == 'access') {
            $rules += [
                'tel'   => ['nullable'],
                'pwd'   => ['nullable', 'string', 'min:6'],   // mot de passe du membre
                'email' => ['nullable', 'email'],
            ];
            $messages += [
                'email:email'      => 'L\'adresse email que vous avez entrer est invalide',
                'pwd:min'          => 'Veuillez entrer un mot de passe ayant au moins 6 caracteres',
                'username:string'  => 'Veuillez entrer un pseudonyme valide',
                'username:between' => 'Le pseudonyme doit etre entre 1 et 25 caracteres',
            ];
        } elseif ($tab == 'identity') {

        } elseif ($tab == 'permutation') {
            $rules += [
                'parrain'   => ['required', 'string', 'exists:utilisateurs,ref'],
                'permut'   => ['nullable', 'string', 'exists:utilisateurs,ref'],
            ];
            $messages += [
                'parrain:exists' => 'Le parrain renseigné n\'existe pas',
                'permut:exists'  => 'Le membre avec lequel on souhaite permuter n\'existe pas',
            ];
        }

        try {
            $validated = $this->validate($rules, $messages);
        } catch (ValidationException $e) {
            return $this->backHTMX('admin/membres/config.htmx-form-response', $e->getErrors()->all());
        }
        
        $user = Utilisateur::with(['user'])->where('ref', $validated['ref'])->first();
                
        /** @var \BlitzPHP\Database\Connection\BaseConnection $db */
        $db = service('database');
    
        try {
            $db->beginTransaction();
           
            if ($tab == 'profil') {
                $user->user->username = $validated['username'];
                $user->user->save();
            }
            else if ($tab == 'access') {
                if (!empty($validated['tel'])) {
                    $user->user->tel = $validated['tel'];
                }
                if (!empty($validated['pwd'])) {
                    $user->user->setPassword($validated['pwd']);
                }
                if (!empty($validated['email'])) {
                    $user->user->setEmail($validated['email']);
                }
                $user->user->saveEmailIdentity();
                $user->user->save();
            }
            elseif ($tab == 'permutation') {
                if ($validated['parrain'] !== $user->parrain) {
                    $parrain = Utilisateur::where('ref', $validated['parrain'])->with('filleuls')->first();

                    if ($parrain->filleuls->count() > 1) {
                        if ($parrain->niveau == 0) {
                            model(UserModel::class)->valideNiveau($parrain, 1);
                        }

                        return $this->backHTMX('admin/membres/config.htmx-form-response', 'Le parrain de reference `' . $parrain->ref.'` a déjà les 2 filleuls direct requis');
                    }
                    $user->parrain = $validated['parrain'];
                    $user->save();
                }
                
                if (!empty($validated['permut'])) {
                    $permut = Utilisateur::with('user')->where('ref', $validated['permut'])->first();
                    /** @var User $permut */
                    $permut = $permut->user;
                    /** @var User $_user */
                    $_user = $user->user;
                    $tmp   = clone $_user;

                    $_user->setRawAttributes(['id' => $_user->id] + $permut->getAttributes());
                    $permut->setRawAttributes(['id' => $permut->id] + $tmp->getAttributes());
                    $_user->save();
                    $permut->save();

                    $user_email   = $_user->getEmail();
                    $user_pass    = $_user->getPasswordHash();
                    $permut_email = $permut->getEmail();
                    $permut_pass  = $permut->getPasswordHash();

                    $permut->setEmail(uniqid()); // Deux enregistrement ne doivent pas avoir le meme email, donc on met d'abord un fake
                    $permut->setPasswordHash($user_pass);
                    $permut->saveEmailIdentity();

                    $_user->setEmail($permut_email);
                    $_user->setPasswordHash($permut_pass);
                    $_user->saveEmailIdentity();
                    $permut->setEmail($user_email); // puis on remet l'email normal
                    $permut->saveEmailIdentity();
                }
            }


            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            return $this->backHTMX('admin/membres/config.htmx-form-response', $e->getMessage() . ': ' . $e->getTraceAsString());
        }

        return $this->backHTMX('admin/membres/config.htmx-form-response', 'Modification effectuée avec succès.', true);
    }
}
