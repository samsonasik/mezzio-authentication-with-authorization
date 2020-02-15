<?php

declare(strict_types=1);

namespace App;

use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'view_helpers' => [
                'invokables' => [
                    'flash'   => View\Helper\Flash::class,
                    'getRole' => View\Helper\GetRole::class,
                ],
                'factories'  => [
                    'isGranted' => View\Helper\IsGrantedFactory::class,
                ],
            ],
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class   => Handler\PingHandler::class,
                Handler\LogoutHandler::class => Handler\LogoutHandler::class,
            ],
            'factories'  => [
                Handler\AdminPageHandler::class           => Handler\AdminPageHandlerFactory::class,
                Handler\LoginPageHandler::class           => Handler\LoginPageHandlerFactory::class,
                Handler\HomePageHandler::class            => Handler\HomePageHandlerFactory::class,
                Middleware\PrgMiddleware::class           => InvokableFactory::class,
                Middleware\AuthorizationMiddleware::class => Middleware\AuthorizationMiddlewareFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }
}
