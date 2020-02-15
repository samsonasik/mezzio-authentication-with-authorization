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
        $acl     = $container->get(LaminasAcl::class);
        $getRole = $container->get(HelperPluginManager::class)
                             ->get('getRole');

        return new IsGranted($acl, $getRole);
    }
}
