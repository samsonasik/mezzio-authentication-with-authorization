<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use PHPUnit\Framework\TestCase;

class OpenHomePageTest extends TestCase
{
    private $app;

    protected function setUp()
    {
        $container = require 'config/container.php';

        $app     = $container->get(Application::class);
        $factory = $container->get(MiddlewareFactory::class);

        (require 'config/pipeline.php')($app, $factory, $container);
        (require 'config/routes.php')($app, $factory, $container);

        $this->app = $app;
    }

    public function testAsAguestRedirectToLoginPage()
    {
        $uri           = new Uri('/');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }
}
