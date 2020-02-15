<?php

declare(strict_types=1);

use App\Middleware\PrgMiddleware;
use Mezzio\Application;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', [
        Mezzio\Authentication\AuthenticationMiddleware::class,
        App\Handler\HomePageHandler::class,
    ], 'home.view');

    $app->route('/admin', [
        Zend\Expressive\Authentication\AuthenticationMiddleware::class,
        App\Handler\AdminPageHandler::class,
    ], ['GET'], 'admin.view');

    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping.view');

    $app->route('/login', [
        //csrf handling
        CsrfMiddleware::class,
        // prg handling
        PrgMiddleware::class,
        // the login page
        App\Handler\LoginPageHandler::class,
        // authentication handling
        Mezzio\Authentication\AuthenticationMiddleware::class,
    ], ['GET', 'POST'], 'login.form');

    $app->get('/logout', App\Handler\LogoutHandler::class, 'logout.access');
};
