<?php

declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'vendor/autoload.php';

if (getenv('CI') === 'Yes') {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=postgres', null, null);
    $pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS users(username character varying(255) PRIMARY KEY NOT NULL, password text NOT NULL, role character varying(255) NOT NULL DEFAULT 'user');
CREATE EXTENSION IF NOT EXISTS pgcrypto;
INSERT INTO users(username, password, role) VALUES('samsonasik', crypt('123456', gen_salt('bf')), 'user');
INSERT INTO users(username, password, role) VALUES('admin', crypt('123456', gen_salt('bf')), 'admin');
SQL
    ) or die(print_r($pdo->errorInfo(), true));
}
