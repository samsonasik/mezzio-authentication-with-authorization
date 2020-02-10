<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\TestCase;

use function session_destroy;
use function session_status;

use const PHP_SESSION_ACTIVE;

class OpenAdminPageTest extends TestCase
{
    private $app;

    protected function setUp()
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

    public function testOpenAdminPageAsAuserGot403()
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

    public function tearDown()
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            session_destroy();
        }
    }
}
