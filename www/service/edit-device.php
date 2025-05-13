<?php 
error_reporting(E_ALL);
require_once '../login.php';
require_once '../db/devices-functions.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_sn = (int) $_GET['sn'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['category'])) {
    update_device($conn, $device_sn);

    if (isset($_FILES['image'])) {
        update_device_picture($device_sn, $_FILES['image']);
    }
    header("Location: /index.php");
}
