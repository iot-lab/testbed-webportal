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
	$response2=json_decode($response);
	
	$responseToWebClient='{"iTotalRecords":"'.$response2->{'total'}.'","iTotalDisplayRecords":"'.$response2->{'total'}.'",';
	$responseToWebClient.='"sEcho":"'.$_GET['sEcho'].'","aaData":[';
	
	$nb_items=count($response2->{'items'});
	
	for ($i=0;$i<$nb_items;$i++) {
		$item = $response2->{'items'}[$i];
		$buttons='<a href=\"#\" class=\"btn btn-valid\" data=\"'.$item->{'id'}.'\" onClick=\"detailsExp('.$item->{'id'}.')\">Details</a>';
		/*if(strcmp($item->{'state'},"Running")==0
			|| strcmp($item->{'state'},"Finishing")==0
			|| strcmp($item->{'state'},"Resuming")==0
			|| strcmp($item->{'state'},"toError")==0) {
			$buttons.='<a href=\"#\" class=\"btn btn-danger\" data=\"'.$item->{'id'}.'\" onClick=\"stopExp('.$item->{'id'}.')\">Stop</a>';
		} else if(strcmp($item->{'state'},"Launching")==0
			|| strcmp($item->{'state'},"Hold")==0
			|| strcmp($item->{'state'},"toLaunch")==0
			|| strcmp($item->{'state'},"toAckReservation")==0
			|| strcmp($item->{'state'},"Waiting")==0
			|| strcmp($item->{'state'},"Suspended")==0) {
			$buttons.='<a href=\"#\" class=\"btn btn-danger\" data=\"'.$item->{'id'}.'\" onClick=\"cancelExp('.$item->{'id'}.')\">Cancel</a>';
		}*/

		$responseToWebClient.='{"0":"'.$item->{'id'}.'","1":"'.$item->{'name'}.'","2":"'.$item->{'date'}.'","3":"'.$item->{'duration'}.'","4":"'.$item->{'nb_resources'}.'","5":"'.$item->{'state'}.'","6":"'.$buttons.'"}';
		if($i!=$nb_items-1)$responseToWebClient.=",";
	}
	$responseToWebClient.="]}";
	echo $responseToWebClient;
}
?>