<?php
require_once './utils.php';
?>

<div class="col-lg-4 mb-5">
    <div class="card h-100 shadow border-0">
        <img class="card-img-top" src="https://dummyimage.com/600x350/ced4da/6c757d" alt="..." />
        <div class='card-body'>
            <div class='card-title'><?php echo $row['name'] ?></div>
        </div>
        <ul class='list-group list-group-flush'>
            <li class='list-group-item'>SN: <?php echo $row['sn'] ?></li>
            <li class='list-group-item'>Category: <?php echo $row['category'] ?></li>
        </ul>
        <div class='card-body'>
            <?php if (is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) { ?>
                <button data-bs-toggle='modal' data-bs-target='#<?php echo $editDeviceModalId ?>' class='btn btn-secondary' href=''>Edit</button>
            <?php } if (is_logged_in()) { ?>
                <button data-bs-toggle='modal' data-bs-target='#<?php echo $loanDeviceModalId ?>' class='btn btn-primary' href=''>Loan</button>
            <?php } ?>
        </div>
    </div>
</div>