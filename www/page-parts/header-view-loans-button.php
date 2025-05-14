<?php
require_once './utils.php';
?>

<!-- TODO: handle this anchor gracefully, dunno if its neede yet -->
<?php if (is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) {?>
    <a class="btn btn-outline-light  btn-lg px-4" href="#loans">View Loans</a>
<?php } else { ?>
    <a class="btn btn-primary btn-custom-stadinbtn-lg px-4" href="#loans">View Loans</a>
<?php } ?>