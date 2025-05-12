<?php //this one clears everything from every table
// i didn't write this cus i was tired (i made chatgpt do this)

require_once 'login.php';

// Connect to the database
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

// List of all tables to be cleared
$tables = ['devices', 'loan']; // Add other table names as needed

foreach ($tables as $table) {
    $stmt = $conn->prepare("DELETE FROM $table");
    if ($stmt->execute()) {
        echo "Cleared all data from table: $table<br>";
    } else {
        echo "Failed to clear data from table: $table. Error: " . $stmt->error . "<br>";
    }
}

// Optional: Reset AUTO_INCREMENT for each table (uncomment if needed) 
// i uncommented this
foreach ($tables as $table) {
    $stmt = $conn->prepare("ALTER TABLE $table AUTO_INCREMENT = 1");
    if ($stmt->execute()) {
        echo "Reset AUTO_INCREMENT for table: $table<br>";
    } else {
        echo "Failed to reset AUTO_INCREMENT for table: $table. Error: " . $stmt->error . "<br>";
    }
}

$conn->close();
?>

<a href="index.php">Back to Home</a>
