<?PHP
	session_start();
	//Inclusion du fichier de configuration
	require "../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../lib/fonctions.php";
	require "../../lib/mysql.php";
	require "../../lib/psql.php";
	require "../../lib/ansible.php";


// Connection to bdd :
$connexion = conMysql ($server);

// request bdd - select all status 
$req="select * from ds_hosts_client;";
$resu = execRequete ($req, $connexion);
if ( !$resu ) {
	errorRequete();
} else {
	print "Valeur de resu : ".$resu."<br/>";
}

$req="select * from ds_status;";
$resu = execRequete ($req, $connexion);
// Create array status
while ($res = ligneSuivante($resu)) {
	$status_id[$res->id]=$res->status;
	$status_name[$res->status]=$res->id;
}

// ----- Host Docker ----- //


// Test DATE

date_default_timezone_set('CET');

print time() * 1000 . "<br/>\n";
print date("Y-m-d H:i:s") . "<br/><br/>\n\n";

$req="select action_id, id, status_id, host_id from ds_tasks where step_id = '0' and status_id not in ('".$status_name['success']."','".$status_name['failed']."','".$status_name['canceled']."') group by host_id order by id;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	print "Id : ".$res->id." -- Action : ".$res->action_id." -- Serveur : ".$res->host_id." -- Status : ".$res->status_id." (".$status_id[$res->status_id].")<br/>";
}

// Disconnect to bdd :
decMysql($connexion);

?>