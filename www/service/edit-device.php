<?php 
require_once '../login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_sn = (int) $_GET['sn'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['category'])) {
    $stmt = $conn->prepare("UPDATE laite SET name = ?, category = ? WHERE sn = ?");
    $stmt->bind_param('sss', $_POST['name'], $_POST['category'], $device_sn);
    $stmt->execute();
    header("Location: /index.php");
}
