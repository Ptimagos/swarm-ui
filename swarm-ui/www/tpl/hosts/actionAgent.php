<?PHP
	session_start();
	//Inclusion du fichier de configuration
	require "../../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../../lib/fonctions.php";
	require "../../../lib/mysql.php";
	require "../../../lib/psql.php";
	
	// Define $hostname and $ipaddr
	$host_id=$_POST['hostId'];
	$action=$_POST['actionCont'];
	$job_id=rand();
	
	// Connexion a la base de donnees :
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
	// Create array status
	while ($res = ligneSuivante($resu)) {
		$actions_id[$res->id]=$res->name;
		$actions_name[$res->name]=$res->id;
	}

	switch ($action) {
		case 'stop':
			$action_id ="Stop agent";
			break;
		case 'start':
			$action_id ="Start agent";
			break;
	}

	// SQL Create Tasks list
	$req="insert into ds_tasks (host_id, job_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$host_id."','".$job_id."','0','".$actions_name[$action_id]."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	$req="insert into ds_tasks (host_id, job_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$host_id."','".$job_id."','1','".$actions_name[$action_id]."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);

	// Deconnexion de la base de donnees :
	decMysql($connexion);
	print "<h4>Agent ".$action." added to task lists</h4>";
?>