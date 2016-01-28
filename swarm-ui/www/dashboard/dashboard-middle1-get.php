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

$current_time = time();

// ----- Host Docker ----- //

$nodes = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
$nodesStatus = getNumberUpOrDown($nodes,"Healthy");
$dashboard_hosts['total'] = $nodesStatus['total'];
$dashboard_hosts['running'] = $nodesStatus['running'];
$dashboard_hosts['offline'] = $nodesStatus['down'];

// ----- Swarm manager ----- //

$swarmsManger = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/swarm-manager","?recurse");
$swarmsManagerStatus = getNumberUpOrDown($swarmsManger,"Up");
$dashboard_managers['total'] = $swarmsManagerStatus['total'];
$dashboard_managers['running'] = $swarmsManagerStatus['running'];
$dashboard_managers['stopped'] = $swarmsManagerStatus['down'];

// ----- Swarm Agent ----- //

$swarmsAgent = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/swarm-agent","?recurse");
$swarmsAgentStatus = getNumberUpOrDown($swarmsAgent,"Up");
$dashboard_agents['total'] = $swarmsAgentStatus['total'];
$dashboard_agents['running'] = $swarmsAgentStatus['running'];
$dashboard_agents['stopped'] = $swarmsAgentStatus['down'];

// ----- Containers Docker ----- //
$containers = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
$containersStatus = getNumberUpOrDown($containers,"Up");
$dashboard_containers['total'] = $containersStatus['total'];
$dashboard_containers['running'] = $containersStatus['running'];
$dashboard_containers['stopped'] = $containersStatus['down'];

?>