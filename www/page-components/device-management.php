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

function get_device_search_form($search_term) {
    ob_start();
    $is_searching_by_term = isset($_GET['search-term']);
    $search_term = $is_searching_by_term ? htmlspecialchars($_GET['search-term']) : false;

    include './page-parts/device-search-form.php';

    $result = ob_get_clean();
    return $result;
}