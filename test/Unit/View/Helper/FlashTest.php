<?php

declare(strict_types=1);

namespace AppTest\Unit\View\Helper;

use App\View\Helper\Flash;
use PHPUnit\Framework\TestCase;

class FlashTest extends TestCase
{
    private $helper;

    protected function setUp(): void
    {
        $this->helper = new Flash();
    }

    public function testGetFlashMessages()
    {
        // flush existing data first that caused by integration tests
        ($this->helper)();

        $_SESSION['Mezzio\Flash\FlashMessagesInterface::FLASH_NEXT'] = [
            'message' => [
                'value' => 'Test',
                'hops'  => 1,
            ],
        ];

        $this->assertEquals(['message' => 'Test'], ($this->helper)());
    }
}
