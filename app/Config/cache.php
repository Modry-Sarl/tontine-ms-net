<?php

/**
 * ------------------------------------------------- -------------------------
 * Configuration du gestionnaire de cache
 * ------------------------------------------------- -------------------------
 *
 * Ce fichier vous permet de definir les parametres de gestion du cache de votre application
 */

return [
    /**
     * ------------------------------------------------- -------------------------
     * Gestionnaire principal
     * ------------------------------------------------- -------------------------
     *
     * Le nom du gestionnaire préféré qui doit être utilisé. Si pour une raison quelconque
     * il n'est pas disponible, le $backupHandler sera utilisé à sa place.
     *
     * @var string
     */
    'handler' => 'file',

    /**
     * ------------------------------------------------- -------------------------
     * Gestionnaire de relais
     * ------------------------------------------------- -------------------------
     *
     * Le nom du gestionnaire qui sera utilisé si le premier est
     * inaccessible. Souvent, 'fichier' est utilisé ici puisque le système de fichiers est
     * toujours disponible, même si ce n'est pas toujours pratique pour l'application.
     *
     * @var string
     */
    'fallback_handler' => 'dummy',

	/**
     * --------------------------------------------------------------------------
     * Cache Include Query String
     * --------------------------------------------------------------------------
     *
     * Whether to take the URL query string into consideration when generating
     * output cache files. Valid options are:
     *
     *    false = Disabled
     *    true  = Enabled, take all query parameters into account.
     *            Please be aware that this may result in numerous cache
     *            files generated for the same page over and over again.
     *    ['q'] = Enabled, but only take into account the specified list
     *            of query parameters.
     *
     * @var bool|string[]
     */
	'cache_query_string' => false,

    /**
     * --------------------------------------------------------------------------
     * Key Prefix
     * --------------------------------------------------------------------------
     *
     * This string is added to all cache item names to help avoid collisions
     * if you run multiple applications with the same cache engine.
	 * 
	 * @var	string
     */
	'prefix' => '',

    /**
     * --------------------------------------------------------------------------
     * Default TTL
     * --------------------------------------------------------------------------
     *
     * The default number of seconds to save items when none is specified.
     *
     * WARNING: This is not used by framework handlers where 60 seconds is
     * hard-coded, but may be useful to projects and modules. This will replace
     * the hard-coded value in a future release.
	 * 
	 * @var int
     */
	'ttl' => 60,

    /**
     * --------------------------------------------------------------------------
     * Reserved Characters
     * --------------------------------------------------------------------------
     *
     * A string of reserved characters that will not be allowed in keys or tags.
     * Strings that violate this restriction will cause handlers to throw.
     * Default: {}()/\@:
     *
     * NOTE: The default set is required for PSR-6 compliance.
	 * 
	 * @var string
     */
	'reserved_characters' => '{}()/\@:',

    /**
     * --------------------------------------------------------------------------
     * File settings
     * --------------------------------------------------------------------------
     * Your file storage preferences can be specified below, if you are using
     * the File driver.
     *
     * @var array<string, int|string|null>
     */
	'file' => [
        'path' => cache_path(),
        'mode' => 0640,
	],

    /**
     * -------------------------------------------------------------------------
     * Memcached settings
     * -------------------------------------------------------------------------
     * Your Memcached servers can be specified below, if you are using
     * the Memcached drivers.
     *
     * @var array<string, bool|int|string>
     */
	'memcached' => [
		'host' => '127.0.0.1',
		'port' => 11211,
	],

    /**
     * -------------------------------------------------------------------------
     * Redis settings
     * -------------------------------------------------------------------------
     * Your Redis server can be specified below, if you are using
     * the Redis or Predis drivers.
     *
     * @var array<string, array|int|string|null>
     */
	'redis' => [
		'host'     => '127.0.0.1',
		'password' => false,
		'port'     => 6379,
		'timeout'  => 0,
		'database' => 0,
	],

    /**
     * ------------------------------------------------- -------------------------
     * Gestionnaires de cache disponibles
     * ------------------------------------------------- -------------------------
     *
     * Il s'agit d'un tableau d'alias de moteur de cache et de noms de classe. Seuls les moteurs
     * qui sont répertoriés ici sont autorisés à être utilisés.
     *
     * @var array<string, string>
     */
    'valid_handlers' => [
        'apcu'      => \BlitzPHP\Cache\Handlers\Apcu::class,
        'array'     => \BlitzPHP\Cache\Handlers\ArrayHandler::class,
        'dummy'     => \BlitzPHP\Cache\Handlers\Dummy::class,
        'file'      => \BlitzPHP\Cache\Handlers\File::class,
        'memcached' => \BlitzPHP\Cache\Handlers\Memcached::class,
        'redis'     => \BlitzPHP\Cache\Handlers\RedisHandler::class,
        'wincache'  => \BlitzPHP\Cache\Handlers\Wincache::class,
    ],
];