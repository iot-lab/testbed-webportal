<?php 
session_start();
if(!isset($_SESSION['is_auth']) || !$_SESSION['is_auth']) {
	header("location: /");
    exit();
}

$_SESSION = array();
header("location: /");
exit();

?>
