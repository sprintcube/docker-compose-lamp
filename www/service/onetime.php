<?php
require_once '../login.php';
require_once '../db/pswd-reset-functions.php';
require_once '../db/user-functions.php';

// One time key is provided by a user 
// when they open the recovery link
$one_time_key = $_GET['otk'];

// Session key is generated for each recovery session
// it connects the password recovery form to the 
// backend recovery function.
$session_key = $_POST['sk'];
$password = $_POST['password'];

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

function render_password_reset_form($sk) {
    ob_start();
    include '../page-parts/form-reset-password.php';
    $result = ob_get_clean();
    return $result;
}

function handle_password_reset($conn, $otk) {
    // generate a new session key for one time key
    $sk = create_sk($conn, $otk);
    // render reset password form
    echo render_password_reset_form($sk);
}

function handle_form_submit(
    $conn,
    $session_key,
    $username,
    $password
) {
    // remove one-time-key by session key
    delete_otk_by_sk($conn, $session_key);
    // hash the password and update the users table
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    reset_user_password($conn, $username, $password_hash);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($one_time_key)) {
        handle_password_reset($conn, $one_time_key);
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($session_key)) {
        $stored_username = get_username_by_sk($conn, $session_key);
        if ($stored_username) {
            handle_form_submit(
                $conn, 
                $session_key, 
                $stored_username, 
                $password);
            header("Location: /login.php");
            exit;
        }
    }
} catch (Exception $e) {
    error_log($e);
    try {
        delete_otk_by_sk($conn, $session_key);
    } catch (Exception $err) {
        error_log($err);
    }
} finally {
    header("Location: /errors/error.html");
    exit;
}