<?php
require_once '../login.php';
require_once '../db/loans-functions.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $conn->real_escape_string($_POST['booking_id']);
    loan_device($conn, $booking_id);
};