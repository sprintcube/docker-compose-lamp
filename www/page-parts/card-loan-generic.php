<?php
require_once './utils.php';
$loan_start = date_format(date_create($row['loan_start']), 'D, d M Y');
$loan_end = date_format(date_create($row['loan_end']), 'D, d M Y');
$teacher_id = $row['teacher_id'];
$teacher_name = $row['user_fullname'];
$device_sn = $row['device_sn'];
$device_name = $row['device_name'];
$device_category = $row['device_category'];
$id = $row['id'];
?>
<div class='card my-2'>
    <div class='card-body'>
        <ul class='list-group list-group-flush'>
            <?php if (is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) { ?>
                <li class='list-group-item'><strong>Loaned to:</strong> <?php echo $teacher_name; ?> <i>(<?php echo $teacher_id; ?>)</i></li>
            <?php }?>
            <li class='list-group-item'><strong>Device:</strong> <?php echo $device_name . ' <small>(SN: ' . $device_sn . ')</small>'; ?></li>
            <li class='list-group-item'></strong><?php echo $loan_start; ?> &mdash; <?php echo $loan_end; ?></li>
        </ul>
        <?php if (is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) {?>
            <a class='btn btn-primary' href='/service/return-loan.php?id=<?php echo $id; ?>'>Return</a>
        <?php } ?>
    </div>
</div>