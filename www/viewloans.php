<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

echo '<h1>Loan Management</h1>';
enum LoanQueryType
{
    case Overdue;
    case Active;
    case Returned;
}
/**
 * Usage example on the admin side
 * ```php
 * get_loans($conn, LoanQueryType::Active);
 * ```
 * Usage example on the user side
 * ```php
 * get_loans($conn, LoanQueryType::Active, $current_user_id);
 * ```
 * Here we supply `current_user_id` to filter results by
 * 
 */
function get_loans($conn, LoanQueryType $view = LoanQueryType::Active)
{
    $query = "";
    $result = "";
    switch ($view) {
        case LoanQueryType::Returned:
            $result .= "<h2>Returned Loans</h2>";
            $query = "SELECT * FROM loan WHERE returned = 1";
            break;
        case LoanQueryType::Overdue:
            $result .= "<h2>Overdue Loans</h2>";
            $query = "SELECT * FROM loan WHERE loan_end < CURDATE() AND returned = 0";
            break;
        default:
        case LoanQueryType::Active:
            $result .= "<h2>Active Loans</h2>";
            $query = "SELECT * FROM loan WHERE loan_end >= CURDATE() AND returned = 0";
            break;
    }

    $query_result = $conn->query($query);
    while ($row = $query_result->fetch_assoc()) {
        if ($view == LoanQueryType::Returned) {
            $result .= "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Returned</div>";
        } else {
            $result .= "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Due: {$row['loan_end']} 
                <a href='returnloan.php?id={$row['id']}'>Return</a>
            </div>";
        }
    }
    return $result;
}

// Overdue loans
echo get_loans($conn, LoanQueryType::Overdue);
// echo '<h2>Overdue Loans</h2>';
// $query = "SELECT * FROM loan WHERE loan_end < CURDATE() AND returned = 0";
// $result = $conn->query($query);
// while ($row = $result->fetch_assoc()) {
//     echo "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Due: {$row['loan_end']} 
//         <a href='returnloan.php?id={$row['id']}'>Return</a>
//     </div>";
// }

// Active loans
echo get_loans($conn, LoanQueryType::Active);
// echo '<h2>Active Loans</h2>';
// $query = "SELECT * FROM loan WHERE loan_end >= CURDATE() AND returned = 0";
// $result = $conn->query($query);
// while ($row = $result->fetch_assoc()) {
//     echo "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Due: {$row['loan_end']} 
//         <a href='returnloan.php?id={$row['id']}'>Return</a>
//     </div>";
// }

// Returned loans
echo get_loans($conn, LoanQueryType::Returned);
// echo '<h2>Returned Loans</h2>';
// $query = "SELECT * FROM loan WHERE returned = 1";
// $result = $conn->query($query);
// while ($row = $result->fetch_assoc()) {
//     echo "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Returned</div>";
// }

// echo '<a href="index.php">Back to Home</a>';

// $conn->close();
