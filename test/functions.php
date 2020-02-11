<?php

declare(strict_types=1);

namespace AppTest;

use phpmock\MockBuilder;

use const PHP_SESSION_NONE;

function mockNativeSession(): void
{
    static $mock;

    if ($mock) {
        $mock->disable();
    } else {
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName("headers_sent")
                ->setFunction(
                    function () {
                        return false;
                    }
                );
        $builder->setNamespace(__NAMESPACE__)
                ->setName("session_status")
                ->setFunction(
                    function () {
                        return PHP_SESSION_NONE;
                    }
                );

        $mock = $builder->build();
    }

    $mock->enable();
    $_SESSION = [];
}
