<?php

session_start();

if(!$_SESSION['is_auth'] || ($_SESSION['login'] == "" || $_SESSION['password'] == ""))
{
    header("HTTP/1.0 404 Not Found");
    exit();
}

$request="";
if(isset($_GET['user'])) $request="&user=".$_GET['user'];


/* Get total */
$url = "https://localhost/rest/admin/experiments?total".$request;

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

if($code == 200) {
	$response2=json_decode($response);
	$total=$response2->{'upcoming'}+$response2->{'terminated'}+$response2->{'running'};
}

$offset = $total - $_GET['iDisplayLength'] - $_GET['iDisplayStart'];
if($offset<0) $offset = 0;
$limit = $_GET['iDisplayLength'];
if($total<$_GET['iDisplayLength']+$_GET['iDisplayStart']) $limit = $total % $_GET['iDisplayLength'];


$url = "https://localhost/rest/admin/experiments?state=Terminated,Error,Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended&limit=".$limit."&offset=".$offset.$request;
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
    $responseToWebClient='{"iTotalRecords":"'.$response2->{'total'}.'","iTotalDisplayRecords":"'.$response2->{'total'}.'",
        "sEcho":"'.$_GET['sEcho'].'","items":'.json_encode(array_reverse($response2->{'items'})).'}';
	echo $responseToWebClient;
	/*
	$responseToWebClient='{"iTotalRecords":"'.$response2->{'total'}.'","iTotalDisplayRecords":"'.$response2->{'total'}.'",';
	$responseToWebClient.='"sEcho":"'.$_GET['sEcho'].'","aaData":[';
	
	$nb_items=count($response2->{'items'});
	
	for ($i=0;$i<$nb_items;$i++) {
		$item = $response2->{'items'}[$nb_items-$i-1];
		$buttons='<a href=\"admin_details_exp.php?id='.$item->{'id'}.'\" class=\"btn btn-valid\" data=\"'.$item->{'id'}.'\" >Details</a>';
		if(strcasecmp($item->{'state'},"Running")==0
			|| strcasecmp($item->{'state'},"Finishing")==0
			|| strcasecmp($item->{'state'},"Resuming")==0
			|| strcasecmp($item->{'state'},"toError")==0) {
				$buttons.='&nbsp;<a href=\"#\" class=\"btn btn-danger\" data=\"'.$item->{'id'}.'\" onClick=\"stopExp('.$item->{'id'}.')\">Stop</a>';
		} else if(strcasecmp($item->{'state'},"Launching")==0
			|| strcasecmp($item->{'state'},"Hold")==0
			|| strcasecmp($item->{'state'},"toLaunch")==0
			|| strcasecmp($item->{'state'},"toAckReservation")==0
			|| strcasecmp($item->{'state'},"Waiting")==0
			|| strcasecmp($item->{'state'},"Suspended")==0) {
				$buttons.='&nbsp;<a href=\"#\" class=\"btn btn-danger\" data=\"'.$item->{'id'}.'\" onClick=\"cancelExp('.$item->{'id'}.')\">Cancel</a>';
		} else {
			$buttons.='&nbsp;<a href=\"#\" class=\"btn btn-primary\" data=\"'.$item->{'id'}.'\" onClick=\"reloadExp('.$item->{'id'}.')\">Reload</a>';
		}
		$date = date("Y/m/d H:i O", $item->{'date'});
		if($date=="") $date="ok";

		$responseToWebClient.='{"0":"'.$item->{'id'}.'","1":"'.$item->{'owner'}.'","2":"'.$item->{'name'}.'","3":"'.$date.'","4":"'.$item->{'duration'}.'","5":"'.$item->{'nb_resources'}.'","6":"'.$item->{'state'}.'","7":"'.$buttons.'"}';
		if($i!=$nb_items-1)$responseToWebClient.=",";
	}
	$responseToWebClient.="]}";
	echo $responseToWebClient;*/
}
?>
