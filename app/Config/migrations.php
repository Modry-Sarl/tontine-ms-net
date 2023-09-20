<?php

/**
 * ------------------------------------------------- -------------------------
 * Configuration des migrations
 * ------------------------------------------------- -------------------------
 *
 * Ce fichier vous permet de definir comment les migrations de votre base de données vont s'effectuer
 */

return [
    /**
     * --------------------------------------------------------------------------
     * Activation des migrations
     * --------------------------------------------------------------------------
     *
     * Les migrations sont actif par defaut.
     *
     * Vous devez activer les migrations chaque fois que vous avez l'intention de faire une migration de schéma
     * et désactivez-le lorsque vous avez terminé.
     */
    'enabled' => true,

    /**
     * --------------------------------------------------------------------------
     * Table de migrations
     * --------------------------------------------------------------------------
     *
     * Il s'agit du nom de la table qui stockera l'état actuel des migrations.
     * Lorsque les migrations s'exécutent, elles stockent dans une table de base de données quelle migration
     * niveau auquel se trouve le système.
     */
    'table' => 'migrations',

    /**
     * --------------------------------------------------------------------------
     * Format du timestamp
     * --------------------------------------------------------------------------
     *
     * C'est le format qui sera utilisé lors de la création de nouvelles migrations
     * en utilisant la commande CLI:
     *   > php klinge make:migration
     *
     * Note: si vous définissez un format non pris en charge, l'exécuteur de migration ne trouvera pas vos fichiers de migration.
     *
     * Formats pris en charge:
     * - YmdHis_
     * - Y-m-d-His_
     * - Y_m_d_His_
     */
    'timestampFormat' => 'Y-m-d-His_',
];
