<?php

namespace App\Middlewares;

use BlitzPHP\Middlewares\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indique si le cookie XSRF-TOKEN doit être défini dans la réponse.
     */
    protected bool $addHttpCookie = true;

    /**
     * Les URI qui doivent être exclus de la vérification CSRF.
     */
    protected array $except = [];
}
