<?php

declare(strict_types=1);

namespace AppTest\Unit\View\Helper;

use App\View\Helper\GetRole;
use App\View\Helper\IsGranted;
use Mezzio\Authorization\Acl\LaminasAclFactory;
use Mezzio\LaminasView\UrlHelper;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class IsGrantedTest extends TestCase
{
    private $helper;

    protected function setUp(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'mezzio-authorization-acl' => [
                'roles'     => [
                    'guest' => [],
                    'user'  => ['guest'],
                    'admin' => ['user'],
                ],
                'resources' => [
                    'api.ping.view',
                    'home.view',
                    'admin.view',
                    'login.form',
                    'logout.access',
                ],
                'allow'     => [
                    'guest' => [
                        'login.form',
                        'api.ping.view',
                    ],
                    'user'  => [
                        'logout.access',
                        'home.view',
                    ],
                    'admin' => [
                        'admin.view',
                    ],
                ],
            ],
        ]);

        $this->acl     = (new LaminasAclFactory())($container->reveal());
        $this->getRole = $this->prophesize(GetRole::class);
        $this->url     = $this->prophesize(UrlHelper::class);

        $this->helper = new IsGranted($this->acl, $this->getRole->reveal(), $this->url->reveal());
    }

    public function provideGrantData(): array
    {
        return [
            'guest allowed to access login.form resource'    => ['guest', 'login.form', true],
            'guest not allowed to access home.view resource' => ['guest', 'home.view', false],
            'user allowed to access home.view resource'      => ['user', 'home.view', true],
            'user not allowed to access admin.view resource' => ['user', 'admin.view', false],
            'admin allowed to access admin.view resource'    => ['admin', 'admin.view', true],
        ];
    }

    /** @dataProvider provideGrantData */
    public function testIsGranted(string $role, string $resource, bool $isGranted)
    {
        $this->url->__invoke($resource)->willReturn('/' . $resource);
        $this->getRole->__invoke()->willReturn($role);
        $this->assertEquals($isGranted, ($this->helper)($resource));
    }
}
