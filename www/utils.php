<?php
//enum LoanQueryType
// {
//   case Overdue;
//    case Active;
//   case Returned;
// }
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
function get_loans($conn, $view = 'ACTIVE')
{
    $query = "";
    $result = "";
    switch ($view) {
        case "RETURNED":
            $result .= "<h2>Returned Loans</h2>";
            $query = "SELECT * FROM loan WHERE returned = 1";
            break;
        case "OVERDUE":
            $result .= "<h2>Overdue Loans</h2>";
            $query = "SELECT * FROM loan WHERE loan_end < CURDATE() AND returned = 0";
            break;
        default:
        case "ACTIVE":
            $result .= "<h2>Active Loans</h2>";
            $query = "SELECT * FROM loan WHERE loan_end >= CURDATE() AND returned = 0";
            break;
    }

    $query_result = $conn->query($query);
    while ($row = $query_result->fetch_assoc()) {
        $teacher_id = $row['teacher_id'];
        $device_sn = $row['device_sn'];

        if ($view == "RETURNED") {
            $result .= "
            <div class='card my-2'>
                <div class='card-body'>
                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item'><strong>Teacher ID:</strong> $teacher_id</li>
                        <li class='list-group-item'><strong>Device SN:</strong> $device_sn</li>
                    </ul>
                </div>
            </div>";
            // $result .= "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Returned</div>";
        } else {
            $loan_end = $row['loan_end'];
            $id = $row['id'];

            $result .= "<div class='card my-2'>
            <div class='card-body'>
                <ul class='list-group list-group-flush'>
                    <li class='list-group-item'><strong>Teacher ID:</strong> $teacher_id</li>
                    <li class='list-group-item'><strong>Device SN:</strong> $device_sn</li>
                    <li class='list-group-item'></strong> $loan_end</li>
                </ul>
                <a class='btn btn-primary' href='returnloan.php?id={$id}'>Return</a>
            </div>
        </div>";
            // <div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Due: {$row['loan_end']} 
        }
    }
    return $result;
}
