<?php

session_start();

if(!$_SESSION['is_auth']) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

$url = 'https://localhost/rest/experiment/'.$_GET['id'].'?data';

$headers = array();

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

curl_setopt($handle, CURLOPT_HEADER, false);
curl_setopt($handle, CURLOPT_VERBOSE, false);

curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($handle, CURLOPT_USERPWD, $_SESSION['login'] . ":" . $_SESSION['password']);
curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
$response = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);


if($code == 200) {
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment; filename="'.$_GET['id'].'.tar.gz"');
	echo $response;
}
else
{
    header("HTTP/1.0 404 Not Found");
    exit();
}

?>
