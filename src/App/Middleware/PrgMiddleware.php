<?php

// src/App/Middleware/PrgMiddleware.php
declare(strict_types=1);

namespace App\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Session\SessionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PrgMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($request->getMethod() === 'POST') {
            $session->set('post_data', $request->getParsedBody());
            return new RedirectResponse($request->getUri(), 303);
        }

        if ($session->has('post_data')) {
            $post = $session->get('post_data');
            $session->unset('post_data');

            $request = $request->withMethod('POST');
            $request = $request->withParsedBody($post);
        }

        return $handler->handle($request);
    }
}
