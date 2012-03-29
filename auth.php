<?php

session_start();


$url = 'http://localhost/rest/admin/user?bind';
$method = 'POST';

# headers and data (this is API dependent, some uses XML)
$headers = array(
'Accept: application/json',
'Content-Type: application/json',
);

$data = json_encode(array(
'login'=> $_POST['login'],
'password'=> $_POST['password']
));

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

switch($method)
{

case 'GET':
break;

case 'POST':
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
break;

case 'PUT':
curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
break;

case 'DELETE':
curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
break;
}

$response = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

var_dump($response);
var_dump($code);

if($code == 200) {
    $_SESSION['is_auth'] = true;
}
else
{
    header("HTTP/1.0 404 Not Found");
}


?>
