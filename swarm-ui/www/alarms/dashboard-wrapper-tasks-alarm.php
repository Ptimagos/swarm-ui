<?PHP
if ( !isset($server['projectName']) ) {
	session_start();
	if ( !isset($_SESSION['login_user']) ) {
		header('Location: /');
  		exit();
	}
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

// ----- Tasks List ----- //

// request bdd - Number of agent docker 
$req="select count(*) as nb_tasks from ds_tasks where step_id = '0' and "
	. "status_id not in ('".$status_name['success']."','".$status_name['failed']."','".$status_name['canceled']."');";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$tasks_list['nb_tasks']=$res->nb_tasks;
}

// Disconnect to bdd :
decMysql($connexion);
if ( $tasks_list['nb_tasks'] == 0 ) {
	print "<span id='main_icon_dash' class='glyphicon glyphicon-list-alt'><span class='wrapper-badge'></span></span>";
} else {
	print "<span id='main_icon_alarm' class='glyphicon glyphicon-list-alt'><span class='wrapper-badge wrapper-badge-info'>".$tasks_list['nb_tasks']."</span></span>";
}
?>