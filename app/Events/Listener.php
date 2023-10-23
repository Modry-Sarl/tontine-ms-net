<?php

namespace App\Events;

use App\Entities\Utilisateur;
use BlitzPHP\Contracts\Event\EventInterface;
use BlitzPHP\Contracts\Event\EventListenerInterface;
use BlitzPHP\Contracts\Event\EventManagerInterface;
use BlitzPHP\Database\Config\Services;

class Listener implements EventListenerInterface
{	
	public function listen(EventManagerInterface $event): void
	{
		$event->attach('user:register', function (EventInterface $eventInterface) {
			/** @var Utilisateur $user */
			$user       = $eventInterface->getTarget();
			$password   = $eventInterface->getParam('password');
			$inscriptor = $eventInterface->getParam('user');

			Services::mail()->to($user->user->getEmail(), $user->user->username)
				->subject('Bienvenue sur TONTINE MS-NET')
				->view('welcome', [
					'user'     => $user,
					'password' => $password
				])
				->send();
		});
	}
}