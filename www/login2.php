<?php
// login2.php
require 'login.php';	// gets the variables from login.php so you don't have to change two files every time.

// i might remove this cus it it's just another way to do something else, but my other project used this so it would require some rewriting if i would make the things that use this use login.php instead. // if i feel like rewriting small parts of many files.


// makes thing into thing
$dsn = "mysql:host=$hn;dbname=$db";

try {
    $pdo = new PDO($dsn, $un, $pw);		// some stuff (PHP Data Objects)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // error handling stuff. honestly i forgor what this is and where i copied this originally.
} catch (PDOException $e) { // error handling? in my terrible code?
    echo 'Connection failed: ' . $e->getMessage();
}
