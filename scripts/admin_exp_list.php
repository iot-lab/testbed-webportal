<?php

session_start();

if(!$_SESSION['is_auth'] || ($_SESSION['login'] == "" || $_SESSION['password'] == "" || !$_SESSION['is_admin']))
{
    header("HTTP/1.0 404 Not Found");
    exit();
}

$request="";
if(isset($_GET['user'])) $request="&user=".$_GET['user'];


/* Get total */
$url = "https://localhost/rest/admin/experiments?total".$request;

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
	$response2=json_decode($response);
	$total=$response2->{'upcoming'}+$response2->{'terminated'}+$response2->{'running'};
}

$state = "Terminated,Error,Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended";
if(isset($_GET['sSearch']) && $_GET['sSearch']!="All" && $_GET['sSearch']!="") {
	if($_GET['sSearch']== "Running") {
		$state = "Running,Finishing,Resuming,toError";
		$total = $response2->{'running'};
	} else if ($_GET['sSearch']== "Upcoming") {
		$state = "Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended";
		$total = $response2->{'upcoming'};
	} else if ($_GET['sSearch']== "Terminated") {
		$state = "Terminated,Error";
		$total = $response2->{'terminated'};
	}
}



$offset = $total - $_GET['iDisplayLength'] - $_GET['iDisplayStart'];
if($offset<0) $offset = 0;
$limit = $_GET['iDisplayLength'];
if($total<$_GET['iDisplayLength']+$_GET['iDisplayStart']) $limit = $total % $_GET['iDisplayLength'];



$url = "https://localhost/rest/admin/experiments?state=".$state."&limit=".$limit."&offset=".$offset.$request;
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


if($code != 200) {
    header("HTTP/1.0 404 Not Found");
    exit();
} else {
	$response2=json_decode($response);
    $responseToWebClient='{"iTotalRecords":"'.$response2->{'total'}.'","iTotalDisplayRecords":"'.$total.'",
        "sEcho":"'.$_GET['sEcho'].'","items":'.json_encode(array_reverse($response2->{'items'})).'}';
	echo $responseToWebClient;
}
?>
