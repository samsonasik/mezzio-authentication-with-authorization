<?php

declare(strict_types=1);

use AppTest\Integration\AppFactory;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'vendor/autoload.php';

$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=postgres', null, null);
//$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=mezzio;user=developer;password=123456', null, null);
//$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=123456', null, null);

$sql  = "SELECT password FROM users WHERE username = 'samsonasik'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchObject();

var_dump($result);

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
