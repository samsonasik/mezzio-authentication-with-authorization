<?php

declare(strict_types=1);

use AppTest\Integration\AppFactory;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'vendor/autoload.php';

if (getenv('CI') === 'Yes') {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=postgres', null, null);
    //$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=123456', null, null);
    //$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=123456', null, null);

    //echo file_get_contents('./data/postgresql.sql');
    $pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS users(username character varying(255) PRIMARY KEY NOT NULL, password text NOT NULL, role character varying(255) NOT NULL DEFAULT 'user');
CREATE EXTENSION IF NOT EXISTS pgcrypto;
INSERT INTO users(username, password, role) VALUES('samsonasik', crypt('123456', gen_salt('bf')), 'user');
INSERT INTO users(username, password, role) VALUES('admin', crypt('123456', gen_salt('bf')), 'admin');
SQL
    ) or die(print_r($pdo->errorInfo(), true));

    exit;
}

//$result = $stmt->fetchObject();

//var_dump($result);

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
