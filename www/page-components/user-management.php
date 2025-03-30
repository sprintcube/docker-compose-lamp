<?php
/**
 * Only include this from root pages, or component intended for use on a root page.
 */
function get_navigation_login_link() {
    ob_start();
    require './page-parts/log-in-link.php';
    $result = ob_get_clean();
    return $result;
}

function get_navigation_logout_link() {
    ob_start();
    require './page-parts/log-out-link.php';
    $result = ob_get_clean();
    return $result;
}