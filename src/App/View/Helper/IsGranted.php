<?php

declare(strict_types=1);

namespace App\View\Helper;

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Uri;
use Laminas\View\Helper\AbstractHelper;
use Mezzio\Authorization\Acl\LaminasAcl;
use Mezzio\LaminasView\UrlHelper;
use Mezzio\Router\LaminasRouter;
use Mezzio\Router\RouteResult;

class IsGranted extends AbstractHelper
{
    /** @var LaminasAcl */
    private $acl;
    /** @var GetRole */
    private $getRole;
    /** @var UrlHelper */
    private $url;
    /** @var LaminasRouter */
    private $router;

    public function __construct(LaminasAcl $acl, GetRole $getRole, UrlHelper $url, LaminasRouter $router)
    {
        $this->acl     = $acl;
        $this->getRole = $getRole;
        $this->url     = $url;
        $this->router  = $router;
    }

    public function __invoke(string $resource, array $routeParams = [], array $queryParams = []): bool
    {
        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withUri(new Uri(($this->url)($resource, $routeParams, $queryParams)));

        $request = $request->withAttribute(
            RouteResult::class,
            $this->router->match($request)
        );

        return $this->acl->isGranted(($this->getRole)(), $request);
    }
}
