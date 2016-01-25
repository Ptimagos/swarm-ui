<?PHP
if ( !isset($server['projectName']) ) {
	session_start();
	//Inclusion du fichier de configuration
	require "../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../lib/fonctions.php";
	require "../../lib/mysql.php";
	require "../../lib/psql.php";
}
$nodes_down = 0;
$nodesDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
$nb_nodesDocker = count($nodesDocker);
for($x=0;$x<$nb_nodesDocker;$x++){
	$nodeDockerValue = base64_decode($nodesDocker[$x]['Value']);
	$valueDocker = json_decode($nodeDockerValue);
	switch ($valueDocker->status) {
			case "Healthy":
				continue;
				break;
			default:  
				$checkAlarmSet = getAlarmStatus($valueDocker->name,"nodes",$server);
				if ( $checkAlarmSet == 0 ) {
					$nodes_down++;
				}                                                                                                                                                                    
				break;
		}
}

if ( $nodes_down == 0 ) {
	print "<span id='main_icon_dash' class='glyphicon glyphicon-eye-open'><span class='wrapper-badge'></span></span>";
} else {
	print "<span id='main_icon_alarm' class='glyphicon glyphicon-eye-open'><span class='wrapper-badge wrapper-badge-danger'>".$nodes_down."</span></span>";
}
?>