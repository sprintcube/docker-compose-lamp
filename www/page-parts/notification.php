<?php
$device_sn = $row['device_sn'];
$device_name = $row['device_name'];
$message = $row['message'];
?>
<div class="toast show my-2" role="alert" aria-live="assertive" aria-atomic="true" style="width: 100%">
    <div class="toast-header">
        <i class="bi bi-bell"></i>
        <strong class="me-auto mx-2">From the Administrator</strong>
        <small>About your booking of <?php echo $device_name; ?> <br/> (SN: <?php echo $device_sn; ?>)</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        <?php echo $message ?>
    </div>
</div>

