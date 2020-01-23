<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Mezzio\Handler\NotFoundHandler;

class AuthorizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $notFoundHandler   = $container->get(NotFoundHandler::class);
        $redirect          = $container->get('config')['authentication']['redirect'];

        return new AuthorizationMiddleware($notFoundHandler, $redirect);
    }
}