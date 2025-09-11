<?php

require_once '../login.php';
require_once '../utils.php';
require_once '../db/user-functions.php';

session_start();

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

if (!is_allowed_user_role([ROLE_SUPER_ADMIN]) || !isset($_POST["username"])) {
    header("Location: /errors/403.html");
    exit;
}

$username = $_POST["username"];
if (isset($_POST['demote'])) {
    demote_user($conn, $username);
} else {
    promote_user($conn, $username);
}
header("Location: /users_management.php");