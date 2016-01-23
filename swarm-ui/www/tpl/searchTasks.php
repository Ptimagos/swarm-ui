<?PHP
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
$req="select * from ds_status;";
$resu = execRequete ($req, $connexion);
// Create array status
while ($res = ligneSuivante($resu)) {
	$status_id[$res->id]=$res->status;
	$status_name[$res->status]=$res->id;
}

// request bdd - select all status 
$req="select * from ds_actions;";
$resu = execRequete ($req, $connexion);
// Create array status
while ($res = ligneSuivante($resu)) {
	$actions_id[$res->id]=$res->name;
	$actions_name[$res->name]=$res->id;
}

// request bdd - search task not success, failed or canceled
$req="select action_id, id, status_id, host_id, job_id from ds_tasks where step_id = '0' and status_id not in ('".$status_name['success']."','".$status_name['failed']."','".$status_name['canceled']."') group by host_id order by id;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	if ( $res->status_id == $status_name['waiting'] ) {
		$date_tasks=date("Y-m-d H:i:s");
		switch ($actions_id[$res->action_id]) {
			case "add host" :
				print $date_tasks." - INFO - Launch Tasks with the job_id ".$res->job_id." on server ".$res->host_id." => add host\n";
				exec ('nohup /usr/bin/php5 '.$server['ansible']['script-tasks'].'addHostScript.php '.$res->host_id.' '.$job_id.' &');
				break;
			;;
			default :
				break;
			;;
		}
	}
}

// Disconnect to bdd :
decMysql($connexion);

?>