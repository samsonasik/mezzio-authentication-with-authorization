<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class LoginPageHandlerFactory
{
    public function __invoke(ContainerInterface $container): MiddlewareInterface
    {
        $template          = $container->get(TemplateRendererInterface::class);
        $rememberMeSeconds = $container->get('config')['authentication']['remember-me-seconds'];

        return new LoginPageHandler($template, $rememberMeSeconds);
    }
}
