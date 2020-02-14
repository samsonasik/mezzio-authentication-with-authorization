<?php

// src/App/Middleware/PrgMiddleware.php
declare(strict_types=1);

namespace App\Middleware;

use App\View\Helper\IsGranted;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IsGrantedMiddleware implements MiddlewareInterface
{
    private $helper;

    public function __construct(IsGranted $helper)
    {
        $this->helper = $helper;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->helper->setRequest($request);
        return $handler->handle($request);
    }
}
