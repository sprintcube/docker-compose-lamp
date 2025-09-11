<?php

function get_current_user_info($conn, $username) {
    $query = "SELECT * FROM users WHERE username = '{$username}'";
    $query_result = $conn->query($query);
    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    return $query_data[0];
}

function get_all_users_info($conn, $page_num = 1, $page_size = 25) {
    $offset = ($page_num - 1) * $page_size;
    $query = "SELECT username, role, email, name  FROM users LIMIT {$offset}, {$page_size}";
    $query_result = $conn->query($query);
    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    return $query_data;
}


function search_users_info($conn, $search_term, $page_num = 1, $page_size = 25) {
    $offset = ($page_num - 1) * $page_size;
    $st = htmlspecialchars($search_term);
    $query = "SELECT 
        username, 
        role, 
        email, 
        name  
    FROM users 
    WHERE  
        username LIKE '%{$st}%' OR
        email LIKE '%{$st}%' OR
        name LIKE '%{$st}%'
    LIMIT {$offset}, {$page_size}";
    $query_result = $conn->query($query);
    $query_data = $query_result->fetch_all(MYSQLI_ASSOC);
    return $query_data;
}

function reset_user_password($conn, $username, $password_hash) {
    $query = "UPDATE users
        SET password_hash = {$password_hash}
        WHERE username = {$username}";
    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to update password hash");
}