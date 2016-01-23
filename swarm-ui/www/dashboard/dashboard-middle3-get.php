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

// request bdd - select all instance
$req="select id, name from ds_instance;";
$resu = execRequete ($req, $connexion);

// Create array status
while ($res = ligneSuivante($resu)) {
	$instances[$res->id]['name']=$res->name;
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

// request bdd - Select all hosts Container
$num_cont=0;
$req="select * from ds_hosts_instances where container_id <> '0';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$all_host_instances[$num_cont]['id']=$res->id;
	$all_host_instances[$res->id]['host_id']=$res->host_id;
	$all_host_instances[$res->id]['instance_id']=$res->instance_id;
	$all_host_instances[$res->id]['container_id']=$res->container_id;
	switch($res->status_id){
		case $status_name['running']:
			$up_time = $current_time - strtotime($res->hearthbeat);
			if ( $up_time < 600 ) {
				$instance_status=$status_name['running'];
			} else {
				$instance_status=$status_name['unknown'];
			}
			break;
		;;
		case $status_name['stopped']:
				$instance_status=$status_name['stopped'];
			break;
		;;
	}
	$all_host_instances[$res->id]['status_id']=$instance_status;
	$all_host_instances[$res->id]['set_alarm']=$res->set_alarm;
	$all_host_instances[$res->id]['hearthbeat']=$res->hearthbeat;
	$num_cont++;
}

// Disconnect to bdd :
decMysql($connexion);
?>