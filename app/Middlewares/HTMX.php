<?php

namespace App\Middlewares;

use BlitzPHP\Middlewares\BaseMiddleware;
use BlitzPHP\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class HTMX extends BaseMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        View::share([
            'isHTMXRequest' => $request->hasHeader('Hx-Request'),
            'isHTMXBoosted' => $request->hasHeader('Hx-Boosted'),
        ]);

        $request = $request
            ->withAttribute('htmx', $request->hasHeader('Hx-Request'))
            ->withAttribute('htmx-boosted', $request->hasHeader('Hx-Boosted'))
            ->withAttribute('htmx-trigger', !$request->hasHeader('Hx-Boosted') && !$request->hasHeader('Hx-Request'));

        return $handler->handle($request);
    }
}
