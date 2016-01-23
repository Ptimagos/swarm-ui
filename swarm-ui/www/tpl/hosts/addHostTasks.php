<?php
	session_start();
	//Inclusion du fichier de configuration
	require "../../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../../lib/fonctions.php";
	require "../../../lib/mysql.php";
	require "../../../lib/psql.php";
	
	// Define $hostname and $ipaddr
	$hostname=$_POST['hostname'];
	$ipaddr=$_POST['ipaddr'];
	$job_id=$_POST['job_id'];
	
	// Connexion a la base de donnees :
	$connexion = conMysql ($server);
	// To protect MySQL injection for Security purpose
	$hostname = stripslashes($hostname);
	$ipaddr = stripslashes($ipaddr);
	$hostname = mysql_real_escape_string($hostname);
	$ipaddr = mysql_real_escape_string($ipaddr);
	
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
	
	// SQL Request ID New host
	$req="select id from ds_hosts_client where hostname = '".$hostname."' and ip_address = '".$ipaddr."';";
	$resu = execRequete ($req, $connexion);
	while ($res = ligneSuivante($resu)) {
		$add_host_id=$res->id;
	}
	
	// SQL Create Tasks list
	$req="insert into ds_tasks (host_id, job_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$add_host_id."','".$job_id."','0','".$actions_name['add host']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	$req="insert into ds_tasks (host_id, job_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$add_host_id."','".$job_id."','1','".$actions_name['Check ansible access']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	$req="insert into ds_tasks (host_id, job_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$add_host_id."','".$job_id."','2','".$actions_name['Install Docker package']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	$req="insert into ds_tasks (host_id, job_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$add_host_id."','".$job_id."','3','".$actions_name['Install DS Agent']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	$req="insert into ds_tasks (host_id, job_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$add_host_id."','".$job_id."','4','".$actions_name['Update host information']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	// Deconnexion de la base de donnees :
	decMysql($connexion);
	print "<h4>Server registration added to tasks list</h4>";
	
?>