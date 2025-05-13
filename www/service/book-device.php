<?php 
require_once "../login.php";
require_once "../db/loans-functions.php";
require_once "../utils.php";
session_start();
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

if (!is_allowed_user_role([ROLE_USER, ROLE_ADMIN, ROLE_SUPER_ADMIN])) {
    header("Location: /403.html");
}

$device_sn = (string) $_POST['device_sn'];
$loan_start= (string) $_POST['loan_start'];
$loan_end = (string) $_POST['loan_end'];
$teacher_id = (string) $_POST['teacher_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset(
    $device_sn,
    $loan_start,
    $loan_end,
    $teacher_id)) {
        create_device_booking($conn, $device_sn, $loan_start, $loan_end, $teacher_id);
        header("Location: /index.php");
    }