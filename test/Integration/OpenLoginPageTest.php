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

class OpenLoginPageTest extends TestCase
{
    private $app;

    protected function setUp()
    {
        $this->app = AppFactory::create();
    }

    public function testOpenLoginPageAsAuserRedirectToHomePage()
    {
        $sessionData                    = [
            'username' => 'samsonasik',
            'roles'    => [
                'user',
            ],
        ];
        $_SESSION[UserInterface::class] = $sessionData;

        $uri           = new Uri('/login');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $response->getHeaderLine('Location'));
    }

    public function tearDown()
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            session_destroy();
        }
    }
}
