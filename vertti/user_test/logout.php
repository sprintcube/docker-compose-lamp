<?php
// this code logs out. hopefully. i think it does.  yeah it does.
session_start();
session_destroy();
header('Location: /index.php');
exit;
?>