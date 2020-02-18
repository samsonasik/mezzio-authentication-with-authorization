<?php

declare(strict_types=1);

namespace AppTest\Unit\Handler;

use App\Handler\LoginPageHandler;
use App\Handler\LoginPageHandlerFactory;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class LoginPageHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testFactoryWithTemplate()
    {
        $this->container
            ->get(TemplateRendererInterface::class)
            ->willReturn($this->prophesize(TemplateRendererInterface::class));

        $this->container
            ->get('config')
            ->willReturn([
                'authentication' => [
                    'remember-me-seconds' => 86400,
                ],
            ]);

        $factory = new LoginPageHandlerFactory();

        $loginPage = $factory($this->container->reveal());

        $this->assertInstanceOf(LoginPageHandler::class, $loginPage);
    }
}
