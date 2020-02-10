<?php

declare(strict_types=1);

namespace AppTest\Integration;

use function ob_end_clean;
use function ob_start;

final class BufferChecker
{
    public static function check()
    {
        ob_end_clean();
        ob_start();
    }
}
