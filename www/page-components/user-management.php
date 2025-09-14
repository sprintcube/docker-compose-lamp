<?php

function render_users_list($users_data) {
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
    return $list;
}

function render_users_pagination_page_links($current_page_number, $number_of_pages, $page_size) {
    ob_start();
    $has_first = $current_page_number > 4;
    $has_last = $current_page_number < ($number_of_pages - 3);
    $previous_page_number = $current_page_number > 2 ? $current_page_number - 1 : false;
    $next_page_number = $current_page_number < $number_of_pages ? $current_page_number + 1 : false;

    include './page-parts/user-pagination.php';
    return ob_get_clean();
}

function get_user_search_form($search_term)
{
    ob_start();
    $is_searching_by_term = !!$search_term;

    include './page-parts/form-search-user.php';

    $result = ob_get_clean();
    return $result;
}