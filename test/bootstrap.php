<?php

declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include __DIR__ . '/vendor/autoload.php';

$ciDbEngine = getenv('CI_DB_ENGINE');
if ($ciDbEngine) {
    $config = (include __DIR__ . '/config/autoload/local.php')['authentication']['pdo'];
    try {
        $connection = new PDO(
            $config['dsn'],
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );

        // https://stackoverflow.com/questions/6346674/pdo-support-for-multiple-queries-pdo-mysql-pdo-mysqlnd
        preg_match_all(
            "#('(\\\\.|.)*?'|[^;])+#s",
            file_get_contents(__DIR__ . '/Fixture/' . $ciDbEngine . '.sql'),
            $matches
        );

        foreach ($matches[0] as $sql) {
            $statement = $connection->prepare($sql);
            $statement->execute();
        }
    } catch (PDOException $pdoException) {
        echo $pdoException->getMessage();
    }
}
