<?php

declare(strict_types=1);

namespace AppTest\Unit\Form;

use App\Form\LoginForm;
use Mezzio\Csrf\SessionCsrfGuard;
use PHPUnit\Framework\TestCase;

class LoginFormTest extends TestCase
{
    private $sessionCsrfGuard;
    private $form;

    protected function setUp(): void
    {
        $this->sessionCsrfGuard = $this->prophesize(SessionCsrfGuard::class);
        $this->form             = new LoginForm($this->sessionCsrfGuard->reveal());
    }

    public function testHasElements()
    {
        $this->assertTrue($this->form->has('username'));
        $this->assertTrue($this->form->has('password'));
        $this->assertTrue($this->form->has('csrf'));
        $this->assertTrue($this->form->has('Login'));
    }

    public function testHasInputFilters()
    {
        $this->assertIsArray($this->form->getInputFilterSpecification());
    }

    public function provideValidateData(): array
    {
        return [
            'valid data'       => [['username' => 'samsonasik', 'password' => '123456', 'csrf' => 's3cr3t'], true],
            'invalid username' => [['username' => '', 'password' => '123456', 'csrf' => 's3cr3t'], false],
            'invalid password' => [['username' => 'samsonasik', 'password' => '', 'csrf' => 's3cr3t'], false],
            'empty csrf'       => [['username' => 'samsonasik', 'password' => '123456', 'csrf' => ''], false],
        ];
    }

    /**
     * @dataProvider provideValidateData
     */
    public function testValidate(array $data, bool $isValid)
    {
        $this->sessionCsrfGuard->validateToken($data['csrf'])->willReturn(true);
        $this->form->setData($data);

        $this->assertEquals($isValid, $this->form->isValid());
    }
}
