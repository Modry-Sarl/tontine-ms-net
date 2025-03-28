<?php

namespace App\Listeners;

use BlitzPHP\Contracts\Event\EventListenerInterface;
use BlitzPHP\Contracts\Event\EventManagerInterface;
use BlitzPHP\HotReloader\HotReloader;

/**
 * Ecouteurs d'évènements liés aux fonctionnalités internes du framework
 * 
 * A moins que vous savez ce que vous faites, nous vous demandons d'éviter 
 * au maximun de modifier ce fichier au risque d'entrainer des disfonctionnements de l'application.
 */
class SystemListener implements EventListenerInterface
{
    public function listen(EventManagerInterface $manager): void
    {
        $manager->on('pre_system', function() {
            $this->addHotReloadRoute();
        });
    }

    /**
     * Definie la route pour le hot reload
     */
    private function addHotReloadRoute()
    {
        service('routes')->environment('dev', function($routes) {
            $routes->get('__hot-reload', static fn () => (new HotReloader())->run());
        });
    }
}
