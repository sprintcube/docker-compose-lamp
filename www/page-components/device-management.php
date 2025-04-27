<?php
require_once "./db/devices-functions.php";

function get_add_device_modal()
{
    ob_start();
    include './page-parts/add-device-modal.php';
    $result = ob_get_clean();
    return $result;
}

function get_header_add_device_button()
{
    ob_start();
    include './page-parts/header-add-device-button.php';
    $result = ob_get_clean();
    return $result;
}

function get_device_search_form()
{
    ob_start();
    $is_searching_by_term = isset($_GET['search-term']);
    $search_term = $is_searching_by_term ? htmlspecialchars($_GET['search-term']) : false;

    include './page-parts/device-search-form.php';

    $result = ob_get_clean();
    return $result;
}

function get_device_list($devices_data)
{
    $columnCount = 0;
    $list = '<div class="row gx-5">';
    foreach ($devices_data as $row) {
        ob_start();
        $serialNumber = $row['sn'];
        $deviceModalIdStub = "DeviceModal-" . $serialNumber . '-' . $columnCount;
        // $loanDeviceModalId = 'loan' . $deviceModalIdStub;
        $editDeviceModalId = 'edit' . $deviceModalIdStub;
        $bookDeviceModalId = 'book' . $deviceModalIdStub;

        include './page-parts/card-device.php';
        if ($columnCount % 3 == 0) {
            $list .= '</div>'; // Closing the <div class="row">
            $list .= '<div class="row gx-5">';
        }
        $list .= ob_get_clean();
        $columnCount++;
    }
    if ($columnCount % 3 != 0) {
        $list .= '</div>'; // close a single unclosed row
    }

    $list .= '</div>';
    return $list;
}

function get_device_edit_modal($device_row, $id_prefix) {
    ob_start();
    $serialNumber = $device_row['sn'];
    $deviceModalIdStub = "DeviceModal-" . $serialNumber . '-' . $id_prefix;
    $loanDeviceModalId = 'loan' . $deviceModalIdStub;
    $editDeviceModalId = 'edit' . $deviceModalIdStub;
    $nameInputId = "nameInput" . $editDeviceModalId;
    $snInputId = "snInput" . $editDeviceModalId;
    $categoryInputId = "categoryInput" . $editDeviceModalId;

    include './page-parts/edit-device-modal.php';
    $result = ob_get_clean();
    return $result;
}

function get_device_loan_modal($device_row, $id_prefix) {
    ob_start();
    $serialNumber = $device_row['sn'];
    $deviceModalIdStub = "DeviceModal-" . $serialNumber . '-' . $id_prefix;
    $loanDeviceModalId = 'loan' . $deviceModalIdStub;
    $teacherInputId = "teacherInput" . $loanDeviceModalId;
    $loanStartInputId = "loanStartInput" . $loanDeviceModalId;
    $loanEndInputId = "loanEndInput" . $loanDeviceModalId;

    include './page-parts/modal-device-loan.php';
    $result = ob_get_clean();
    return $result;
}

function get_device_book_modal($device_row, $id_prefix) {
    ob_start();
    $serialNumber = $device_row['sn'];
    $deviceModalIdStub = "DeviceModal-" . $serialNumber . '-' . $id_prefix;
    $bookDeviceModalId = 'book' . $deviceModalIdStub;
    $teacherInputId = "teacherInput" . $bookDeviceModalId;
    $loanStartInputId = "loanStartInput" . $bookDeviceModalId;
    $loanEndInputId = "loanEndInput" . $bookDeviceModalId;

    include './page-parts/modal-device-book.php';
    $result = ob_get_clean();
    return $result;
}

function get_device_modals($device_data) {
    $column_count = 0;
    $device_modals = '';
    foreach ($device_data as $row) {
        $device_modals .= get_device_edit_modal($row, $column_count);
        // $device_modals .= get_device_loan_modal($row, $column_count);
        $device_modals .= get_device_book_modal($row, $column_count);
        $column_count++;
    }
    return $device_modals;
}