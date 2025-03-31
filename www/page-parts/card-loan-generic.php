<div class='card my-2'>
    <div class='card-body'>
        <ul class='list-group list-group-flush'>
            <li class='list-group-item'><strong>Teacher ID:</strong> <?php echo $teacher_id; ?></li>
            <li class='list-group-item'><strong>Device SN:</strong> <?php echo $device_sn; ?></li>
            <li class='list-group-item'></strong> <?php echo $loan_end; ?></li>
        </ul>
        <a class='btn btn-primary' href='returnloan.php?id=<?php echo $id; ?>'>Return</a>
    </div>
</div>