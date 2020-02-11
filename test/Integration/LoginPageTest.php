<?php

declare(strict_types=1);

namespace AppTest\Integration;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\TestCase;

use function preg_match;

class LoginPageTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        $this->app = AppFactory::create();
    }

    public function testOpenLoginPageAsAguestGot200OK()
    {
        $uri           = new Uri('/login');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testOpenLoginPageAndSubmitLoginRedirect303Prg()
    {
        $uri           = new Uri('/login');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $body     = (string) $response->getBody();

        preg_match('/(?<=name="csrf" value=")(.{32})/', $body, $matches);
        $formData = [
            'username' => 'samsonasik',
            'password' => '123456',
            'csrf'     => $matches[0],
        ];

        $serverRequest = $serverRequest->withMethod('POST');
        $serverRequest = $serverRequest->withParsedBody($formData);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(303, $response->getStatusCode());
    }

    public function testOpenLoginPageHasValidPostDataSessionRedirectToHomePage()
    {
        $uri           = new Uri('/login');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $body     = (string) $response->getBody();

        preg_match('/(?<=name="csrf" value=")(.{32})/', $body, $matches);
        $sessionData           = [
            'username' => 'samsonasik',
            'password' => '123456',
            'csrf'     => $matches[0],
        ];
        $_SESSION['post_data'] = $sessionData;

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $response->getHeaderLine('Location'));
    }

    public function testOpenLoginPageHasInValidPostDataSessionRedirectBackToLoginPage()
    {
        $uri           = new Uri('/login');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $body     = (string) $response->getBody();

        preg_match('/(?<=name="csrf" value=")(.{32})/', $body, $matches);
        $sessionData           = [
            'username' => 'samsonasik',
            'password' => '1234567',
            'csrf'     => $matches[0],
        ];
        $_SESSION['post_data'] = $sessionData;

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaderLine('Location'));
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
}
