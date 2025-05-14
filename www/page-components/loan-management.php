<?php
require_once './utils.php';
require_once './db/loans-functions.php';
/**
 * Only include this from root pages, or component intended for use on a root page.
 */
function get_header_loans_button() {
    ob_start();
    require './page-parts/header-view-loans-button.php';
    $result = ob_get_clean();
    return $result;
}

function get_loans_list($conn, $view = 'ACTIVE', $teacher_id = NULL) {
    $loans_data = get_loans($conn, $view, $teacher_id);
    $result = '';
    foreach ($loans_data as $row) {
        if ($view == "RETURNED") {
            ob_start();
            include './page-parts/card-loan-returned.php';
            $result .= ob_get_clean();
        } else {
            ob_start();
            include './page-parts/card-loan-generic.php';
            $result .= ob_get_clean();
        }
    }
    return $result;
}

function get_bookings_list($conn, $teacher_id = NULL) {
    $bookings = get_device_bookings($conn, NULL, $teacher_id);
    $result = '';
    foreach ($bookings as $row) {
        ob_start();
        include './page-parts/list-item-device-booking.php';
        $result .= ob_get_clean();
    }
    return $result;
}

function get_notifications($notifications) {
    $result = '';
    foreach ($notifications as $row) {
        include './page-parts/notification.php';
    }
    return $result;
}
