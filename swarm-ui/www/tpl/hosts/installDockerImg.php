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
	$image=$_POST['image'];
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
	
	$addImage = explode("|", $image);
	// request bdd - search existing entry for this image
	$req="select id, name from ds_instance where name='".$addImage[0]."';";
	$resu = execRequete ($req, $connexion);
	$numRow = nbLigne($resu);
	if ( $numRow ==0 ){
		// request bdd - insert new image
		$req="insert into ds_instance (name, description) values "
			. "('".$addImage[0]."','".$addImage[1]."');";
		$resu = execRequete ($req, $connexion);

		// request bdd - getID 
		$req="select id from ds_instance where name='".$addImage[0]."';";
		$resu = execRequete ($req, $connexion);
		while ($res = ligneSuivante($resu)) {
			$instance_id=$res->id;
		}
	} else {
		$resu = execRequete ($req, $connexion);
		while ($res = ligneSuivante($resu)) {
			$instance_id=$res->id;
		}
	}
	
	// SQL Create Tasks list
	$req="insert into ds_tasks (host_id, job_id, instance_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$host_id."','".$job_id."','".$instance_id."','0','".$actions_name['Pull Docker Image']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	$req="insert into ds_tasks (host_id, job_id, instance_id, step_id, action_id, status_id, user_id, progress) "
		. "values ('".$host_id."','".$job_id."','".$instance_id."','1','".$actions_name['Pull Docker Image']."', '3', '".$_SESSION['login_id']."', '0');";
	$resu = execRequete ($req, $connexion);
	// Deconnexion de la base de donnees :
	decMysql($connexion);
	print "<h4>Pull Docker Image ".$addImage[0]." added to tasks list</h4>";
	
?>