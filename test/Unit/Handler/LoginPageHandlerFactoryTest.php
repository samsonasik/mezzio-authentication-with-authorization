<?php

declare(strict_types=1);

namespace AppTest\Unit\Handler;

use App\Handler\LoginPageHandler;
use App\Handler\LoginPageHandlerFactory;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function get_class;

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

        $factory = new LoginPageHandlerFactory();

        $LoginPage = $factory($this->container->reveal(), null, get_class($this->container->reveal()));

        $this->assertInstanceOf(LoginPageHandler::class, $LoginPage);
    }
}
