<?php
require_once '../login.php';
require_once '../db/pswd-reset-functions.php';
require_once '../db/user-functions.php';

// One time key is provided by a user 
// when they open the recovery link
$one_time_key = isset($_GET['otk']) ? $_GET['otk'] : false;

// Session key is generated for each recovery session
// it connects the password recovery form to the 
// backend recovery function.
$session_key = isset($_POST['sk']) ? $_POST['sk'] : false;


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
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $one_time_key && validate_otk($conn, $one_time_key)) {
        handle_password_reset($conn, $one_time_key);
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $session_key && isset($_POST['password'])) {
        $password = $_POST['password'];
        $stored_username = get_username_by_sk($conn, $session_key);
        if ($stored_username) {
            handle_form_submit(
                $conn, 
                $session_key, 
                $stored_username, 
                $password);
            header("Location: /loginpage.html");
            exit;
        }
        throw new Exception("Could not find username");
    }
} catch (Exception $e) {
    // TODO: create a bespoke error page for invalid password reset links
    error_log($e);
    header("Location: /errors/inactive-recovery-link.html");
    try {
        delete_otk_by_sk($conn, $session_key);
    } catch (Exception $err) {
        error_log($err);
        header("Location: /errors/inactive-recovery-link.html");
    }
} finally {
    exit;
}