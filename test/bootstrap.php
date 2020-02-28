<?php

declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'vendor/autoload.php';

$ciDbEngine = getenv('CI_DB_ENGINE');
if ($ciDbEngine) {
    $config = (include 'config/autoload/local.php')['authentication']['pdo'];
    try {
        $connection = new PDO(
            $config['dsn'],
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );

        $sql       = file_get_contents(__DIR__ . '/Fixture/' . $ciDbEngine . '.sql');
        $statement = $connection->prepare($sql);
        $statement->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
