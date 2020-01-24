<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthorizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $redirect = $container->get('config')['authentication']['redirect'];
        return new AuthorizationMiddleware($redirect);
    }
}