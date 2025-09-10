<?php
require_once '../login.php';
require_once '../utils.php';

require_once '../db/devices-functions.php';

$EXCEPTION_DELETE_UNAVAILABLE_ERROR = "Cannot delete or update a parent row: a foreign key constraint fails";
$is_device_delete_success = false;

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");
session_start();

if (!is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) {
    header('Location: /403.html');
    exit;
};

try {
    $is_device_delete_success = delete_device($conn, $_POST['device_sn']);
} catch (Exception $e) {
    if (strstr($e, $EXCEPTION_DELETE_UNAVAILABLE_ERROR)) {
        header("Location: /errors/delete-error.html");
    } else {
        echo $e->getMessage();
        header("Location: /errors/error.html");
    }
}

if (isset($_POST['device_sn']) && $is_device_delete_success) {
    header('Location: ../index.php');
    exit;
}