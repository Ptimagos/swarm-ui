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
$host_id = $_GET['host_id'];

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

// request bdd - select all actions 
$req="select id, name from ds_actions;";
$resu = execRequete ($req, $connexion);

// Create array actions
while ($res = ligneSuivante($resu)) {
	$actions_id[$res->id]=$res->name;
	$actions_name[$res->name]=$res->id;
}

// request bdd - select all agent
$req="select id, version, active from ds_agent;";
$resu = execRequete ($req, $connexion);

// Create array actions
while ($res = ligneSuivante($resu)) {
	$agent_version[$res->id]['version']=$res->version;
	$agent_version[$res->id]['active']=$res->active;
}

// request bdd - select all instance
$req="select * from ds_instance;";
$resu = execRequete ($req, $connexion);

$formCreateCont_inputOption="";
// Create array actions
while ($res = ligneSuivante($resu)) {
	$instance_list[$res->id]['id']=$res->id;
	$instance_list[$res->id]['name']=$res->name;
	$instance_list[$res->id]['description']=$res->Description;
}

// request bdd - select all instance
$req="select * from ds_hosts_instances where status_id='".$status_name['stopped']."' and container_id = '0' and host_id = '".$host_id."';";
$resu = execRequete ($req, $connexion);

$formCreateCont_inputOption="";
// Create array actions
while ($res = ligneSuivante($resu)) {
	$formCreateCont_inputOption .= "<option value='".$res->instance_id."'>".$instance_list[$res->instance_id]['name']."</option>";
}


// request bdd - search active or wait task 'add host' for this host
$req="select count(*) nb_actions from ds_tasks where host_id = '".$host_id."' and "
	. "action_id = '".$actions_name['add host']."' and "
	. "status_id in ('".$status_name['starting']."', '".$status_name['waiting']."');";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$active_task=$res->nb_actions;
}

// ----- Host Docker ----- //

// request bdd - Select all host docker
$req="select * from ds_hosts_client where id = '".$host_id."';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts['hostname']=$res->hostname;
	$host_hosts['ip_address']=$res->ip_address;
	$host_hosts['os']=$res->os;
	$host_hosts['status_id']=$res->status_id;
	$host_hosts['comment']=$res->comment;
	$host_hosts['timestamp']=$res->hearthbeat;
}

// ----- Agents Docker ----- //

$current_time = time();

// request bdd - Select all hosts agents 
$req="select status_id, agent_id, hearthbeat from ds_hosts_agents where host_id = '".$host_id."';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	switch($res->status_id){
		case $status_name['running']:
			$up_time = $current_time - strtotime($res->hearthbeat);
			if ( $up_time < 600 ) {
				$agent_status="success";
			} else {
				$agent_status="danger";
			}
			break;
		case $status_name['stopped']:
				$agent_status="warning";
			break;
	}
	$agent_id=$res->agent_id;
}

// ----- Host Docker Instance ----- //

$num_cont = 0;
// request bdd - select all instance for this host
$req="select id, instance_id, status_id, container_id, options, hearthbeat from ds_hosts_instances where host_id = '".$host_id."';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$host_hosts[$num_cont]['id']=$res->id;
	$host_hosts[$num_cont]['instance_id']=$res->instance_id;
	switch($res->status_id){
		case $status_name['running']:
			$up_time = $current_time - strtotime($res->hearthbeat);
			if ( $up_time < 600 ) {
				$host_hosts[$num_cont]['status_id']=$status_name['running'];
			} else {
				$host_hosts[$num_cont]['status_id']=$status_name['unknown'];
			}
			break;
		case $status_name['stopped']:
				$host_hosts[$num_cont]['status_id']=$status_name['stopped'];
			break;
	}
	$host_hosts[$num_cont]['options']=$res->options;
	$host_hosts[$num_cont]['container_id']=$res->container_id;
	$num_cont++;
}

// Disconnect to bdd :
decMysql($connexion);
?>
