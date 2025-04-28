<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_id = (int) $_GET['id'];
$query = "SELECT * FROM laite WHERE id = $device_id";
$result = $conn->query($query);
if (!$result) die("Database access failed");

$row = $result->fetch_assoc();

echo "<h1>Device Details</h1>";
echo "Name: {$row['name']}<br>";
echo "Serial Number: {$row['sn']}<br>";
echo "Category: {$row['category']}<br>";

echo "<a href='service/edit-device.php?id={$row['id']}'>Edit</a> | ";
echo "<a href='loandevice.php?id={$row['id']}'>Loan</a>";
echo '<br><a href="index.php">Back to Home</a>';

$conn->close();
?>


