<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\TestCase;

use function session_start;

class AdminPageTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        $this->app = AppFactory::create();
    }

    public function testOpenAdminPageAsAguestRedirectToLoginPage()
    {
        $uri           = new Uri('/admin');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function testOpenAdminPageAsAnAdminGot200Ok()
    {
        session_start();

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

    public function testOpenAdminPageAsAuserGot403Forbidden()
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
}
