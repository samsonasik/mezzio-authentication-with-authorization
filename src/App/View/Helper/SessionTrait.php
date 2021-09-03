<?php

declare(strict_types=1);

namespace App\View\Helper;

use function session_status;

use const PHP_SESSION_ACTIVE;

trait SessionTrait
{
    /**
     * @return mixed[]|array<string, mixed>
     */
    private function getSession(): array
    {
        return session_status() === PHP_SESSION_ACTIVE
            ? $_SESSION
            : [];
    }
}
