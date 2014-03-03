<?php

session_start();

if (!$_SESSION['is_auth'] || ($_SESSION['login'] == "" || $_SESSION['password'] == "")) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

if (!$_SESSION['is_admin']) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

$headers = 'From: admin@senslab.info' . "\r\n";

mail($_POST['to'], $_POST['subject'], $_POST['message'], $headers);

?>
