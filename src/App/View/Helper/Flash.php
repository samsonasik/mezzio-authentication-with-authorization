<?php

// src/App/View\Helper\Flash.php

declare(strict_types=1);

namespace App\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Mezzio\Flash\FlashMessages;
use Mezzio\Session\Session;

class Flash extends AbstractHelper
{
    use SessionTrait;

    public function __invoke(): array
    {
        $this->checkIsStarted();

        return FlashMessages::createFromSession(
            new Session($_SESSION)
        )->getFlashes();
    }
}
