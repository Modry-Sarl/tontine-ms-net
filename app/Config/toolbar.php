<?php

/**
 * ------------------------------------------------- -------------------------
 * Barre d'outils de débogage
 * ------------------------------------------------- -------------------------
 *
 * La barre d'outils de débogage permet d'afficher des informations sur les performances
 * et l'état de votre application lors de l'affichage de cette page. Par défaut, ce sera
 * NE PAS être affiché dans les environnements de production
 */

return [
    /**
     * Liste des collecteurs de barre d'outils qui seront appelés lors du débogage de la barre d'outils
     * se déclenche et collecte des données à partir de.
     *
     * @var string[]
     */
    'collectors' => [
        \BlitzPHP\Debug\Toolbar\Collectors\TimersCollector::class,
        \BlitzPHP\Debug\Toolbar\Collectors\LogsCollector::class,
        \BlitzPHP\Debug\Toolbar\Collectors\ViewsCollector::class,
        \BlitzPHP\Debug\Toolbar\Collectors\FilesCollector::class,
        \BlitzPHP\Debug\Toolbar\Collectors\RoutesCollector::class,
        \BlitzPHP\Debug\Toolbar\Collectors\EventsCollector::class,
        \BlitzPHP\Schild\Collectors\Auth::class,
        \BlitzPHP\Database\Collectors\DatabaseCollector::class,
    ],

    /**
     * Si défini sur false, les données var des vues ne seront pas collectées. Utile pour
     * éviter une utilisation élevée de la mémoire lorsqu'il y a beaucoup de données transmises à la vue.
     *
     * @var bool
     */
    'collect_var_data' => true,

    /**
     * Définit une limite sur le nombre de requêtes passées qui sont stockées,
     * aidant à conserver l'espace de fichier utilisé pour les stocker. Vous pouvez le régler sur
     * 0 (zéro) pour ne pas avoir d'historique stocké, ou -1 pour un historique illimité.
     *
     * @var int
     */
    'max_history' => 20,

    /**
     * Le chemin d'accès complet aux vues utilisées par la barre d'outils.
     *
     * @var string
     */
    'view_path' => SYST_PATH . 'Debug' . DS . 'Toolbar' . DS . 'Views',

    /**
     * Si le collecteur de base de données est activé, il enregistrera chaque requête que le
     * le système génère afin qu'ils puissent être affichés sur la chronologie de la barre d'outils
     * et dans le journal des requêtes. Cela peut entraîner des problèmes de mémoire dans certains cas
     * avec des centaines de requêtes.
     *
     * `max_queries` définit le nombre maximum de requêtes qui seront stockées.
     *
     * @var int
     */
    'max_queries' => 100,

    /**
     * Specifie si on doit afficher la debugbar ou pas
     * Quelque soit la valeur défini, la debugbar ne sera pas affichée en production
     * 
     * @var bool 
     */
    'show_debugbar' => true,
];
