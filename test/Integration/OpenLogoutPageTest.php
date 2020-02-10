<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\TestCase;

use function session_start;

class OpenLogoutPageTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        $this->app = AppFactory::create();
    }

    public function testOpenLogoutPageAsAuserRedirectToLoginPage()
    {
        $uri           = new Uri('/logout');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
    }

    public function tearDown(): void
    {
        session_start();
        $_SESSION[UserInterface::class] = [
            'username' => 'samsonasik',
            'roles'    => [
                'user',
            ],
        ];
    }
}
