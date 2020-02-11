<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Mezzio\Application;
use Mezzio\MiddlewareFactory;

use function AppTest\mockNativeSession;

final class AppFactory
{
    public static function create(): Application
    {
        $container = require 'config/container.php';

        $app     = $container->get(Application::class);
        $factory = $container->get(MiddlewareFactory::class);

        (require 'config/pipeline.php')($app, $factory, $container);
        (require 'config/routes.php')($app, $factory, $container);

        mockNativeSession();
        $_SESSION = [];

        return $app;
    }
}
