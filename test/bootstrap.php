<?php

declare(strict_types=1);

namespace AppTest;

use AppTest\Integration\AppFactory;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PDO;

use function error_reporting;
use function ini_set;
use function preg_match;
use function session_start;
use function sprintf;

use const E_ALL;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'vendor/autoload.php';

$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=postgres', null, null);
//$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=mezzio;user=developer;password=123456', null, null);
//$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=123456', null, null);

$sql  = sprintf(
    "SELECT %s FROM %s WHERE %s = :identity",
    'password',
    'users',
    'username'
);
$stmt = $pdo->prepare($sql);

exit;

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

$response = $app->handle($serverRequest);
$app->run();

exit;
