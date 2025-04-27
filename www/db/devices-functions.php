<?php

function get_devices($conn) {
    $is_searching_by_term = isset($_GET['search-term']);
    $search_term = $is_searching_by_term ? htmlspecialchars($_GET['search-term']) : false;
    $query = '';
    if (isset($search_term)) {
        $search_term_sanitized = $conn->real_escape_string($search_term);
        $query = "SELECT * FROM laite WHERE name LIKE '%{$search_term_sanitized}%' OR sn LIKE '%{$search_term_sanitized}%'";
    } else {
        $query = "SELECT * FROM laite";
    }

    $result = $conn->query($query);
    if (!$result) die("Database access failed");

    $result_data = $result->fetch_all(MYSQLI_ASSOC);
    $result->free_result();
    return $result_data;
}

function delete_device($conn, $device_sn) {
    $query = "DELETE FROM laite WHERE sn = {$device_sn}";
    $result = $conn->query($query);

    if (!$result) die("Could not delete a device");
    return true;
}
