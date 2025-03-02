<?php
// login_process.php processes the login request. need some input sanitization that im not gonna do right now, but will do later.
session_start();
require 'login2.php'; // this is like the login.php that does the database stuff, but like different cus it prepares some stuff

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // fetch user from database	// this is what the login2.php is for. i didn't feel like changing code around (also some real reasons)
    $stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {	// btw this doesn't use plain text passwords so a script kitty doesn't immediately steal everyones passwords when they "hack" this.
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        // goes to a place // change to go to index.php or something // could also make go to like nothing so it doesn't put the index.php in the address bar
        header('Location: /index.php');
        exit;
    } else {
        header('Location: /loginerror.html');
    }
}
?>
