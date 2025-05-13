<?php
require_once '../login.php';
require_once '../utils.php';
require_once '../db/loans-functions.php';
session_start();

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$booking_id = $_GET['id'];
if (!is_allowed_user_role([ROLE_USER])) {
    header("Location: /403.html");
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($booking_id)) {
    cancel_booking($conn, $booking_id);
    header("Location: /index.php");
}
