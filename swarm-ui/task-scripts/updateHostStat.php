<?PHP
//Inclusion du fichier de configuration
require "/opt/dockerstation/cfg/conf.php";

//Inclusion des differentes librairies
require "/opt/dockerstation/lib/fonctions.php";
require "/opt/dockerstation/lib/mysql.php";
require "/opt/dockerstation/lib/psql.php";
require "/opt/dockerstation/lib/ansible.php";


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

// request bdd - select all id, hostname hosts_client 
$req="select id, hostname from ds_hosts_client where id <> '0' and status_id <> '".$status_name['installing']."';";
$resu = execRequete ($req, $connexion);
// Create array status
while ($res = ligneSuivante($resu)) {
	$id = $res->id;
//	print "Valeur de id : ".$id."\n";
	$hostname = $res->hostname;
//	print "Valeur de hostname : ".$hostname."\n";
	$rand = rand();
	// Execution Ansible Check
	$fileout=$server['ansible']['checkhostdir'].$hostname.".json";
	$ansiCheck = ansibleCheck($server,$hostname,$fileout);
	$date_tasks=date("Ymd H:i:s");
	if ( filesize($fileout) != 0 ) {
		$req="update ds_hosts_client set status_id = '".$status_name['running']."', random = '".$rand."' where id = '".$id."';";
		$resu2 = execRequete ($req, $connexion);
		print $date_tasks." - INFO - Update status host_client for ".$hostname." success.\n";
	} else {
		$req="update ds_hosts_client set status_id = '".$status_name['offline']."', random = '".$rand."' where id = '".$id."';";
		$resu2 = execRequete ($req, $connexion);
		print $date_tasks." - ERROR - Update status host_client for ".$hostname." failed -- Server unreachable.\n";
	}
}

// Disconnect to bdd :
decMysql($connexion);
?>
