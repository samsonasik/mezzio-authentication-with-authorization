<?php

declare(strict_types=1);

namespace App;

use App\Handler\AdminPageHandler;
use App\Handler\AdminPageHandlerFactory;
use App\Handler\HomePageHandler;
use App\Handler\HomePageHandlerFactory;
use App\Handler\LoginPageHandler;
use App\Handler\LoginPageHandlerFactory;
use App\Handler\LogoutHandler;
use App\Handler\PingHandler;
use App\Middleware\PrgMiddleware;
use App\Middleware\UserMiddleware;
use App\Middleware\UserMiddlewareFactory;
use App\View\Helper\Flash;
use App\View\Helper\GetRole;
use App\View\Helper\IsGrantedFactory;
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
                    'flash'   => Flash::class,
                    'getRole' => GetRole::class,
                ],
                'factories'  => [
                    'isGranted' => IsGrantedFactory::class,
                ],
            ],
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return mixed[]
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                PingHandler::class   => PingHandler::class,
                LogoutHandler::class => LogoutHandler::class,
            ],
            'factories'  => [
                AdminPageHandler::class => AdminPageHandlerFactory::class,
                LoginPageHandler::class => LoginPageHandlerFactory::class,
                HomePageHandler::class  => HomePageHandlerFactory::class,
                PrgMiddleware::class    => InvokableFactory::class,
                UserMiddleware::class   => UserMiddlewareFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array<string, array<string, array<string>>>
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
