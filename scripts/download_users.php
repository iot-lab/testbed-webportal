<?php

session_start();

if(!$_SESSION['is_auth'] || ($_SESSION['login'] == "" || $_SESSION['password'] == "" || !$_SESSION['is_admin']))
{
    header("HTTP/1.0 404 Not Found");
    exit();
}



/* Get total */
$url = "https://localhost/rest/admin/users";

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
curl_close($handle);

if($code == 200) {
	$users = json_decode($response);

	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename="users.csv"');

        echo "FirstName;LastName;Mail;Country;City;Structure\n";
	for($i=0;$i<sizeof($users);$i++) {
		echo $users[$i]->{'firstName'} . ";" . $users[$i]->{'lastName'} . ";" . $users[$i]->{'email'} . ";" . $users[$i]->{'country'} . ";" . $users[$i]->{'city'} . ";" . $users[$i]->{'structure'} . "\n";
	}

}





?>
