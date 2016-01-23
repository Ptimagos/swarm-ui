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

// request bdd - select all agents 
$req="select * from ds_agent;";
$resu = execRequete ($req, $connexion);

// Create array status
while ($res = ligneSuivante($resu)) {
	$agents[$res->id]['name']=$res->name;
	$agents[$res->id]['version']=$res->version;
}

// ----- Host Docker ----- //

// request bdd - Select all host docker 
$req="select * from ds_hosts_client;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$all_hosts[$res->id]['hostname']=$res->hostname;
	$all_hosts[$res->id]['status_id']=$res->status_id;
}

// ----- Agents Docker ----- //

$current_time = time();

// request bdd - Select all hosts agents 
$num_agent=0;
$req="select * from ds_hosts_agents;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$all_host_agents[$num_agent]['id']=$res->id;
	$all_host_agents[$res->id]['host_id']=$res->host_id;
	$all_host_agents[$res->id]['agent_id']=$res->agent_id;
	switch($res->status_id){
		case $status_name['running']:
			$up_time = $current_time - strtotime($res->hearthbeat);
			if ( $up_time < 600 ) {
				$agent_status=$status_name['running'];
			} else {
				$agent_status=$status_name['unknown'];
			}
			break;
		;;
		case $status_name['stopped']:
				$agent_status=$status_name['stopped'];
			break;
		;;
	}
	$all_host_agents[$res->id]['status_id']=$agent_status;
	$all_host_agents[$res->id]['set_alarm']=$res->set_alarm;
	$all_host_agents[$res->id]['hearthbeat']=$res->hearthbeat;
	$num_agent++;
}

// Disconnect to bdd :
decMysql($connexion);
?>