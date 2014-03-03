<?php
session_start();
if (!isset($_SESSION['is_auth']) || !$_SESSION['is_auth']) {
    header("location: /testbed/index.php#notlogin");
    exit();
}

$_SESSION = array();
header("location: /testbed/index.php#logout");
exit();

?>
