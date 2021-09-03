<?php

declare(strict_types=1);

namespace AppTest\Unit\Form;

use App\Form\LoginForm;
use Mezzio\Csrf\SessionCsrfGuard;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

use function rand;

class LoginFormTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy */
    private $sessionCsrfGuard;
    /** @var LoginForm */
    private $form;

    protected function setUp(): void
    {
        $this->sessionCsrfGuard = $this->prophesize(SessionCsrfGuard::class);
        $this->form             = new LoginForm($this->sessionCsrfGuard->reveal());
    }

    public function testHasElements(): void
    {
        $this->assertTrue($this->form->has('username'));
        $this->assertTrue($this->form->has('password'));
        $this->assertTrue($this->form->has('rememberme'));
        $this->assertTrue($this->form->has('csrf'));
        $this->assertTrue($this->form->has('Login'));
    }

    public function testHasInputFilters(): void
    {
        $this->assertIsArray($this->form->getInputFilterSpecification());
    }

    /**
     * @return array<string, array<array<string, string|int>|bool>>
     */
    public function provideValidateData(): array
    {
        return [
            'valid data'       => [
                [
                    'username'   => 'samsonasik',
                    'password'   => '123456',
                    'rememberme' => rand(0, 1),
                    'csrf'       => 's3cr3t',
                ],
                true,
            ],
            'invalid username' => [
                [
                    'username'   => '',
                    'password'   => '123456',
                    'rememberme' => rand(0, 1),
                    'csrf'       => 's3cr3t',
                ],
                false,
            ],
            'invalid password' => [
                [
                    'username'   => 'samsonasik',
                    'password'   => '',
                    'rememberme' => rand(0, 1),
                    'csrf'       => 's3cr3t',
                ],
                false,
            ],
            'empty csrf'       => [
                [
                    'username'   => 'samsonasik',
                    'password'   => '123456',
                    'rememberme' => rand(0, 1),
                    'csrf'       => '',
                ],
                false,
            ],
        ];
    }

    /**
     * @dataProvider provideValidateData
     * @param array<string, string>|array<string, int> $data
     */
    public function testValidate(array $data, bool $isValid): void
    {
        $this->sessionCsrfGuard->validateToken($data['csrf'])->willReturn(true);
        $this->form->setData($data);

        $this->assertEquals($isValid, $this->form->isValid());
    }
}
