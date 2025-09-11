<?php

function render_users_list($users_data) {
    // $list = '<div class="row gx-5">';
    $list = '';
    foreach ($users_data as $row) {
        ob_start();
        $username = $row['username'];
        $email = $row['email'];
        $role = $row['role'];
        $name = $row['name'];

        include './page-parts/card-user.php';
        $list .= ob_get_clean();
    }

    // $list .= '</div>';
    return $list;
}