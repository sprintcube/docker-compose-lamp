<?php 
error_reporting(E_ALL);
require_once '../login.php';
require_once '../db/devices-functions.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_sn = (int) $_GET['sn'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['category'])) {
    update_device($conn, $device_sn);

    if($_FILES["image"]["size"]>0){
        echo "There is a file uploaded<br>";
        // Check if its an image
            $check_if_image = getimagesize($_FILES["image"]["tmp_name"]);
            if($check_if_image !== false) {
                update_device_picture($device_sn, $_FILES['image']);    
            }
    }
    header("Location: /index.php");
}
