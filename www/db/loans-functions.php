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
}

function get_device_booking_by_id($conn, $booking_id) {
    $query = "SELECT
        db.*,
        l.name AS device_name,
        l.category AS device_category,
        l.description AS device_description
    FROM device_bookings db
    LEFT JOIN devices l ON db.device_sn = l.sn
    WHERE db.id = {$booking_id}
    LIMIT 1";
    $query_result = $conn->query($query);
    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    $query_result->free_result();
    return $query_data[0];
}

function create_device_return_record($conn, $booking_info) {
    $device_sn = $booking_info['device_sn'];
    $teacher_id = $booking_info['teacher_id'];
    $device_name = $booking_info['device_name'];
    $device_category = $booking_info['device_category'];
    $device_description = $booking_info['device_description'];
    $loan_start = $booking_info['loan_start'];
    $loan_end = $booking_info['loan_end'];
    $stmt = $conn->prepare("INSERT INTO device_returns 
        (device_sn, teacher_id, device_name, device_category, device_description, loan_start, loan_end)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssss',
        $device_sn,
        $teacher_id,
        $device_name,
        $device_category,
        $device_description,
        $loan_start,
        $loan_end);
    return $stmt->execute();
}

function return_loaned_device($conn, $booking_id) {
    $booking_info = get_device_booking_by_id($conn, $booking_id);
    $return_record_result = create_device_return_record($conn, $booking_info);
    $query = "DELETE FROM device_bookings WHERE id = {$booking_id} AND booking_status = 'loaned'";
    $query_result = $conn->query($query);
    if (!$query_result || ! $return_record_result) die ("Failed to return device");
}

function cancel_booking($conn, $booking_id) {
    $query = "DELETE FROM device_bookings WHERE id = {$booking_id} AND booking_status = 'booked'";
    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to cancel booking");
}

function create_booking_notification($conn, $booking_info, $message) {
    $username = $booking_info['teacher_id'];
    $device_name = $booking_info['device_name'];
    $device_sn = $booking_info['device_sn'];
    $stmt = $conn->prepare("INSERT INTO booking_notifications
        (username, device_sn, device_name, message)
        VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $username, $device_sn, $device_name, $message);
    return $stmt->execute();
}

function get_notifications_for_user($conn, $username) {
    $query = "SELECT * FROM booking_notifications WHERE username = '{$username}'";
    $query_result = $conn->query($query);
    $query_data = (array) $query_result->fetch_all(MYSQLI_ASSOC);
    $query_result->free_result();

    return $query_data;
}

function reject_booking($conn, $booking_id) {
    $messsage = "Your booking was rejected by the administration";
    $booking_info = get_device_booking_by_id($conn, $booking_id);
    cancel_booking($conn, $booking_id);
    return create_booking_notification($conn, $booking_info, $messsage);
}


function dismiss_notification($conn, $username, $notification_id) {
    $query = "DELETE FROM booking_notifications WHERE id = {$notification_id} AND username = '{$username}'";
    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed dismiss notification");
}