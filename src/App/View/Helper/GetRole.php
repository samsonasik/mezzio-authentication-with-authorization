<?php

namespace App\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\Session;

class GetRole extends AbstractHelper
{
    public function __invoke() : string
    {
        if (\session_status() == \PHP_SESSION_NONE){
            \session_start();
        }

        $session     = new Session($_SESSION);
        $hasLoggedIn = $session->has(UserInterface::class);

        if (! $hasLoggedIn) {
            return 'guest';
        }

        return $session->get(UserInterface::class)['roles'][0];
    }
}