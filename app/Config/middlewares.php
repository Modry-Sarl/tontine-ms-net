<?php

use BlitzPHP\Http\MiddlewareQueue;

/**
 * ------------------------------------------------- -------------------------
 * Configuration du gestionnaire de middleware 
 * ------------------------------------------------- -------------------------
 */

return [
    /**
     * Configure les alias pour les classes Middleware afin de rendre la lecture plus agréable et plus simple.
     * 
     * @var array<string, string>
     */
    'aliases' => [
        'body-parser'   => \BlitzPHP\Middlewares\BodyParser::class,
        'cors'          => \BlitzPHP\Middlewares\Cors::class,
        'secureheaders' => \BlitzPHP\Middlewares\SecureHeaders::class,
    ],

    /**
     * Liste des alias de middlewares toujours appliqués à chaque requête.
     * 
     * @var array<string|Closure|class-string>
     */
    'globals' => [
        // \BlitzPHP\Middlewares\ForceHTTPS::class, // Forcer les requêtes sécurisées globales
        // \BlitzPHP\Middlewares\PageCache::class, // Mise en cache des pages Web
        // \BlitzPHP\Middlewares\PerformanceMetrics::class, // Mesures de performances

        \App\Middlewares\HTMX::class
    ],

    /**
     * Configuration personnalisée du gestionnaire de middleware
     * 
     * @var Closure
     */
    'build' => function (MiddlewareQueue $queue) {
        
    },
];
