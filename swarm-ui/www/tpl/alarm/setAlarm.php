<?php
	session_start();
	//Inclusion du fichier de configuration
	require "../../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../../lib/fonctions.php";
	require "../../../lib/mysql.php";
	require "../../../lib/psql.php";
	
	// Define id and alarm
	$id=$_POST['id'];
	$table=$_POST['table'];
	$setAlarm=$_POST['alarm'];
	$hearthbeat=$_POST['hearthbeat'];
	// Connexion a la base de donnees :
	$connexion = conMysql ($server);
	
	// SQL query to fetch information of registerd users and finds user match.
	$req="update ".$table." set set_alarm = '".$setAlarm."', hearthbeat = '".$hearthbeat."' where id = '".$id."';";
	$resu = execRequete ($req, $connexion);
	// Deconnexion de la base de donnees :
	decMysql($connexion);
?>