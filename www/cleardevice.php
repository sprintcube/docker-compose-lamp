<?php 
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $device_id = (int) $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM devices WHERE id = ?");
    $stmt->bind_param('i', $device_id);
    $stmt->execute();
    header("Location: index.php");
}

echo '<a href="index.php">Back to Home</a>';

$conn->close();
?>
