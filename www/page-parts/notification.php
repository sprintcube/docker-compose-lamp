<?php
$device_sn = $row['device_sn'];
$device_name = $row['device_name'];
$message = $row['message'];
$id = $row['id'];
?>
<div class="toast show my-2" role="alert" aria-live="assertive" aria-atomic="true" style="width: 100%">
    <div class="toast-header">
        <i class="bi bi-bell"></i>
        <strong class="me-auto mx-2">From the Administrator</strong>
        <small>About your booking of <?php echo $device_name; ?> <br/> (SN: <?php echo $device_sn; ?>)</small>
        <a href="service/dismiss-notification.php?id=<?php echo $id;?>" class="btn-close" aria-label="Close"></a>
    </div>
    <div class="toast-body">
        <?php echo $message ?>
    </div>
</div>

