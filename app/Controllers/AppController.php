<?php

namespace App\Controllers;

use App\Entities\User;
use App\MS\Constants;
use BlitzPHP\Controllers\ApplicationController;
use BlitzPHP\View\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * AppController fournit un emplacement pratique pour charger des composants et exécuter des fonctions nécessaires à tous vos contrôleurs.
 * Étendez cette classe dans tous les nouveaux contrôleurs :
 *     class HomeController extends AppController
 *
 * Pour des raisons de sécurité, assurez-vous de déclarer toutes les nouvelles méthodes comme protégées ou privées.
 */
abstract class AppController extends ApplicationController
{
    /**
     * Un tableau d'helpers à charger automatiquement lors de l'instanciation de la classe.
     * Ces helpers seront disponibles pour tous les autres contrôleurs qui étendent BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    protected User $user;

    /**
     * Constructeur.
     */
    public function initialize(ServerRequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Ne pas modifier cette ligne
        parent::initialize($request, $response, $logger);

        // Préchargez tous les modèles, bibliothèques, etc., ici.

        $this->getLoggedUser();
    }

    protected function getLoggedUser() 
    {
        
        
        if (auth()->loggedIn()) {
            $reflection = new ReflectionClass($this);
            if (str_starts_with($reflection->getNamespaceName(), 'App\Controllers\Admin')) {
                $this->user = User::with('utilisateur')->find(auth()->id());
            } else {
                $this->user = User::with(['utilisateur' => function($q) {
                    $q->with(['notifications' => fn($q) => $q->where('lu', 0)->limit(5) ]);
                }])->find(auth()->id());
                
                $total_notifications = $this->user->utilisateur->notifications()->count();
                View::share('total_notifications', $total_notifications);
            }

            $this->user->email = auth()->user()->email;

            View::share('_user', $this->user);
        }
    }

    /**
     * Renvoie le nombre d'interation pour l'affichage de la progression en fonction du pack actuel de l'utilisateur.
     */
    protected function getIteration(?string $pack = null): int 
    {
        return Constants::getIteration($pack ?: $this->user->pack);
    }
    

    /**
     * Undocumented function
     *
     * @param string|array $key si success vaut true, alors $key est le message de succes sinon c'est l'erreur ou l'ensemble des erreur
     * 
     * @return Response|View
     */
    protected function backHTMX(string $view, string|array $key, bool $success = false)
    {
        if ($success) {
            $this->request->session()->flash('success', $key);
        } else {
            $this->request->session()->flashErrors($key);
        }

        if ($this->isHTMX()) {
            return view($view);
        }

        return $success ? back() : back()->withInput();
    }

    protected function isHTMX(): bool
    {
        return $this->request->getAttribute('htmx');
    }
}
