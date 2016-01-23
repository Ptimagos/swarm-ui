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

// ----- Host Docker ----- //

// request bdd - Select all host docker
$num_serv=1; 
$req="select * from ds_hosts_client where id <> '0';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts[$num_serv]['id']=$res->id;
	$host_hosts[$res->id]['hostname']=$res->hostname;
	$host_hosts[$res->id]['os']=$res->os;
	$host_hosts[$res->id]['status_id']=$res->status_id;
	$host_hosts[$res->id]['comment']=$res->comment;
	$host_hosts[$res->id]['timestamp']=$res->hearthbeat;
	$num_serv++;
}

$time_out = time() - 600;
// request bdd - Select all instances status 
$req="select host_id, status_id, count(instance_id) as nb_instance "
	. "from ds_hosts_instances where status_id = "
	. "'".$status_name['running']."' and container_id <> '0' "
	. "and UNIX_TIMESTAMP(hearthbeat) >= '".$time_out."' "
	. "group by host_id,status_id order by host_id;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts[$res->host_id]['instances'][$res->status_id]=$res->nb_instance;
}
$req="select host_id, status_id, count(instance_id) as nb_instance "
	. "from ds_hosts_instances where status_id = "
	. "'".$status_name['stopped']."' and container_id <> '0' "
	. "group by host_id,status_id order by host_id;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts[$res->host_id]['instances'][$res->status_id]=$res->nb_instance;
}
$req="select host_id, count(instance_id) as nb_instance "
	. "from ds_hosts_instances where status_id <> '".$status_name['stopped']."' "
	. "and UNIX_TIMESTAMP(hearthbeat) < '".$time_out."' "
	. "and container_id <> '0' group by host_id order by host_id;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts[$res->host_id]['instances']['unknown']=$res->nb_instance;
}

// request bdd - Select all agents status 
$req="select host_id, status_id from ds_hosts_agents "
	. "where status_id = '".$status_name['running']."' and "
	. "UNIX_TIMESTAMP(hearthbeat) >= '".$time_out."' order by host_id;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts[$res->host_id]['agent'][$res->status_id]=$res->status_id;
}
$req="select host_id, status_id from ds_hosts_agents "
	. "where status_id = '".$status_name['stopped']."' "
	. "order by host_id;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts[$res->host_id]['agent'][$res->status_id]=$res->status_id;
}
$req="select host_id from ds_hosts_agents where status_id <> '".$status_name['stopped']."' "
	. "and UNIX_TIMESTAMP(hearthbeat) < '".$time_out."' order by host_id;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts[$res->host_id]['agent']['unknown']="unknown";
}

// Disconnect to bdd :
decMysql($connexion);
?>