<?php

declare(strict_types=1);

namespace AppTest\Unit\Middleware;

use App\Middleware\UserMiddleware;
use App\Middleware\UserMiddlewareFactory;
use Mezzio\Authentication\DefaultUserFactory;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

class UserMiddlewareFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testFactory(): void
    {
        $this->container
            ->get(UserInterface::class)
            ->willReturn(new DefaultUserFactory());

        $this->container
            ->get('config')
            ->willReturn(
                [
                    'authentication' => [
                        'redirect' => '/login',
                    ],
                ]
            );

        $factory    = new UserMiddlewareFactory();
        $middleware = $factory($this->container->reveal());
        $this->assertInstanceOf(UserMiddleware::class, $middleware);
    }
}
