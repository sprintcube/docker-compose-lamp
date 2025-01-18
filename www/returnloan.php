<?php 
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$loan_id = (int) $_GET['id'];

$query = "UPDATE loan SET returned = 1 WHERE id = $loan_id";
$conn->query($query);

header("Location: viewloans.php");
$conn->close();
?>
