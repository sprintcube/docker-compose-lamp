<?php
require_once './utils.php';

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

    if (is_allowed_user_role([ROLE_USER])) {
      $user_id = get_user_id();
      $query .= " AND teacher_id = {$user_id}";
    };

    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to fetch loans");

    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    $query_result->free_result();
    return $query_data;
}

function get_device_bookings($conn, $device_sn) {
    $query = "SELECT * FROM device_bookings WHERE device_s = {$device_sn}";

    if (is_allowed_user_role([ROLE_USER])) {
        $user_id = get_user_id();
        $query .= " AND teacher_id = {$user_id}";

        $query_result = $conn->query($query);
        if (!$query_result) die ("Failed to fetch device bookings");

        $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
        $query_result->free_result();
        return $query_data;
    }
}

function create_device_booking($conn, $device_sn, $loan_start, $loan_end, $teacher_id) {
    $query = "INSERT INTO device_bookings (loan_start, loan_end, teacher_id, device_sn)
        VALUES ('$loan_start', '$loan_end', '$teacher_id', '$device_sn')";

    if (!$conn->query($query)) return $conn->error;
}