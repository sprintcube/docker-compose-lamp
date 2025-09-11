<?php
function drop_existing_otks($conn, $username) {
    $query = "DELETE FROM one_time_sessions
        WHERE username = '{$username}';";
    $conn->query($query);
}

function create_otk($conn, $username) {
    drop_existing_otks($conn, $username);
    $new_otk = bin2hex(random_bytes(8));
    $stmt = $conn->prepare("INSERT INTO one_time_sessions (otk, username)
        VALUES (?,?)");
    $stmt->bind_param('ss',$new_otk, $username);
    $stmt->execute();
    return $new_otk;
}

function delete_otk_by_sk($conn, $sk) {
    $query = "DELETE FROM one_time_sessions
        WHERE sk = '{$sk}'";
    $query_result = $conn->query($query);
    if(!$query_result) die ("Failed to delete one-time-key");
}

/**
 * Creates a session key for resetting user password.
 * Password reset session is valid for 30 minutes
 * after clicking the password reset link.
 * @param mixed $conn database connection
 * @param mixed $otk one-time password reset key
 * @return string password reset session key
 */
function create_sk($conn, $otk) {
    // Generate new session key
    $new_sk = bin2hex(random_bytes(8));
    // Get the current date and time in the correct format
    // https://www.mysqltutorial.org/mysql-basics/mysql-insert-datetime/
    $currentDateTime = date('Y-m-d H:i:s');

    $query = "UPDATE one_time_sessions
        SET sk = '{$new_sk}', session_start = '{$currentDateTime}'
        WHERE otk = '{$otk}'";
    $query_result = $conn->query($query);
    if (!$query_result) throw new Exception("Failed to create a session key");
    return $new_sk;
}

function get_username_by_sk($conn, $sk) {
    $query = "SELECT username FROM one_time_sessions
        WHERE sk = '{$sk}'";
    $query_result = $conn->query($query);

    if (!$query_result) die ("Failed to fetch username by session key");

    if ($query_result->num_rows > 0) {
        $row = $query_result->fetch_assoc();
        return $row['username'];
    } else {
        return false;
    }
}