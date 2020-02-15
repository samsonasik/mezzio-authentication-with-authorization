<?php

declare(strict_types=1);

namespace AppTest\Unit\View\Helper;

use App\View\Helper\GetRole;
use App\View\Helper\IsGranted;
use Mezzio\Authorization\Acl\LaminasAclFactory;
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

        $this->helper = new IsGranted($this->acl, $this->getRole->reveal());
    }

    public function provideGrantData()
    {
        return [
            ['guest', 'login.form', true],
            ['guest', 'home.view', false],
            ['user', 'home.view', true],
            ['user', 'admin.view', false],
            ['admin', 'admin.view', true],
        ];
    }

    /** @dataProvider provideGrantData */
    public function testIsGranted($role, $resource, $isGranted)
    {
        $this->getRole->__invoke()->willReturn($role);
        $this->assertEquals($isGranted, ($this->helper)($resource));
    }
}
