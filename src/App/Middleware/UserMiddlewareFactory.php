<?php

declare(strict_types=1);

namespace App\Middleware;

use Mezzio\Authentication\UserInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class UserMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): MiddlewareInterface
    {
        $user     = $container->get(UserInterface::class);
        $redirect = $container->get('config')['authentication']['redirect'];

        return new UserMiddleware($user, $redirect);
    }
}
