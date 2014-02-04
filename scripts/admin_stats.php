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


if($code != 200) {

	header("HTTP/1.0 404 Not Found");
	exit();
}
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

ksort($countries);

$json_user = array("nb_users" => $total_users, "admin" => $admin, "pending" => $pending, "countries" => $countries);


/* ************************************************* */
/* request pour les exps                             */
/* ************************************************* */

$headers = array();

$url = 'https://localhost/rest/admin/experiments?total';
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

if($code != 200) {
	header("HTTP/1.0 404 Not Found");
	exit();
}

$header_size = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);
$json_exps=json_decode($body);


/* ************************************************* */
/* request pour les nodes                            */
/* ************************************************* */

$headers = array();

$url = 'https://localhost/rest/admin/resourcesproperties';
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

if($code != 200) {
	header("HTTP/1.0 404 Not Found");
	exit();
}

$header_size = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);
$json_nodes=json_decode($body,true);

//$nb_nodes = count($json_nodes->items);
$nodes_ids = array_keys($json_nodes);
$nb_nodes = count($nodes_ids);

$nodes_states = array("Alive" => 0, "Unavailable" => 0, "Busy" => 0);
$nodes_states_by_sites = array();
foreach ($nodes_ids as $node_id) {

	$node=$json_nodes[$node_id];

	if(!array_key_exists($node['site'],$nodes_states_by_sites)) {
		$nodes_states_by_sites[$node['site']] = array("Alive" => 0, "Unavailable" => 0, "Busy" => 0);
	}


	if($node['state']=="Alive") {
		$nodes_states["Alive"]++;
		$nodes_states_by_sites[$node['site']]["Alive"]++;
	} else {
		$nodes_states["Unavailable"]++;
		$nodes_states_by_sites[$node['site']]["Unavailable"]++;
	}
}

ksort($nodes_states_by_sites);

$nodes_states["BySite"]=$nodes_states_by_sites;
//echo json_encode($nodes_states);

// busy nodes and running/upcoming exps
$headers = array();

$url = 'https://localhost/rest/admin/experiments?state=Running,Upcoming,Launching,Waiting';
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

if($code != 200) {
	header("HTTP/1.0 404 Not Found");
	exit();
}

$header_size = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);
$json_exps2=json_decode($body);


$json_exps_run = array();
$json_exps_upco = array();
foreach($json_exps2->items as $exp) {
	if($exp->state=="Running") {
		// nodes stuff
		foreach($exp->resources as $node) {
			$infos=explode('.',$node);
			$length=count($infos);
			$nodes_states["Alive"]--;
			$nodes_states["Busy"]++;
			$nodes_states["BySite"][$infos[$length-3]]["Alive"]--;
			$nodes_states["BySite"][$infos[$length-3]]["Busy"]++;
		}
		// exps stuff
		$json_exps_run[$exp->id]=$exp->owner;
	} else {
		$json_exps_upco[$exp->id]=$exp->owner;
	}
}
$json_nodes=$nodes_states;

if(count($json_exps_run)<1) $json_exps_run=0;
if(count($json_exps_upco)<1) $json_exps_upco=0;

$json_exps666 = array ("running" => $json_exps->running, "upcoming" => $json_exps->upcoming, "terminated" => $json_exps->terminated, "infosRunning" => $json_exps_run, "infosUpco" => $json_exps_upco);
$json_exps = $json_exps666;

/*
 echo json_encode($json_users)."<br/><hr/>";
echo json_encode($json_exps)."<br/><hr/>";
echo json_encode($json_nodes)."<br/><hr/>";
*/
// a la fin, on envoie
$json_output = array("users" => $json_user, "exps" => $json_exps, "nodes" => $json_nodes);
echo json_encode($json_output);


?>
