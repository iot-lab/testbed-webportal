<?php

session_start();

if(!$_SESSION['is_auth'] || ($_SESSION['login'] == "" || $_SESSION['password'] == ""))
{
    header("HTTP/1.0 404 Not Found");
    exit();
}

$url = "https://localhost/rest/experiments?limit=".$_GET['iDisplayLength']."&offset=".$_GET['iDisplayStart'];
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

curl_setopt($handle, CURLOPT_HEADER, false);
curl_setopt($handle, CURLOPT_VERBOSE, true);

curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($handle, CURLOPT_USERPWD, $_SESSION['login'] . ":" . $_SESSION['password']);
curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
$response = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
curl_close($handle);


if($code != 200) {
    header("HTTP/1.0 404 Not Found");
    exit();
} else {
	echo $url."<br/><br/>";
	echo $response;
	$response2=json_decode($response);
	$responseToWebClient='{"iTotalRecords":"'.$response2->{'total'}.'","iTotalDisplayRecords":"'.$response2->{'total'}.'",';
	$responseToWebClient.='"sEcho":"'.$_GET['sEcho'].'",';
	echo "<br/><br/>";
	print $responseToWebClient;
	//var_dump($response2['total']);
	echo "<br/><br/>";
	for ($i=0;$i<count($response2->{'items'});$i++) {
		var_dump($response2->{'items'}[$i]);
		echo "<br/><br/>";
	}
	//var_dump($response2['items']);
	
	
	
	
	/* parsing the JSON response to fill thiese fields :
		theResult.put("sEcho", getQuery().getValues("sEcho"));
		theResult.put("iTotalRecords", getTotal());
		theResult.put("iTotalDisplayRecords", getTotal());
		theResult.put("aaData", items);
	*/
	
}
?>