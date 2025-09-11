<?php
include_once './utils.php';

$can_promote = is_allowed_user_role([ROLE_SUPER_ADMIN]);
?>

<div class="col-lg-12 mb-2">
    <div class="card h-100 shadow border-0">
        <div class='card-body'>
            <h5 class="card-title"><?php echo $name; ?></h5>
        </div>
        <ul class='card-body list-group list-group-flush'>
            <li class='list-group-item'>Username: <?php echo $username; ?></li>
            <li class='list-group-item'>Role: <?php echo $role; ?></li>
            <li class='list-group-item'>Email: <?php echo $email; ?></li>
        </ul>
        <div class='card-body btn-toolbar justify-content-between'>
            <div>
                <?php if (in_array($role, [ROLE_ADMIN, ROLE_USER]) & $can_promote) {?>
                    <form method="POST" action="/service/user-promotion.php">
                        <input hidden type="text" name="username" value="<?php echo $username; ?>" />
                        <button class='btn btn-secondary' type="submit">Promote user</button>
                    </form>
                <?php }?>
                <?php if (in_array($role, [ROLE_ADMIN, ROLE_SUPER_ADMIN]) & $can_promote) {?>
                    <form method="POST" action="/service/user-promotion.php">
                        <input hidden type="text" name="username" value="<?php echo $username; ?>" />
                        <input hidden type="text" name="demote" value="true" />
                        <button class='btn btn-primary' type="submit">Demote user</button>
                    </form>
                <?php }?>
                <form method="POST" action="/service/reset-user-pass.php">
                    <input hidden type="text" name="username" value="<?php echo $username; ?>" />
                    <button class='btn btn-primary' type="submit">Reset user password</button>
                </form>
            </div>
        </div>
    </div>
</div>