<?php

declare(strict_types=1);

namespace App\Middleware;

use Mezzio\Authentication\UserInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthorizationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): MiddlewareInterface
    {
        $redirect = $container->get('config')['authentication']['redirect'];
        $user     = $container->get(UserInterface::class);

        return new AuthorizationMiddleware(
            $user,
            $redirect
        );
    }
}
