<?php

session_start();

$data = json_decode(file_get_contents("php://input"), true);
$captcha = $data['captcha'];
unset($data['captcha']);

if (!empty($captcha)) {
    if (empty($_SESSION['captcha']) || trim(strtolower($captcha)) != $_SESSION['captcha']) {
        header('HTTP/1.1 403 Forbidden');
        exit();
    }
} else {
    header('HTTP/1.1 403 Forbidden');;
    exit();
}

$url = 'https://localhost/rest/users';

# headers and data (this is API dependent, some uses XML)
$headers = array(
    'Accept: application/json',
    'Content-Type: application/json',
);

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if ($code != 200) {
    echo $code . " - " . $response;
    header('HTTP/1.1 403 Forbidden');
    exit();
}
