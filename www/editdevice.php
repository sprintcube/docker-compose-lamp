<?php 
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_sn = (int) $_GET['sn'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['category'])) {
    $stmt = $conn->prepare("UPDATE laite SET name = ?, category = ? WHERE sn = ?");
    $stmt->bind_param('sss', $_POST['name'], $_POST['category'], $device_sn);
    $stmt->execute();
    header("Location: index.php");
}

$query = "SELECT * FROM laite WHERE sn = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('varchar(32)', $device_sn);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo <<<_END
<form action="editdevice.php?sn=$device_sn" method="post">
    Name: <input type="text" name="name" value="{$row['name']}" required><br>
    Category: <input type="text" name="category" value="{$row['category']}" required><br>
    <input type="submit" value="Update">
</form>

<a href="index.php">Back to Home</a>
_END;

$conn->close();
?>
