<?php

session_start();

if($_POST['login'] == "" || $_POST['password'] == "")
{
    header("HTTP/1.0 404 Not Found");
    exit();
}

$url = 'http://localhost/rest/users?login';
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
}
else
{
    header("HTTP/1.0 404 Not Found");
    exit();
}


/* Test isAdmin */
$url = 'http://localhost/rest/users?isadmin&login='.$_POST['login'];

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

if($code == 200) {
    $_SESSION['is_admin'] = true;
}

?>
