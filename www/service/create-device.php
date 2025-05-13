<?php 
error_reporting(E_ALL);
require_once '../login.php';
require_once '../db/devices-functions.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['sn'], $_POST['category'], $_POST['description'])) {
    $create_result = create_device(
        $conn,
        $_POST['name'],
        $_POST['sn'],
        $_POST['category'],
        $_POST['description'],
        $_FILES['image']);
    if (!$create_result) echo "Error: Could not create the device";
    header('Location: /index.php');
}