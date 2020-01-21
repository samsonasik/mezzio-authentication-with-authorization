<?php

declare(strict_types=1);

use App\Middleware\PrgMiddleware;
use Mezzio\Application;
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
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', [
        // prg handling
        PrgMiddleware::class,

        \Mezzio\Authentication\AuthenticationMiddleware::class,
        App\Handler\HomePageHandler::class,
    ], 'home');

    $app->route('/admin', [
        \Zend\Expressive\Authentication\AuthenticationMiddleware::class,
        App\Handler\AdminPageHandler::class,
    ], ['GET'], 'admin');

    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
	$app->route('/login', [
	    App\Handler\LoginPageHandler::class,
	    // for authentication next handling
        \Mezzio\Authentication\AuthenticationMiddleware::class,
        // prg handling
        PrgMiddleware::class,
    ], ['GET', 'POST'],'login');
    $app->get('/logout', App\Handler\LogoutHandler::class, 'logout');
};
