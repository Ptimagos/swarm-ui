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
$nodeInfo = base64_decode($node['responce'][0]['Value']);
$nodeInfoValue = json_decode($nodeInfo);
$logs = restRequestSSL("POST",$nodeInfoValue->url,"/containers/".$actionContainerID."/".$action);

include "../../checks/docker-daemon.php";
include "../../checks/docker-swarm-manager.php";
include "../../checks/docker-swarm-containers.php";

if ( $logs['info']['http_code'] >= 200 && $logs['info']['http_code'] <= 299 ){
	$status="success";
} else {
	$status="failed";
}

if ( $logs['responce'] == "" ){
  list($log,$log1) = explode(":", $logs['encode'], 3);
	$logs['responce'] = $log." ".$log1;
}

$endDate=time();

$containerSet = '{"nodeName":"'.$host.'","containerID":"'.$actionContainerID
				.'","action":"'.$action.'","stat":"'.$status
				.'","describe":"'.$describe.'","progress":"100","startDate":"'.$startDate
				.'","endDate":"'.$endDate.'","logs":"'.addslashes($logs['responce']).'"}';
createTask($containerSet,$jobId,$server);

print "<h4>".$describe." <b class='font-db-danger'>".$actionContainerID."</b> on host ".$host."</h4>";

$getProgressBar = getBarProgress($status,'100');
print $getProgressBar;
?>