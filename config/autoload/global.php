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
            'api.ping',
            'home',
            'admin',
            'login',
            'logout',
        ],
        'allow' => [
            'guest' => [
                'login',
                'api.ping',
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