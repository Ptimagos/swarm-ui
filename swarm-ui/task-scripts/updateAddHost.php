<?php
	session_start();
	//Inclusion du fichier de configuration
	require "../../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../../lib/fonctions.php";
	require "../../../lib/mysql.php";
	require "../../../lib/psql.php";
	
	date_default_timezone_set('CET');
	
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
	
	// SQL Update Task Check ansible access to start
	$date_jobs=date("Y-m-d H:i:s");
	$req="update ds_tasks set status_id = '8', start_date = '".$date_jobs."' where job_id = '".$job_id."' and action_id = '".$actions_name['Update host information']."'";
	$resu = execRequete ($req, $connexion);
	
	// Stoppe pour 10 secondes
	sleep(10);
	
	// SQL Update Task Check ansible access to finish
	$date_jobs = date("Y-m-d H:i:s");
	$timestamp = time();
	$timestamp = $timestamp * 1000;
	$req="update ds_tasks set status_id = '9', end_date = '".$date_jobs."' where job_id = '".$job_id."' and action_id = '".$actions_name['Update host information']."'";
	$resu = execRequete ($req, $connexion);
	$req="update ds_tasks set status_id = '9', end_date = '".$date_jobs."' where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
	$resu = execRequete ($req, $connexion);
	$req="update ds_hosts_client set status_id = '1' where hostname = '".$hostname."' and ip_address = '".$ipaddr."';";
	$resu = execRequete ($req, $connexion);
	// Deconnexion de la base de donnees :
	decMysql($connexion);
	
	print "<div class='bg-success' style='text-align:center'>Update Host done</div>";
?>