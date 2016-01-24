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

// Connection to bdd :
$connexion = conMysql ($server);

// request bdd - select all status 
$req="select * from ds_status;";
$resu = execRequete ($req, $connexion);

// Create array status
while ($res = ligneSuivante($resu)) {
	$status_id[$res->id]=$res->status;
	$status_name[$res->status]=$res->id;
}

$current_time = time();

// ----- Host Docker ----- //

$nodes = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
$nodesStatus = getNumberUpOrDown($nodes,"Healthy");
$dashboard_hosts['total'] = $nodesStatus['total'];
$dashboard_hosts['running'] = $nodesStatus['running'];
$dashboard_hosts['offline'] = $nodesStatus['down'];

// ----- Swarm manager ----- //

$swarms = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/swarm-manager","?recurse");
$swarmsStatus = getNumberUpOrDown($swarms,"Up");
$dashboard_managers['total'] = $swarmsStatus['total'];
$dashboard_managers['running'] = $swarmsStatus['running'];
$dashboard_managers['stopped'] = $swarmsStatus['down'];

// ----- Containers Docker ----- //
$containers = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
$containersStatus = getNumberUpOrDown($containers,"Up");
$dashboard_containers['total'] = $containersStatus['total'];
$dashboard_containers['running'] = $containersStatus['running'];
$dashboard_containers['stopped'] = $containersStatus['down'];

// Disconnect to bdd :
decMysql($connexion);
?>