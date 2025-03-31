<?php
/**
 * Only include this from root pages, or component intended for use on a root page.
 */
function get_header_loans_button() {
    ob_start();
    require './page-parts/header-view-loans-button.php';
    $result = ob_get_clean();
    return $result;
}

function get_loans($conn, $view = 'ACTIVE')
{
    $query = "";
    switch ($view) {
        case "RETURNED":
            $query = "SELECT * FROM loan WHERE returned = 1";
            break;
        case "OVERDUE":
            $query = "SELECT * FROM loan WHERE loan_end < CURDATE() AND returned = 0";
            break;
        default:
        case "ACTIVE":
            $query = "SELECT * FROM loan WHERE loan_end >= CURDATE() AND returned = 0";
            break;
    }

    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to fetch loans");

    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    $query_result->free_result();
    return $query_data;
}
function get_loans_list($conn, $view = 'ACTIVE') {
    $loans_data = get_loans($conn, $view);
    $result = '';
    foreach ($loans_data as $row) {
        if ($view == "RETURNED") {
            ob_start();
            $teacher_id = $row['teacher_id'];
            $device_sn = $row['device_sn'];

            include './page-parts/card-loan-returned.php';
            $result .= ob_get_clean();
            // $result .= "<div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Returned</div>";
        } else {
            ob_start();
            $loan_end = $row['loan_end'];
            $teacher_id = $row['teacher_id'];
            $device_sn = $row['device_sn'];
            $id = $row['id'];

            include './page-parts/card-loan-generic.php';
            $result .= ob_get_clean();
            // <div>Teacher ID: {$row['teacher_id']}, Device ID: {$row['device_id']}, Due: {$row['loan_end']} 
        }
    }
    return $result;
}