<?php
require_once '../login.php';
require_once '../utils.php';
require_once '../db/loans-functions.php';
session_start();

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$notification_id = $_GET['id'];
$username = get_user_name();
if (!is_allowed_user_role([ROLE_USER, ROLE_ADMIN, ROLE_SUPER_ADMIN])) {
    header("Location: /403.html");
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($notification_id)) {
    dismiss_notification($conn, $username, $notification_id);
    header("Location: /profile-page.php");
}
