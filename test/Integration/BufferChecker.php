<?php

declare(strict_types=1);

namespace AppTest\Integration;

use function ob_end_flush;
use function ob_get_level;
use function ob_start;

final class BufferChecker
{
    public static function check()
    {
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        ob_start();
    }
}
