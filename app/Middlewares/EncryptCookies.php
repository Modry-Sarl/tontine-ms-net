<?php

namespace App\Middlewares;

use BlitzPHP\Middlewares\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Les noms des cookies qui ne doivent pas être cryptés.
     *
     * @var list<string>
     */
    protected array $except = [
        
    ];
}
