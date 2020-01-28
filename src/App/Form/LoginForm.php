<?php

// src/App/Form/LoginForm.php
declare(strict_types=1);

namespace App\Form;

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Mezzio\Csrf\SessionCsrfGuard;

class LoginForm extends Form implements InputFilterProviderInterface
{
    /** @var SessionCsrfGuard */
    private $guard;

    public function __construct(SessionCsrfGuard $guard)
    {
        parent::__construct('login-form');

        $this->guard = $guard;
        $this->init();
    }

    public function init()
    {
        $this->add([
            'type'    => Text::class,
            'name'    => 'username',
            'options' => [
                'label' => 'Username',
            ],
        ]);

        $this->add([
            'type'    => Password::class,
            'name'    => 'password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
            'type' => Hidden::class,
            'name' => 'csrf',
        ]);

        $this->add([
            'name'       => 'Login',
            'type'       => 'submit',
            'attributes' => [
                'value' => 'Login',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            [
                'name'     => 'username',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            [
                'name'     => 'password',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            [
                'name'       => 'csrf',
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'callback',
                        'options' => [
                            'callback' => function (string $value) {
                                return $this->guard->validateToken($value);
                            },
                            'messages' => [
                                'callbackValue' => 'The form submitted did not originate from the expected site',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
