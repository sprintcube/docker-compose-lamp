<?php 
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_id = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['sn'], $_POST['category'])) {
    $stmt = $conn->prepare("UPDATE laite SET name = ?, sn = ?, category = ? WHERE id = ?");
    $stmt->bind_param('sssi', $_POST['name'], $_POST['sn'], $_POST['category'], $device_id);
    $stmt->execute();
    header("Location: viewdevice.php?id=$device_id");
}

$query = "SELECT * FROM laite WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $device_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo <<<_END
<form action="editdevice.php?id=$device_id" method="post">
    Name: <input type="text" name="name" value="{$row['name']}" required><br>
    Serial Number: <input type="text" name="sn" value="{$row['sn']}" required><br>
    Category: <input type="text" name="category" value="{$row['category']}" required><br>
    <input type="submit" value="Update">
</form>

<a href="index.php">Back to Home</a>
_END;

$conn->close();
?>
