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

function get_user_search_form($search_term)
{
    ob_start();
    $is_searching_by_term = !!$search_term;

    include './page-parts/form-search-user.php';

    $result = ob_get_clean();
    return $result;
}