<?php
// src/App/Handler/LoginPageFactory.php
declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class LoginPageFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $template  = $container->get(TemplateRendererInterface::class);
        return new LoginPageHandler($template);
    }
}