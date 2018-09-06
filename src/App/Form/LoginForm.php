<?php
// src/App/Form/LoginForm.php
declare(strict_types=1);

namespace App\Form;

use Zend\Expressive\Csrf\SessionCsrfGuard;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class LoginForm extends Form implements InputFilterProviderInterface
{
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
            'type' => Text::class,
            'name' => 'username',
            'options' => [
                'label' => 'Username',
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
            'type'  => Hidden::class,
            'name'  => 'csrf',
        ]);

        $this->add([
            'name' => 'Login',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Login',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            [
                'name' => 'username',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                  ],
            ],

            [
                'name' => 'password',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            [
                'name' => 'csrf',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'callback',
                        'options' => [
                            'callback' => function ($value) {
                                return $this->guard->validateToken($value);
                            },
                            'messages' => [
                                'callbackValue' => 'The form submitted did not originate from the expected site'
                            ],
                        ],
                    ]
                ],
            ],
        ];
    }
}