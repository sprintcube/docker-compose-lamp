<?php 
require_once 'login.php';
require_once './db/loans-functions.php';
// require_once './page-components/loan-management.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_sn = (int) $_GET['sn'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'], $_POST['loan_start'], $_POST['loan_end'])) {
    $teacher_id = $conn->real_escape_string($_POST['teacher_id']);
    $loan_start = $conn->real_escape_string($_POST['loan_start']);
    $loan_end = $conn->real_escape_string($_POST['loan_end']);

    $err = create_device_booking($conn, $device_sn, $loan_start, $loan_end, $teacher_id);
    if ($err) {
        print_r($err);
    } else {
        header("Location: index.php");
    }
}