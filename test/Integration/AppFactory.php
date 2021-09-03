<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Mezzio\Application;
use Mezzio\MiddlewareFactory;

final class AppFactory
{
    public static function create(): Application
    {
        $container = require __DIR__ . '/config/container.php';

        $app     = $container->get(Application::class);
        $factory = $container->get(MiddlewareFactory::class);

        (require __DIR__ . '/config/pipeline.php')($app, $factory, $container);
        (require __DIR__ . '/config/routes.php')($app, $factory, $container);

        unset($_SESSION);
        return $app;
    }
}
