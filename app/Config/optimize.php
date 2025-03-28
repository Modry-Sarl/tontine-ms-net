<?php

/**
 * -------------------------------
 * Configuration de l'optimisation
 * -------------------------------
 *
 * @immutable
 */

return [
    /**
     * @see 
     */
    'config_cache_enabled' => on_prod(),
    
    /**
     * @see 
     */
    'locator_cache_enabled' => on_prod(),
];
