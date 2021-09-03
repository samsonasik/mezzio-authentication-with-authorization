<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function current;

class UserMiddleware implements MiddlewareInterface
{
    /** @var callable */
    private $user;
    /** @var string */
    private $redirect;

    public function __construct(callable $user, string $redirect)
    {
        $this->user     = $user;
        $this->redirect = $redirect;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $session     = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $sessionData = $session->get(UserInterface::class);

        $request  = $request->withAttribute(
            UserInterface::class,
            $user = ($this->user)($sessionData['username'] ?? '', $sessionData['roles'] ?? ['guest'])
        );

        $response      = $handler->handle($request);
        $isGuest       = current($user->getRoles()) === 'guest';
        $isAtLoginPage = $request->getUri()->getPath() === $this->redirect;

        if (! $isGuest && $isAtLoginPage) {
            return new RedirectResponse('/');
        }

        return $response;
    }
}
