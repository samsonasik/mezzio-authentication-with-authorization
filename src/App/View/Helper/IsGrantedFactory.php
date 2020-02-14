<?php

declare(strict_types=1);

namespace App\View\Helper;

use Mezzio\Authorization\Acl\LaminasAcl;
use Psr\Container\ContainerInterface;

class IsGrantedFactory
{
    public function __invoke(ContainerInterface $container): IsGranted
    {
        $acl = $container->get(LaminasAcl::class);
        return new IsGranted($acl);
    }
}
