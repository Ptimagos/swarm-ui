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
$req="select id, status from ds_status;";
$resu = execRequete ($req, $connexion);

// Create array status
while ($res = ligneSuivante($resu)) {
	$status_id[$res->id]=$res->status;
	$status_name[$res->status]=$res->id;
}

// request bdd - select all actions 
$req="select id, name from ds_actions;";
$resu = execRequete ($req, $connexion);

// Create array status
while ($res = ligneSuivante($resu)) {
	$actions_id[$res->id]=$res->name;
	$actions_name[$res->name]=$res->id;
}

// request bdd - select all hosts
$req="select id, hostname from ds_hosts_client;";
$resu = execRequete ($req, $connexion);

// Create array status
while ($res = ligneSuivante($resu)) {
	$hosts_id[$res->id]=$res->hostname;
	$hosts_name[$res->hostname]=$res->id;
}

// ----- tasks Lists ----- //

// request bdd - Select all mains Tasks 
$num_main_tsk = 0;
$req="select * from ds_tasks where step_id = '0' order by id DESC LIMIT 20;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$main_tasks[$num_main_tsk]['id']=$res->id;
	$main_tasks[$res->id]['host_id']=$res->host_id;
	$main_tasks[$res->id]['job_id']=$res->job_id;
	$main_tasks[$res->id]['status_id']=$res->status_id;
	$main_tasks[$res->id]['instance_id']=$res->instance_id;
	$main_tasks[$res->id]['step_id']=$res->step_id;
	$main_tasks[$res->id]['action_id']=$res->action_id;
	$main_tasks[$res->id]['progress']=$res->progress;
	$main_tasks[$res->id]['start_date']=$res->start_date;
	$main_tasks[$res->id]['end_date']=$res->end_date;
	$sub_req="select * from ds_tasks where job_id = '".$res->job_id."' and step_id <> '0';";
	$sub_resu = execRequete ($sub_req, $connexion);
	while ($sub_res = ligneSuivante($sub_resu)) {
		$main_tasks[$res->id][$res->job_id]['step'][$sub_res->step_id]['action_id']=$sub_res->action_id;
		$main_tasks[$res->id][$res->job_id]['step'][$sub_res->step_id]['log_file']=$sub_res->log_file;
		$main_tasks[$res->id][$res->job_id]['step'][$sub_res->step_id]['progress']=$sub_res->progress;
		$main_tasks[$res->id][$res->job_id]['step'][$sub_res->step_id]['start_date']=$sub_res->start_date;
		$main_tasks[$res->id][$res->job_id]['step'][$sub_res->step_id]['end_date']=$sub_res->end_date;
		$main_tasks[$res->id][$res->job_id]['step'][$sub_res->step_id]['status_id']=$sub_res->status_id;
		$main_tasks[$res->id][$res->job_id]['step'][$sub_res->step_id]['logFile']=$sub_res->log_file;
	}
	$main_tasks[$res->id]['instance_name']="";
	if ($main_tasks[$res->id]['instance_id'] != 0) {
		$sub_req="select name from ds_instance where id = '".$main_tasks[$res->id]['instance_id']."';";
		$sub_resu = execRequete ($sub_req, $connexion);
		while ($sub_res = ligneSuivante($sub_resu)) {
			$main_tasks[$res->id]['instance_name']=$sub_res->name;
		}
	}
	$num_main_tsk++;
}

// Disconnect to bdd :
decMysql($connexion);
?>
