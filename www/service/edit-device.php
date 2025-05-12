<?php 
error_reporting(E_ALL);
require_once '../login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$device_sn = (int) $_GET['sn'];

function update_device($conn, $device_sn) {
    $stmt = $conn->prepare("UPDATE devices SET name = ?, category = ? WHERE sn = ?");
    $stmt->bind_param('sss', $_POST['name'], $_POST['category'], $device_sn);
    $stmt->execute();
}

/**
 * Resizes an image, crops it to the target aspect ratio, and saves it as a PNG.
 *
 * @param string $file_path        Path to the image file.
 * @param array  $target_dimensions Array containing the target width and height (e.g., [300, 200]).
 * @param string $original_ext     The original file extension (e.g., 'jpg', 'png', 'webp', 'gif').
 *
 * @return bool True on success, false on failure (e.g., unsupported image format).
 */
function resize_image($file_path, $target_dimensions, $original_ext) {
    list($target_width, $target_height) = $target_dimensions;
    list($orig_width, $orig_height) = getimagesize($file_path);
    $target_ratio = $target_width / $target_height;
    $orig_ratio = $orig_width / $orig_height;

    if ($orig_ratio > $target_ratio) {
        // Original image is wider than the target aspect ratio; crop the width.
        $crop_width = $orig_height * $target_ratio;
        $crop_height = $orig_height;
        $src_x = ($orig_width - $crop_width) / 2;
        $src_y = 0;
    } else {
        // Original image is taller than the target aspect ratio, or the ratios are equal; crop the height.
        $crop_width = $orig_width;
        $crop_height = $orig_width / $target_ratio;
        $src_x = 0;
        $src_y = ($orig_height - $crop_height) / 2;
    }

    $new_image = imagecreatetruecolor($target_width, $target_height);
    // Keep transparency for PNG and GIF
    if ($original_ext === 'png' || $original_ext === 'gif') {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
        imagefill($new_image, 0, 0, $transparent); // Fill with transparent color
    }

    // Create a new image resource from file
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
            return false; // Unsupported image format
    }

    // Handle transparency for GIF and PNG source images
    if ($original_ext === 'png' || $original_ext === 'gif') {
        imagealphablending($image, true); // Ensure source image's transparency is respected.
    }
    imagecopyresampled(
        $new_image,  // Destination image
        $image,      // Source image
        0,          // Destination X
        0,          // Destination Y
        $src_x,      // Source X (cropping start)
        $src_y,      // Source Y (cropping start)
        $target_width, // Destination width
        $target_height, // Destination height
        $crop_width,   // Source width (cropping width)
        $crop_height  // Source height (cropping height)
    );

    // Overwrite the original file with the resized and cropped PNG
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
