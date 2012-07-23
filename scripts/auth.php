<?php

session_start();

if($_POST['login'] == "" || $_POST['password'] == "")
{
    header("HTTP/1.0 404 Not Found");
    exit();
}

$url = 'https://localhost/rest/users/'.$_POST['login'].'?login';
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

curl_setopt($handle, CURLOPT_HEADER, true);
curl_setopt($handle, CURLOPT_VERBOSE, true);

curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($handle, CURLOPT_USERPWD, $_POST['login'] . ":" . $_POST['password']);
curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
$response = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);


if($code == 200) {
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['is_auth'] = true;
	$_SESSION['basic_value'] = base64_encode($_POST['login'].":".$_POST['password']);
}
else
{
    header("HTTP/1.0 404 Not Found");
    exit();
}


/* Test isAdmin */
$url = 'https://localhost/rest/users/'.$_POST['login'].'/isadmin';

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($handle, CURLOPT_POST, true);
//curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_USERPWD, $_POST['login'] . ":" . $_POST['password']);
curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$response = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if($code == 200 && $response == "Success") {
    $_SESSION['is_admin'] = true;
}

?>
