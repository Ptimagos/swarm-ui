<?PHP
if ( !isset($server['projectName']) ) {
	session_start();
	if ( !isset($_SESSION['login_user']) ) {
		header('Location: /');
  		exit();
	}
	//Inclusion du fichier de configuration
	require "../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../lib/fonctions.php";
	require "../../lib/mysql.php";
	require "../../lib/psql.php";
}
$containers_down = 0;
$containersDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
$nb_containersDocker = count($containersDocker['responce']);
for($x=0;$x<$nb_containersDocker;$x++){
	$containerDockerValue = base64_decode($containersDocker['responce'][$x]['Value']);
	$valueDocker = json_decode($containerDockerValue);
	list($statusCont, $uptime) = explode(" ", $valueDocker->status, 2);
	switch ($statusCont) {
			case "Up":
				continue;
				break;
			default:  
				$checkAlarmSet = getAlarmStatus($valueDocker->id,"containers",$server);
				if ( $checkAlarmSet == 0 ) {
					$containers_down++;
				}                                                                                                                                                                    
				break;
		}
}

if ( $containers_down == 0 ) {
	print "<span id='main_icon_dash' class='glyphicon glyphicon-oil'><span class='wrapper-badge wrapper-badge-danger'></span></span>";
} else {
	print "<span id='main_icon_alarm' class='glyphicon glyphicon-oil'><span class='wrapper-badge wrapper-badge-danger'>".$containers_down."</span></span>";
}
?>