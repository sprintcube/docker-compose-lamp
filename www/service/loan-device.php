<?php
require_once '../login.php';
require_once '../db/loans-functions.php';

$EXCEPTION_DUPLICATE_RECORD = "Duplicate entry";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $conn->real_escape_string($_POST['booking_id']);
    try {
        loan_device($conn, $booking_id);
        header("Location: /index.php");
    } catch (Exception $e) {
        if (strstr($e, $EXCEPTION_DUPLICATE_RECORD)) {
            header("Location: /errors/duplicate-record.html");
        } else {
            echo $e->getMessage();
            header("Location: /errors/500.html");
        }
    }
};