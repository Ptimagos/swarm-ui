<?PHP
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
$host=$_POST['hostId'];
$actionContainerID=$_POST['containerId'];
$action=$_POST['actionCont'];
$describe=$_POST['describeAction'];
$jobId=time()."-".rand();
$startDate=time();

$node = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes/".$host);
$nodeInfo = base64_decode($node[0]['Value']);
$nodeInfoValue = json_decode($nodeInfo);
$checkStatBefor = restRequestSSL("GET",$nodeInfoValue->url,"/containers/".$actionContainerID."/json");
$logs = restRequestSSL("POST",$nodeInfoValue->url,"/containers/".$actionContainerID."/".$action);
$checkStatAfter = restRequestSSL("GET",$nodeInfoValue->url,"/containers/".$actionContainerID."/json");

include "../../checks/docker-daemon.php";
include "../../checks/docker-swarm-manager.php";
include "../../checks/docker-swarm-containers.php";

if ( $checkStatBefor['State']['Status'] != $checkStatAfter['State']['Status'] ){
	$status="success";
} else {
	$status="danger";
}

if ( $logs == "" ){
	$logs = "No logs ...";
}

$endDate=time();

$containerSet = '{"nodeName":"'.$host.'","containerID":"'.$actionContainerID
				.'","action":"'.$action.'","stat":"'.$status
				.'","describe":"'.$describe.'","progress":"100","startDate":"'.$startDate
				.'","endDate":"'.$endDate.'","logs":"'.$logs.'"}';
createTask($host,$containerID,$containerSet,$jobId,$server);

print "<h4>".$describe." <b class='font-db-danger'>".$actionContainerID."</b> on host ".$host."</h4>";

$getProgressBar = getBarProgress($status,'100');
print $getProgressBar;
?>