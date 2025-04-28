<?php 
error_reporting(E_ALL);
require_once '../login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_sn = (int) $_GET['sn'];

function update_device($conn, $device_sn) {
    $stmt = $conn->prepare("UPDATE laite SET name = ?, category = ? WHERE sn = ?");
    $stmt->bind_param('sss', $_POST['name'], $_POST['category'], $device_sn);
    $stmt->execute();
}

function resize_image($file_path, $target_dimensions, $original_ext) {
    list($width, $height) = $target_dimensions;
    list($o_width, $o_height) = getimagesize($file_path);
    $new_image = imagecreatetruecolor($width, $height);

    // Create a new image depending on source file type
    switch ($original_ext) {
        case 'jpeg':
        case 'jpg':
            $image = imagecreatefromjpeg($file_path);
            break;
        case 'png':
            $image = imagecreatefrompng($file_path);
            break;
        case 'webp':
            $image = imagecreatefromwebp($file_path);
            break;
        case 'gif':
            $image = imagecreatefromgif($file_path);
            break;
        default:
            return false; // unsupported image format
    };
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, $o_width, $o_height);
    imagepng($new_image, $file_path);
    imagedestroy($new_image);
    imagedestroy($image);
    return true;
}

function update_device_picture($device_sn, $image) {
    $image_dims = [600, 350];
    $target_dir = '../assets/images/devices/';
    $original_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $target_file = $target_dir . $device_sn . '.png';

    if (getimagesize($image['tmp_name'])) {
        move_uploaded_file($image['tmp_name'], $target_file);
        resize_image($target_file, $image_dims, $original_ext);
        header('Location: /index.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['category'])) {
    update_device($conn, $device_sn);

    if (isset($_FILES['image'])) {
        update_device_picture($device_sn, $_FILES['image']);
    }
    header("Location: /index.php");
}
