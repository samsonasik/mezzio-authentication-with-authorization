<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\LaminasView\LaminasViewRenderer;
use Mezzio\Plates\PlatesRenderer;
use Mezzio\Router;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Template;
use Mezzio\Twig\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomePageHandler implements RequestHandlerInterface
{
    private $containerName;

    private $router;

    private $template;

    public function __construct(
        Router\RouterInterface $router,
        Template\TemplateRendererInterface $template = null,
        string $containerName
    ) {
        $this->router        = $router;
        $this->template      = $template;
        $this->containerName = $containerName;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if (! $session->has(UserInterface::class)) {
            return new RedirectResponse('/login');
        }

        if (! $this->template) {
            return new JsonResponse([
                'welcome' => 'Congratulations! You have installed the zend-expressive skeleton application.',
                'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
            ]);
        }

        $data = [];

        switch ($this->containerName) {
            case 'Aura\Di\Container':
                $data['containerName'] = 'Aura.Di';
                $data['containerDocs'] = 'http://auraphp.com/packages/2.x/Di.html';
                break;
            case 'Pimple\Container':
                $data['containerName'] = 'Pimple';
                $data['containerDocs'] = 'https://pimple.symfony.com/';
                break;
            case 'Laminas\ServiceManager\ServiceManager':
                $data['containerName'] = 'Zend Servicemanager';
                $data['containerDocs'] = 'https://docs.zendframework.com/zend-servicemanager/';
                break;
            case 'Auryn\Injector':
                $data['containerName'] = 'Auryn';
                $data['containerDocs'] = 'https://github.com/rdlowrey/Auryn';
                break;
            case 'Symfony\Component\DependencyInjection\ContainerBuilder':
                $data['containerName'] = 'Symfony DI Container';
                $data['containerDocs'] = 'https://symfony.com/doc/current/service_container.html';
                break;
        }

        if ($this->router instanceof Router\AuraRouter) {
            $data['routerName'] = 'Aura.Router';
            $data['routerDocs'] = 'http://auraphp.com/packages/2.x/Router.html';
        } elseif ($this->router instanceof Router\FastRouteRouter) {
            $data['routerName'] = 'FastRoute';
            $data['routerDocs'] = 'https://github.com/nikic/FastRoute';
        } elseif ($this->router instanceof Router\LaminasRouter) {
            $data['routerName'] = 'Zend Router';
            $data['routerDocs'] = 'https://docs.zendframework.com/zend-router/';
        }

        if ($this->template instanceof PlatesRenderer) {
            $data['templateName'] = 'Plates';
            $data['templateDocs'] = 'http://platesphp.com/';
        } elseif ($this->template instanceof TwigRenderer) {
            $data['templateName'] = 'Twig';
            $data['templateDocs'] = 'http://twig.sensiolabs.org/documentation';
        } elseif ($this->template instanceof LaminasViewRenderer) {
            $data['templateName'] = 'Zend View';
            $data['templateDocs'] = 'https://docs.zendframework.com/zend-view/';
        }

        return new HtmlResponse($this->template->render('app::home-page', $data));
    }
}
