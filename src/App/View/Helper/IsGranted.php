<?php

// src/App/View\Helper\IsGranted.php

declare(strict_types=1);

namespace App\View\Helper;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
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
    /** @var LaminasAcl */
    private $acl;

    /** @var GetRole */
    private $getRole;

    public function __construct(LaminasAcl $acl, GetRole $getRole)
    {
        $this->acl     = $acl;
        $this->getRole = $getRole;
    }

    public function __invoke(string $resource): bool
    {
        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withAttribute(
            RouteResult::class,
            RouteResult::fromRoute(new Route(
                '/' . $resource,
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
