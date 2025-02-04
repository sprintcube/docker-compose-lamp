<?php 
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_sn = (int) $_GET['sn'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'], $_POST['loan_start'], $_POST['loan_end'])) {
    $teacher_id = $conn->real_escape_string($_POST['teacher_id']);
    $loan_start = $conn->real_escape_string($_POST['loan_start']);
    $loan_end = $conn->real_escape_string($_POST['loan_end']);

    $query = "INSERT INTO loan (device_sn, teacher_id, loan_start, loan_end) 
              VALUES ('$device_sn', '$teacher_id', '$loan_start', '$loan_end')";
    if (!$conn->query($query)) echo "Error: " . $conn->error;
    header("Location: index.php");
}

echo <<<_END
<form action="loandevice.php?id=$device_sn" method="post">
    Teacher ID: <input type="text" name="teacher_id" maxlength="6" required><br>
    Loan Start: <input type="date" name="loan_start" required><br>
    Loan End: <input type="date" name="loan_end" required><br>
    <input type="submit" value="Loan Device">
</form>

<script>
    const loanStartInput = document.querySelector('input[name="loan_start"]');
    const loanEndInput = document.querySelector('input[name="loan_end"]');

    loanStartInput.addEventListener('change', () => {
        loanEndInput.min = loanStartInput.value;
    });
</script>

<a href="index.php">Back to Home</a>
_END;

$conn->close();
?>
