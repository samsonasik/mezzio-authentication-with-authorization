<?php
declare(strict_types = 1);
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LegacyApplicationMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * This Middleware is a pathway to a legacy app and should not be triggered for valid mezzio routes
         *
         * Middleware is to serve as a connector between Mezzio front-end and a legacy app.
         *
         * Legacy app is loaded when no valid mezzio route is found to satisfy a request
         * and before mezzio triggers a 404 error)
         *
         * This middleware skeleton will supply its own mechanism to load a legacy app
         * where the legacy app will use its own routing structure
         */
        die("Error: legacy app middleware should not be triggered for a valid mezzio route");
    }
}
