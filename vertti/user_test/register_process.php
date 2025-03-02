<?php
// register_process.php is the thing that actually processes the register reaquest. 
// there should be some actual safety stuff here so nobody just "hacks" this. i'll do it later.
// so i need to add input sanitization

// this code is also just copied from one of my projects and updated to fit the current needs (and also to hide stupid mistakes)
require 'login2.php'; // this is like the login.php that does the database stuff, but like different cus it prepares some stuff

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];	// might change later
    $password = $_POST['password'];

    // this thing actually hashes the passwords so it's like a bit harder to get the actual passwords if some "hacker" finds out the terrible ssh password.
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // does stuff (very informative)	// this is what the login2.php is for. i didn't feel like changing code around (also some real reasons)
    $stmt = $pdo->prepare('INSERT INTO users (name, email, username, password_hash) VALUES (?, ?, ?, ?)');
    
    // this thing
    if ($stmt->execute([$name, $email, $username, $password_hash])) {
        echo 'Registration successful!!! :)';
		header('Location: /index.php');
    } else {
        echo 'Error during registration. please contact the admins or something. dont spam tho';
    }
}
?>
