<?php

function create_otk($conn, $username) {
    $new_otk = bin2hex(random_bytes(8));
    $stmt = $conn->prepare("INSERT INTO one_time_sessions (otk, username)
        VALUES (?,?)");
    $stmt->bind_param('ss',$new_otk, $username);
    $stmt->execute();
    return $new_otk;
}

function delete_otk_by_sk($conn, $sk) {
    $query = "DELETE FROM one_time_sessions
        WHERE sk = {$sk}";
    $query_result = $conn->query($query);
    if(!$query_result) die ("Failed to delete one-time-key");
}

function create_sk($conn, $otk) {
    $new_sk = bin2hex(random_bytes(8));
    $query = "UPDATE one_time_sessions
        SET sk = {$new_sk}
        WHERE otk = {$otk}";
    $query_result = $conn->query($query);
    if (!$query_result) die ("Failed to create a session key");
}

function get_username_by_sk($conn, $sk) {
    $query = "SELECT username FROM one_time_sessions
        WHERE sk = {$sk}";
    $query_result = $conn->query($query);

    if (!$query_result) die ("Failed to fetch username by session key");

    if ($query_result->num_rows > 0) {
        $row = $query_result->fetch_assoc();
        return $row['username'];
    } else {
        return false;
    }
}