<?php
/**
 * Only include this from root pages, or component intended for use on a root page.
 */
function get_header_loans_button() {
    ob_start();
    require './page-parts/header-view-loans-button.php';
    $result = ob_get_clean();
    return $result;
}

