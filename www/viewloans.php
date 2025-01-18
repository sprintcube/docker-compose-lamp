<?php 
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

echo '<h1>Loan Management</h1>';

// Overdue loans
echo '<h2>Overdue Loans</h2>';
$query = "SELECT * FROM loan WHERE loan_end < CURDATE() AND returned = 0";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    echo "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Due: {$row['loan_end']} 
        <a href='returnloan.php?id={$row['id']}'>Return</a>
    </div>";
}

// Active loans
echo '<h2>Active Loans</h2>';
$query = "SELECT * FROM loan WHERE loan_end >= CURDATE() AND returned = 0";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    echo "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Due: {$row['loan_end']} 
        <a href='returnloan.php?id={$row['id']}'>Return</a>
    </div>";
}

// Returned loans
echo '<h2>Returned Loans</h2>';
$query = "SELECT * FROM loan WHERE returned = 1";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    echo "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Returned</div>";
}

echo '<a href="index.php">Back to Home</a>';

$conn->close();
?>
