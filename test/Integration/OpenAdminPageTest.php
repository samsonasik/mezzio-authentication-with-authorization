<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\TestCase;

class OpenAdminPageTest extends TestCase
{
    private $app;

    protected function setUp()
    {
        $this->app = AppFactory::create();
    }

    public function testAsAguestRedirectToLoginPage()
    {
        $uri           = new Uri('/admin');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    /** @runInSeparateProcess */
    public function testAsAuserGot403()
    {
        $sessionData                    = [
            'username' => 'samsonasik',
            'roles'    => [
                'user',
            ],
        ];
        $_SESSION[UserInterface::class] = $sessionData;

        $uri           = new Uri('/admin');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @runInSeparateProcess */
    public function testAsAnAdminGot200()
    {
        $sessionData                    = [
            'username' => 'admin',
            'roles'    => [
                'admin',
            ],
        ];
        $_SESSION[UserInterface::class] = $sessionData;

        $uri           = new Uri('/admin');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
