<?php

declare(strict_types=1);

namespace AppTest\Unit\View\Helper;

use App\View\Helper\GetRole;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\TestCase;

use function session_start;

class GetRoleTest extends TestCase
{
    private $helper;

    protected function setUp(): void
    {
        $this->helper = new GetRole();
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetRoleUser()
    {
        session_start();

        $_SESSION[UserInterface::class] = [
            'username' => 'samsonasik',
            'roles'    => [
                'user',
            ],
        ];

        $this->assertEquals('user', ($this->helper)());
    }
}
