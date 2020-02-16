<?php

declare(strict_types=1);

namespace App\View\Helper;

use Laminas\View\HelperPluginManager;
use Mezzio\Authorization\Acl\LaminasAcl;
use Psr\Container\ContainerInterface;

class IsGrantedFactory
{
    public function __invoke(ContainerInterface $container): IsGranted
    {
        $acl                 = $container->get(LaminasAcl::class);
        $helperPluginManager = $container->get(HelperPluginManager::class);
        $getRole             = $helperPluginManager->get('getRole');
        $url                 = $helperPluginManager->get('url');

        return new IsGranted($acl, $getRole, $url);
    }
}
