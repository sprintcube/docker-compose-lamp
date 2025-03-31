<?php
require_once '../login.php';
require_once '../utils.php';

require_once '../page-components/device-management.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");
session_start();

if (!is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) {
    header('Location: /403.html');
    exit;
};

if (isset($_POST['device_sn']) && delete_device($conn, $_POST['device_sn'])) {
    header('Location: ../index.php');
    exit;
}