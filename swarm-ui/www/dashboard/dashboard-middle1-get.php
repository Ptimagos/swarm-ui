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
$nb_nodes = count($nodes) - 1;
$dashboard_hosts['total']=$nb_nodes;
$nb_nodes_running=0;
$nb_nodes_down=0;
for($x = 0; $x < $nb_nodes; $x++){
  $nodeValue = base64_decode($nodes[$x]['Value']);
  $value = json_decode($nodeValue);
  if (isset($value->status) && $value->status == "Healthy" ){
    $nb_nodes_running++;
  } else {
    $nb_nodes_down++;
  }  
}
$dashboard_hosts['running']=$nb_nodes_running;
$dashboard_hosts['offline']=$nb_nodes - $nb_nodes_running;

// ----- Swarm manager ----- //

$nodes = restRequest("GET",$server['consul']['url'],"/v1/catalog/service/swarm-manager");
$nb_nodes = count($nodes);
$dashboard_agents['total']=$nb_nodes;
$nodes_running = restRequest("GET",$server['consul']['url'],"/v1/health/service/swarm-manager","passing");                           
$nb_nodes_running = count($nodes_running);
$dashboard_agents['running']=$nb_nodes_running;
$dashboard_agents['stopped']=$nb_nodes - $nb_nodes_running;
// ----- Instance Docker ----- //

$swarm_p = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm/leader");
$swarmPrimary = base64_decode($swarm_p[0]['Value']);
$instances = restRequest("GET","https://".$swarmPrimary,"/containers/json");
$nb_instances = count($instances);
$dashboard_instances['total']=$nb_instances;
$dashboard_instances['running']=0;
$dashboard_instances['stopped']=0;

// Disconnect to bdd :
decMysql($connexion);
?>