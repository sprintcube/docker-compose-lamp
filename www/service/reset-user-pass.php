<?php
$ROOT_WEBSITE_URL = "http://tietokanta.dy.fi:8000/laiterekisteri/site/";

require_once '../login.php';
require_once '../utils.php';
require_once '../db/pswd-reset-functions.php';

session_start();

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Connection failed");

$username = $_POST["username"];

function render_link($otk, $root) {
    return $root . "service/onetime.php?otk=" . $otk;
}

if (!is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) {
    header("Location: /403.html");
} else if (
    $_SERVER["REQUEST_METHOD"] === 'POST'
    && isset($username)) {
        $otk = create_otk($conn, $username);
        // render the link
        $link = render_link($otk, $ROOT_WEBSITE_URL);
        echo $link;
        exit;
    }
    header("Location: /index.php");