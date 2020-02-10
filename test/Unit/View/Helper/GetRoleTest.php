<?php

declare(strict_types=1);

namespace AppTest\Unit\View\Helper;

use App\View\Helper\GetRole;
use PHPUnit\Framework\TestCase;
use Mezzio\Authentication\UserInterface;

class GetRoleTest extends TestCase
{
    private $helper;

    protected function setUp(): void
    {
        $this->helper = new GetRole();
    }

    public function testGetRoleUser()
    {
        $_SESSION[UserInterface::class] = [
            'username' => 'samsonasik',
            'roles' => [
                'user',
            ],
        ];

        $this->assertEquals('user', ($this->helper)());
    }
}
