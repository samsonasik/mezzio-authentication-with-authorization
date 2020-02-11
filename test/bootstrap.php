<?php

declare(strict_types=1);

namespace AppTest;

use AppTest\Integration\AppFactory;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

use function error_reporting;
use function ini_set;
use function preg_match;
use function session_start;
use function var_dump;

use const E_ALL;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'vendor/autoload.php';

session_start();

$app = AppFactory::create();

$uri           = new Uri('/login');
$serverRequest = new ServerRequest([], [], $uri);

$response = $app->handle($serverRequest);
$body     = (string) $response->getBody();

preg_match('/(?<=name="csrf" value=")(.{32})/', $body, $matches);
$sessionData           = [
    'username' => 'samsonasik',
    'password' => '123456',
    'csrf'     => $matches[0],
];
$_SESSION['post_data'] = $sessionData;

var_dump($sessionData);

$response = $app->handle($serverRequest);
$app->run();

exit;
