<?php

$DBuser = 'root';
$DBpass = 'tiger';
$pdo = null;

try{
    $database = 'mysql:host=database:3306';
    $pdo = new PDO($database, $DBuser, $DBpass);
    echo "Looking good, php connect to mysql successfully.";    
} catch(PDOException $e) {
    echo "php connect to mysql failed with:\n $e";
}

$pdo = null;