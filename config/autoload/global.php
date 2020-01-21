<?php
// config/autoload/global.php

return [
    // ...
    'mezzio-authorization-acl' => [
        'roles' => [
            'guest' => [],
            'user'  => ['guest'],
            'admin' => ['user'],
        ],
        'resources' => [
            'home',
            'admin',
            'login',
            'logout',
        ],
        'allow' => [
            'guest' => [
                'login',
            ],
            'user'  => [
                'logout',
                'home',
            ],
            'admin' => [
                'admin',
            ],
        ],
    ],
    // ...
];