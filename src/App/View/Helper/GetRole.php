<?php

declare(strict_types=1);

namespace App\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\Session;

class GetRole extends AbstractHelper
{
    use SessionTrait;

    public function __invoke(): string
    {
        $this->checkIsStarted();

        $session     = new Session($_SESSION);
        $hasLoggedIn = $session->has(UserInterface::class);

        if (! $hasLoggedIn) {
            return 'guest';
        }

        return $session->get(UserInterface::class)['roles'][0];
    }
}
