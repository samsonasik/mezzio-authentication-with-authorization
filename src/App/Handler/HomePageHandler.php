<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomePageHandler implements RequestHandlerInterface
{
    private $template;

    public function __construct(Template\TemplateRendererInterface $template)
    {
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [];

        $data['containerName'] = 'Zend Servicemanager';
        $data['containerDocs'] = 'https://docs.zendframework.com/zend-servicemanager/';

        $data['routerName'] = 'Zend Router';
        $data['routerDocs'] = 'https://docs.zendframework.com/zend-router/';

        $data['templateName'] = 'Zend View';
        $data['templateDocs'] = 'https://docs.zendframework.com/zend-view/';

        return new HtmlResponse($this->template->render('app::home-page', $data));
    }
}
