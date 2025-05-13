<?php
// require_once './utils.php';

function get_loans($conn, $view = 'ACTIVE', $username = NULL)
{
    $query = "SELECT 
        db.*,
        l.name AS device_name,
        l.category AS device_category,
        l.description AS device_description,
        u.name AS user_fullname
    FROM device_bookings db
    LEFT JOIN devices l ON db.device_sn = l.sn
    LEFT JOIN users u ON db.teacher_id = u.username";
    switch ($view) {
        case "RETURNED":
            $query = "SELECT
                db.*,
                u.name AS user_fullname
            FROM device_returns db
            LEFT JOIN users u ON db.teacher_id = u.username";
            break;
        case "OVERDUE":
            $query .= " WHERE booking_status = 'loaned' AND loan_end < CURDATE()";
            break;
        default:
        case "ACTIVE":
            $query .= " WHERE booking_status = 'loaned' AND loan_end >= CURDATE()";
            break;
    }

    if (isset($username)) {
      $query .= " AND teacher_id = '{$username}'";
    };

    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to fetch loans");

    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    $query_result->free_result();
    return $query_data;
}

function get_device_bookings($conn, $device_sn = NULL, $user_name = NULL) {
    $query = "SELECT
            db.*,
            l.name AS device_name,
            l.category AS device_category,
            u.name AS user_fullname
        FROM
            device_bookings db
        LEFT JOIN
            devices l ON db.device_sn = l.sn
        LEFT JOIN
            users u ON db.teacher_id = u.username
        WHERE db.booking_status = 'booked'";

    if (isset($device_sn)) {
        $query .= " AND db.device_sn = '{$device_sn}'";
    }

    if (isset($user_name)) {
    // if (is_allowed_user_role([ROLE_USER])) {
    //     $user_name = get_user_name();
        $query .= " AND db.teacher_id = '{$user_name}'";
    }

    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to fetch device bookings");

    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    $query_result->free_result();
    return $query_data;
}

function create_device_booking($conn, $device_sn, $loan_start, $loan_end, $teacher_id) {
    $stmt = $conn->prepare("INSERT INTO device_bookings (loan_start, loan_end, teacher_id, device_sn)
        VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $loan_start, $loan_end, $teacher_id, $device_sn);
    return $stmt->execute();
}

function loan_device($conn, $booking_id) {
    $query = "UPDATE device_bookings
        SET booking_status = 'loaned'
        WHERE id = {$booking_id}";
    
    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to fetch device bookings");

    // $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    // $query_result->free_result();
    // return $query_data;
}