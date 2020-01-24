<?php
// src/App/Handler/LoginPageHandler.php
declare(strict_types=1);

namespace App\Handler;

use App\Form\LoginForm;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginPageHandler implements MiddlewareInterface
{
    private $template;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->template  = $template;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $guard     = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
        $loginForm = new LoginForm($guard);

        $prg = $request->getParsedBody();
        if ($prg) {
            $loginForm->setData($prg);
            if ($loginForm->isValid()) {
                $response = $handler->handle($request);

                $flashMessages = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
                if ($response->getStatusCode() !== 302) {
                    $flashMessages->flash('message', 'You are succesfully authenticated');
                    return new RedirectResponse('/');
                }

                $flashMessages->flash('message', 'Login Failure, please try again');
                return $response;
            }
        }

        $token   = $guard->generateToken();
        return new HtmlResponse(
            $this->template->render('app::login-page', [
                'form'  => $loginForm,
                'token' => $token,
            ])
        );
    }
}