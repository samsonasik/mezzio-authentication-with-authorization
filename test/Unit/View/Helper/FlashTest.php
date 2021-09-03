<?php

declare(strict_types=1);

namespace AppTest\Unit\View\Helper;

use App\View\Helper\Flash;
use Mezzio\Flash\FlashMessagesInterface;
use PHPUnit\Framework\TestCase;

use function session_start;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class FlashTest extends TestCase
{
    /** @var Flash */
    private $helper;

    protected function setUp(): void
    {
        $this->helper = new Flash();
    }

    public function testGetFlashMessagesSessionIsNotActive(): void
    {
        $this->assertEquals([], ($this->helper)());
    }

    public function testGetFlashMessagesSessionIsActive(): void
    {
        session_start();

        $_SESSION[FlashMessagesInterface::class . '::FLASH_NEXT'] = [
            'message' => [
                'value' => 'Test',
                'hops'  => 1,
            ],
        ];

        $this->assertEquals(['message' => 'Test'], ($this->helper)());
    }
}
