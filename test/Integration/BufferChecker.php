<?php

declare(strict_types=1);

namespace AppTest\Integration;

use function ob_end_flush;
use function ob_start;

final class BufferChecker
{
    public static function check()
    {
        ob_end_flush();
        ob_start();
    }
}
