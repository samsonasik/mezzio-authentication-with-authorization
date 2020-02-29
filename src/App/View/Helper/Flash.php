<?php

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
        return FlashMessages::createFromSession(
            new Session($this->getSession())
        )->getFlashes();
    }
}
