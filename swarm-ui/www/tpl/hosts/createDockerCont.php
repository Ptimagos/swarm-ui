<?php
	session_start();
	//Inclusion du fichier de configuration
	require "../../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../../lib/fonctions.php";
	require "../../../lib/mysql.php";
	require "../../../lib/psql.php";
	
	// Define $hostname and $ipaddr
	$host_id=$_POST['host_id'];
	$image_id=$_POST['image'];
	$options=$_POST['options'];
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

	// request bdd - seletc name instance
	$req="select name from ds_instance where id='".$image_id."';";
	$resu = execRequete ($req, $connexion);
	while ($res = ligneSuivante($resu)) {
		$instance_name=$res->name;
	}

	
	// SQL Create Tasks list
	$req="insert into ds_tasks (host_id, job_id, instance_id, options, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$host_id."','".$job_id."','".$image_id."','".$options."','0','".$actions_name['Create Container']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	$req="insert into ds_tasks (host_id, job_id, instance_id, options, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$host_id."','".$job_id."','".$image_id."','".$options."','1','".$actions_name['Create Container']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	// Deconnexion de la base de donnees :
	decMysql($connexion);
	print "<h4>Create Container ".$instance_name." added to tasks list</h4>";
	
?>