<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class IsGrantedMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): MiddlewareInterface
    {
        $helper = $container->get(HelperPluginManager::class)
                            ->get('isGranted');

        return new IsGrantedMiddleware($helper);
    }
}
