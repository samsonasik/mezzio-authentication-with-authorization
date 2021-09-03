<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Application;
use PHPUnit\Framework\TestCase;

class PingPageTest extends TestCase
{
    /** @var Application */
    private $app;

    protected function setUp(): void
    {
        $this->app = AppFactory::create();
    }

    public function testOpenPingPageAsAguestGot200OK(): void
    {
        $uri           = new Uri('/api/ping');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
