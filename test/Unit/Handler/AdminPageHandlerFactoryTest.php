<?php

declare(strict_types=1);

namespace AppTest\Unit\Handler;

use App\Handler\AdminPageHandler;
use App\Handler\AdminPageHandlerFactory;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function get_class;

class AdminPageHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testFactoryWithTemplate2()
    {
        $this->container
            ->get(TemplateRendererInterface::class)
            ->willReturn($this->prophesize(TemplateRendererInterface::class));

        $factory = new AdminPageHandlerFactory();

        $adminPage = $factory($this->container->reveal(), null, get_class($this->container->reveal()));

        $this->assertInstanceOf(AdminPageHandler::class, $adminPage);
    }
}
