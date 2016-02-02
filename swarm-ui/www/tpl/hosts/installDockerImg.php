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
$host_id = $_POST['host_id'];
$image = $_POST['image'];
$url = $_POST['url'];
$action=$_POST['actionImg'];
$describe=$_POST['describeAction'];
$jobId=time()."-".rand();
$startDate=time();

$logs = restRequestSSL("POST",$url,"/images/create","?fromImage=".$image);

sleep(2);

include "../../checks/docker-daemon.php";
include "../../checks/docker-swarm-manager.php";
include "../../checks/docker-swarm-containers.php";

$status="success";

if ( $logs == "" ){
	$logs = "No logs ...";
}

$endDate=time();

$imageSet = '{"nodeName":"'.$host.'","image":"'.$image
				.'","action":"'.$action.'","stat":"'.$status
				.'","describe":"'.$describe.'","progress":"100","startDate":"'.$startDate
				.'","endDate":"'.$endDate.'","logs":"'.$logs.'"}';
createTask($containerSet,$jobId,$server);

print "<h4>".$describe." <b class='font-db-danger'>".$actionContainerID."</b> on host ".$host."</h4>";

$getProgressBar = getBarProgress($status,'100');
print $getProgressBar;
	
?>