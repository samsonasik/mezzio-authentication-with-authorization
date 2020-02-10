<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;

class OpenHomePageTest extends TestCase
{
    private $app;

    protected function setUp()
    {
        $this->app = AppFactory::create();
    }

    public function testAsAguestRedirectToLoginPage()
    {
        $uri           = new Uri('/');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        echo (string) $response->getBody();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }
}
