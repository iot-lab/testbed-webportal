<?php
$login="schmidt";
$password="coucou_";


$headers = array();

$url = 'https://localhost/rest/admin/users';
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

curl_setopt($handle, CURLOPT_HEADER, true);
curl_setopt($handle, CURLOPT_VERBOSE, false);

curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($handle, CURLOPT_USERPWD, $login . ":" . $password);
curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
$response = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);


if($code == 200) {
	// on traite les infos des users pour enlever les trucs qui ne sont pas public :
	// en gros, on a besoin du nombre d'user, de leur etats (pending, admin, etc ...) et de leur pays
	$header_size = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	$json_users=json_decode($body);

	$total_users=count($json_users);
	$admin=0;
	$pending=0;
	$countries = array();
	foreach ($json_users as $item) {
		if($item->admin) $admin++;
		if(!$item->validate) $pending++;
		if(array_key_exists($item->country,$countries)) $countries[$item->country]++;
		else $countries[$item->country]=1;
	}

	$json_output = array("nb_users" => $total_users, "admin" => $admin, "pending" => $pending, "countries" => $countries);
	echo json_encode($json_output);
} else {
    header("HTTP/1.0 404 Not Found");
    exit();
}

?>
