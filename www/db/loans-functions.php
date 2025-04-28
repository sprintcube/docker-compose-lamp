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

function get_device_bookings($conn, $device_sn = false) {
    $query = "
        SELECT
            db.*,
            l.name AS device_name,
            l.category AS device_category
        FROM
            device_bookings db
        LEFT JOIN
            laite l ON db.device_sn = l.sn
    ";

    if ($device_sn) {
        $query .= " WHERE db.device_sn = '{$device_sn}'";
    }

    if (is_allowed_user_role([ROLE_USER])) {
        $user_name = get_user_name();
        $query .= ($device_sn ? " AND" : " WHERE") . " db.teacher_id = '{$user_name}'";
    }

    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to fetch device bookings");

    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    $query_result->free_result();
    return $query_data;
}

function create_device_booking($conn, $device_sn, $loan_start, $loan_end, $teacher_id) {
    $query = "INSERT INTO device_bookings (loan_start, loan_end, teacher_id, device_sn)
        VALUES ('$loan_start', '$loan_end', '$teacher_id', '$device_sn')";

    if (!$conn->query($query)) return $conn->error;
}