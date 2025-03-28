<?php

namespace App\Listeners;

use App\Entities\Utilisateur;
use BlitzPHP\Contracts\Event\EventInterface;
use BlitzPHP\Contracts\Event\EventListenerInterface;
use BlitzPHP\Contracts\Event\EventManagerInterface;
use BlitzPHP\Database\Config\Services;
use BlitzPHP\Wolke\Pagination\Paginator;

class AppListener implements EventListenerInterface
{	
	public function listen(EventManagerInterface $event): void
	{
		$event->on('user:register', function (EventInterface $eventInterface) {
			/** @var Utilisateur $user */
			$user       = $eventInterface->getTarget();
			$password   = $eventInterface->getParam('password');
			$inscriptor = $eventInterface->getParam('user');

			Services::mail()->to($user->user->getEmail(), $user->user->username)
				->subject('Bienvenue sur ' . strtoupper(config('app.name')))
				->view('welcome', [
					'user'     => $user,
					'password' => $password
				])
				->send();
		});

		$event->on('pre_system', function () {
            Paginator::viewFactoryResolver(fn() => service('viewer'));
			Paginator::useBootstrap();
        });
	}
}