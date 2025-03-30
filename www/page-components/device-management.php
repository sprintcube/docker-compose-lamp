<?php

function get_add_device_modal() {
    ob_start();
    include './page-parts/add-device-modal.php';
    $result = ob_get_clean();
    return $result;
}

function get_header_add_device_button() {
    ob_start();
    include './page-parts/header-add-device-button.php';
    $result = ob_get_clean();
    return $result;
}
