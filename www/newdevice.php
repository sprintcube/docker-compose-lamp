<?php 
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['sn'], $_POST['category'])) {
    $stmt = $conn->prepare("INSERT INTO laite (name, sn, category) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $_POST['name'], $_POST['sn'], $_POST['category']);
    if (!$stmt->execute()) echo "Error: " . $stmt->error;
    header('Location: index.php');
}

echo <<<_END
<form action="newdevice.php" method="post">
    Name: <input type="text" name="name" required><br>
    Serial Number: <input type="text" name="sn" required><br>
    Category: <input type="text" name="category" required><br>
    <input type="submit" value="Add Device">
</form>

<a href="index.php">Back to Home</a>
_END;

$conn->close();
?>
