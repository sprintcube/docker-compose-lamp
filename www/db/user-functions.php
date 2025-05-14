<?php

function get_current_user_info($conn, $username) {
    $query = "SELECT * FROM users WHERE username = '{$username}'";
    $query_result = $conn->query($query);
    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    return $query_data[0];
}

