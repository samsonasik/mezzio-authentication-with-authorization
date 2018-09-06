<?php
// src/App/Handler/LoginPageHandler.php
declare(strict_types=1);

namespace App\Handler;

use App\Form\LoginForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Csrf\CsrfMiddleware;
use Zend\Expressive\Flash\FlashMessageMiddleware;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;

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
                return new RedirectResponse('/login');
            }
        }

        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $token   = $guard->generateToken();

        return new HtmlResponse(
            $this->template->render('app::login-page', [
                'form'  => $loginForm,
                'token' => $token,
            ])
        );
    }
}