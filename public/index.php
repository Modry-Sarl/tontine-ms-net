<?php

/**
 * This file is part of Blitz PHP framework.
 *
 * (c) 2022 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

define('DS', DIRECTORY_SEPARATOR);

// Charge la configuration de nos chemins de repertoires
// Cette ligne doit etre modifiee en fonction de votre structure
$paths_config_file = dirname(__DIR__) . '/app/Config/paths.php';

$paths = require_once $paths_config_file;

// Le chemin d'accès vers composer
if (empty($paths['composer']) || (!is_dir($paths['composer']) && !is_file($paths['composer']))) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Your composer autoload file does not appear to be set correctly. ';
    echo 'Please open the following file and correct this: "' . $paths_config_file . '"';
    exit(3); // EXIT_CONFIG
}

$paths['composer'] = strtr(rtrim($paths['composer'], '/\\'), '/\\', DS.DS);
if (is_dir($paths['composer'])) {
    $paths['composer'] .= DS . 'autoload.php';
}
if (!is_file($paths['composer'])) {
    $paths['composer'] = dirname(__DIR__) . DS . 'vendor' . DS . 'auoload.php';
}
if (!is_file($paths['composer'])) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Your composer autoload file does not appear to be set correctly. ';
    echo 'Please open the following file and correct this: "' . $paths_config_file . '"';
    exit(3); // EXIT_CONFIG
}

require_once $paths['composer'];

/**
 * Chemin d'acces du dossier "vendor"
 */
define('VENDOR_PATH', realpath(pathinfo($paths['composer'], PATHINFO_DIRNAME)) . DS);

/**
 * Chemin vers le framework
 */
define('SYST_PATH', VENDOR_PATH . 'blitz-php' . DS . 'framework' . DS . 'src' . DS);

/**
 * Chemin vers le repertoire publique
 */
define('WEBROOT', __DIR__  . DS);

/**
 * URL de base
 */
define('BASE_URL', trim(dirname($_SERVER['SCRIPT_NAME'], 2), '\\'));

/**
 * Chargement du fichier responsable du demarrage du systeme
 */
$bootstrap = require_once SYST_PATH . 'Initializer' . DS. 'bootstrap.php';

/**
 * Lancement de l'application
 * 
 * Maintenant que tout est ok, on peut exeecuter l'application
 */
$bootstrap(
    $paths, 
    $paths_config_file, 
    PHP_SAPI === 'cli' || defined('STDIN') || defined('KLINGED')
);
