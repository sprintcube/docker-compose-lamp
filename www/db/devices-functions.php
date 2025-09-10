<?php

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
        $crop_width = (int) $orig_height * $target_ratio;
        $crop_height = (int) $orig_height;
        $src_x = (int) ($orig_width - $crop_width) / 2;
        $src_y = 0;
    } else {
        // Original image is taller than the target aspect ratio, or the ratios are equal; crop the height.
        $crop_width = (int) $orig_width;
        $crop_height = (int) $orig_width / $target_ratio;
        $src_x = 0;
        $src_y = (int) ($orig_height - $crop_height) / 2;
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
        (int) $src_x,      // Source X (cropping start)
        (int) $src_y,      // Source Y (cropping start)
        (int) $target_width, // Destination width
        (int) $target_height, // Destination height
        (int) $crop_width,   // Source width (cropping width)
        (int) $crop_height  // Source height (cropping height)
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
    $filename = (string) $device_sn . '.png';
    $target_file_path = $target_dir . $filename;

    if (getimagesize($image['tmp_name'])) {
        move_uploaded_file($image['tmp_name'], $target_file_path);
        resize_image($target_file_path, $image_dims, $original_ext);
        header('Location: /index.php');
    }
}

function get_devices($conn) {
    $is_searching_by_term = isset($_GET['search-term']);
    $search_term = $is_searching_by_term ? htmlspecialchars($_GET['search-term']) : false;
    $query = '';
    if (isset($search_term)) {
        $search_term_sanitized = $conn->real_escape_string($search_term);
        $query = "SELECT * FROM devices WHERE name LIKE '%{$search_term_sanitized}%' OR sn LIKE '%{$search_term_sanitized}%'";
    } else {
        $query = "SELECT * FROM devices";
    }

    $result = $conn->query($query);
    if (!$result) die("Database access failed");

    $result_data = $result->fetch_all(MYSQLI_ASSOC);
    $result->free_result();
    return $result_data;
}

function update_device($conn, $device_sn) {
    $stmt = $conn->prepare("UPDATE devices SET name = ?, category = ?, description = ? WHERE sn = ?");
    $stmt->bind_param('ssss', $_POST['name'], $_POST['category'], $_POST['description'], $device_sn);
    return $stmt->execute();
}

function delete_device($conn, $device_sn) {
    $query = "DELETE FROM devices WHERE sn = {$device_sn}";
    $result = $conn->query($query);

    if (!$result) die("Could not delete a device");
    return true;
}

function create_device($conn, $name, $sn, $category, $description, $image) {
    $stmt = $conn->prepare("INSERT INTO devices (name, sn, category, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $sn, $category, $description);
    $result = $stmt->execute();

    if (isset($image)) {
        update_device_picture($sn, $image);
    }

    return $result;
}