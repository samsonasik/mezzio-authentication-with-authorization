<?php

// src/App/View\Helper\IsGranted.php

declare(strict_types=1);

namespace App\View\Helper;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\View\Helper\AbstractHelper;
use Mezzio\Authorization\Acl\LaminasAcl;
use Mezzio\LaminasView\UrlHelper;
use Mezzio\Router\Route;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IsGranted extends AbstractHelper
{
    private $acl;
    private $getRole;
    private $url;

    public function __construct(LaminasAcl $acl, GetRole $getRole, UrlHelper $url)
    {
        $this->acl     = $acl;
        $this->getRole = $getRole;
        $this->url     = $url;
    }

    public function __invoke(string $resource): bool
    {
        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withAttribute(
            RouteResult::class,
            RouteResult::fromRoute(new Route(
                // somehow it needs manual __invoke instead of self called
                // when running composer test-coverage
                // with PHP 7.4.2 with Xdebug 2.9.2
                $this->url->__invoke($resource),
                // @codeCoverageIgnoreStart
                new class implements MiddlewareInterface {
                    public function process(
                        ServerRequestInterface $request,
                        RequestHandlerInterface $handler
                    ): ResponseInterface {
                        return new Response();
                    }
                },
                // @codeCoverageIgnoreEnd
                ['GET'],
                $resource
            ))
        );

        return $this->acl->isGranted(($this->getRole)(), $request);
    }
}
