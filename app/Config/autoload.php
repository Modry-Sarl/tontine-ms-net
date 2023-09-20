<?php

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * Ce fichier définit les espaces de noms et les mappages de classes 
 * afin que l'autoloader puisse trouver les fichiers selon les besoins.
 *
 * REMARQUE : si vous utilisez une clé identique dans $psr4 ou $classmap, 
 * les valeurs de ce fichier écraseront les valeurs du framework.
 */
return [
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * Cela mappe les emplacements de tous les espaces de noms de votre application à leur emplacement 
     * sur le système de fichiers. Ceux-ci sont utilisés par le chargeur automatique pour localiser les 
     * fichiers la première fois qu'ils ont été instanciés.
     *
     * Le répertoire '/app' est déjà mappés pour vous.
     * vous pouvez changer le nom de l'espace de noms "App" si vous le souhaitez, mais cela doit être fait 
     * avant de créer des classes d'espace de noms, sinon vous devrez modifier toutes ces classes pour que 
     * cela fonctionne.
     *
     * Prototype:
     *   'psr4' => [
     *      'BLITZ' => SYSTEM_PATH,
     *      'App'   => APP_PATH
     *   ],
     *
     * @var array<string, array<int, string>|string>
     * @phpstan-var array<string, string|list<string>>
     */
    'psr4' => [
        APP_NAMESPACE => APP_PATH, // Pour l'espace de noms d'application personnalisé
    ],

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     * La carte de classe fournit une carte des noms de classe et leur emplacement exact sur le lecteur. 
     * Les classes chargées de cette manière auront des performances légèrement plus rapides car elles 
     * n'auront pas à être recherchées dans un ou plusieurs répertoires comme elles le feraient si elles 
     * étaient chargées automatiquement via un espace de noms.
     *
     * Prototype:
     *   'classmap' => [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ],
     *
     * @var array<string, string>
     */
    'classmap' => [],

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * Le tableau files fournit une liste de chemins vers les fichiers __non-class__ 
     * qui seront chargés automatiquement. 
     * Cela peut être utile pour les opérations d'amorçage ou pour charger des fonctions.
     *
     * Prototype:
     *   'files' = [
     *       '/path/to/my/file.php',
     *   ],
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    'files' => [],

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    'helpers' => [
        'path',
        'app',
    ],
];
