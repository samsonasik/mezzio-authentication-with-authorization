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

class AuthorizationMiddleware implements MiddlewareInterface
{
    private $user;
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
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        // No Session data
        if (! $session->has(UserInterface::class)) {
            $user  = '';
            $roles = ['guest'];

            $request = $request->withAttribute(
                UserInterface::class,
                ($this->user)($user, $roles)
            );

            $response = $handler->handle($request);
            if ($request->getUri()->getPath() === $this->redirect || $response->getStatusCode() !== 403) {
                return $response;
            }

            return new RedirectResponse($this->redirect);
        }

        // at /login page, redirect to authenticated page
        if ($request->getUri()->getPath() === $this->redirect) {
            return new RedirectResponse('/');
        }

        // define roles from DB
        $sessionData = $session->get(UserInterface::class);
        $request     = $request->withAttribute(
            UserInterface::class,
            ($this->user)($sessionData['username'], $sessionData['roles'])
        );

        return $handler->handle($request);
    }
}
