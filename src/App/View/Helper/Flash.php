<?php

// src/App/View\Helper\Flash.php

declare(strict_types=1);

namespace App\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Mezzio\Flash\FlashMessages;
use Mezzio\Session\Session;

use function session_start;
use function session_status;

use const PHP_SESSION_NONE;

class Flash extends AbstractHelper
{
    public function __invoke(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return FlashMessages::createFromSession(
            new Session($_SESSION)
        )->getFlashes();
    }
}
