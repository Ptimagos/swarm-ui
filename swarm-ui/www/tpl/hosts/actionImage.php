<?php
session_start();
if ( !isset($_SESSION['login_user']) ) {
	header('Location: /');
	exit();
}
//Inclusion du fichier de configuration
require "../../../cfg/conf.php";

//Inclusion des differentes librairies
require "../../../lib/fonctions.php";
require "../../../lib/mysql.php";
require "../../../lib/psql.php";

	
// Define $hostname and $ipaddr
$host = $_POST['hostId'];
$image = $_POST['image'];
$url = $_POST['url'];
$action=$_POST['actionImg'];
$describe=$_POST['describeAction'];
$jobId=time()."-".rand();
$startDate=time();

$logs = restRequestSSL("POST",$url,"/images/create","?fromImage=".$image.":latest");

sleep(2);

include "../../checks/docker-daemon.php";
include "../../checks/docker-swarm-manager.php";
include "../../checks/docker-swarm-containers.php";

if ( $logs['info']['http_code'] >= 200 && $logs['info']['http_code'] <= 299 ){
  $status="success";
} else {
  $status="danger";
}

if ( $logs['responce'] == "" ){
	$logs['responce'] = "No logs ...";
}

$endDate=time();

$imageSet = '{"nodeName":"'.$host.'","image":"'.$image
				.'","action":"'.$action.'","stat":"'.$status
				.'","describe":"'.$describe.'","progress":"100","startDate":"'.$startDate
				.'","endDate":"'.$endDate.'","logs":"'.$logs['responce'].'"}';
createTask($imageSet,$jobId,$server);

print "<h4>".$describe." <b class='font-db-danger'>".$image."</b> on host ".$host."</h4>";

$getProgressBar = getBarProgress($status,'100');
print $getProgressBar;

print "<pre>";
print_r($logs);
print "</pre>";
?>