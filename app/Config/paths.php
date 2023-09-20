<?php

/**
 * ------------------------------------------------- -------------------------
 * Configuration des chemins des differents repertoires de l'application
 * ------------------------------------------------- -------------------------
 *
 * Ce fichier vous permet de definir le chemins où se trouveront les élements nécessaires à l'éxecution du programme
 * 
 * Contient les chemins utilisés par le système pour localiser les répertoires principaux, l'application, le système, etc.
 *
 * Les modifier vous permet de restructurer votre application, de partager un dossier système entre plusieurs applications, et bien plus encore.
 *
 * Tous les chemins sont relatifs au dossier racine du projet.
 */

return [
    /**
     * ------------------------------------------------- -------------------------
     * NOM DU DOSSIER D'APPLICATION
     * ------------------------------------------------- -------------------------
     *
     * Repertoire où sont stockés les fichiers de votre application
     * 
     * Si vous souhaitez que ce contrôleur frontal utilise un dossier « app » différent de celui par défaut, vous pouvez définir son nom ici.
     * Le dossier peut également être renommé ou déplacé n'importe où sur votre serveur.
     * Si vous le faites, utilisez un chemin de serveur complet.
     *
    * @var string
     */
    'app' => __DIR__ . '/../',
    
    /**
     * ------------------------------------------------- -------------------------
     * NOM DU DOSSIER DE STOCKAGE
     * ---------------------------------------------------------------
     *
     * Repertoire où sont stockés les fichiers statique générés par l'application (cache, log, migrations...)
     * 
     * Cette variable doit contenir le nom de votre répertoire "storage".
     * Le répertoire de stockage vous permet de regrouper tous les répertoires nécessitant une autorisation 
     * d'écriture dans un seul endroit qui peut être rangé pour une sécurité maximale, 
     * en les gardant hors de l'application et/ou des répertoires système.
     */
    'storage' => __DIR__ . '/../../storage/',
    
    /**
     * ------------------------------------------------- -------------------------
     * NOM DU DOSSIER DES TELECHARGEMENTS
     * ---------------------------------------------------------------
     *
     * Repertoire où sont stockés les fichiers téléchargés par les utilisateurs de l'application (images, videos, pdf...)
     */
    'upload' => __DIR__ . '/../../uploads/',
    
    /**
     * ------------------------------------------------- -------------------------
     * NOM DU DOSSIER DE COMPOSER
     * ---------------------------------------------------------------
     *
     * Repertoire votre dossier de dependances installées via composer "vendor".
     */
    'composer' => __DIR__ . '/../../vendor/',
];
