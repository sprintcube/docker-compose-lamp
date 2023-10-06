<?php

$DBuser = 'root';
$DBpass = $_ENV['MYSQL_ROOT_PASSWORD'];
$pdo = null;

try{
    $database = 'mysql:host=database:3306';
    $pdo = new PDO($database, $DBuser, $DBpass);
    echo "Success: A proper connection to MySQL was made! The docker database is great.<br>" . PHP_EOL;
} catch(PDOException $e) {
    echo "Error: Unable to connect to MySQL. Error:\n $e<br>" . PHP_EOL;
}

$pdo = null;

try{
    $database = 'pgsql:host=postgres;port=5432;dbname=' . $_ENV['POSTGRES_DB'];
    $pdo = new PDO($database, $_ENV['POSTGRES_USER'], $_ENV['POSTGRES_PASSWORD']);
    echo "Success: A proper connection to PostgreSQL was made! The docker database is great.<br>" . PHP_EOL;
} catch(PDOException $e) {
    echo "Error: Unable to connect to PostgreSQL. Error:\n $e<br>" . PHP_EOL;
}

$pdo = null;
