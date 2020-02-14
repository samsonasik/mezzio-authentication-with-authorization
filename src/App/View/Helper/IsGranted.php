<?php

// src/App/View\Helper\IsGranted.php

declare(strict_types=1);

namespace App\View\Helper;

use Laminas\Diactoros\Response;
use Laminas\View\Helper\AbstractHelper;
use Mezzio\Authorization\Acl\LaminasAcl;
use Mezzio\Router\Route;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IsGranted extends AbstractHelper
{
    private $acl;
    private $request;

    public function __construct(LaminasAcl $acl)
    {
        $this->acl = $acl;
    }

    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function __invoke(string $resource): bool
    {
        $request = $this->request;
        $request = $request->withAttribute(
            RouteResult::class,
            RouteResult::fromRoute(new Route(
                '/' . $resource,
                new class implements MiddlewareInterface {
                    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                    {
                        return new Response();
                    }
                },
                ['GET'],
                $resource
            ))
        );

        return $this->acl->isGranted($this->view->getRole(), $request);
    }
}
