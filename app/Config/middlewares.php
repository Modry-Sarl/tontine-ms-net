<?php

use App\Middlewares\HTMX;
use BlitzPHP\Http\Middleware;
use BlitzPHP\Http\Request;
use BlitzPHP\Schild\Config\Registrar;

return function (Middleware &$middleware, Request $request) {
    $middleware->aliases(Registrar::middlewares());

    $middleware->add(HTMX::class);
    // $middleware->add(['body-parser', 'cors']);
};
