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

// ----- Agents Docker ----- //
$current_time = time();

// request bdd - Count agents unknown status
$agent_running=0;
$agent_unknown=0;
$agent_stopped=0;
$req="select * from ds_hosts_agents where set_alarm = '1';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	switch($res->status_id){
		case $status_name['running']:
			$up_time = $current_time - strtotime($res->hearthbeat);
			if ( $up_time > 600 ) {
				$agent_unknown++;
			}
			break;
		default:
			$agent_unknown++;
		;;
	}
}

$dashboard_agents['unknown']=$agent_unknown;

// Disconnect to bdd :
decMysql($connexion);

if ( $dashboard_agents['unknown'] == 0 ) {
	print "<span id='main_icon_dash' class='glyphicon glyphicon-eye-open'><span class='wrapper-badge'></span></span>";
} else {
	print "<span id='main_icon_alarm' class='glyphicon glyphicon-eye-open'><span class='wrapper-badge wrapper-badge-danger'>".$dashboard_agents['unknown']."</span></span>";
}
?>