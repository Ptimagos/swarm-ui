<?php
	session_start();
	//Inclusion du fichier de configuration
	require "../../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../../lib/fonctions.php";
	require "../../../lib/mysql.php";
	require "../../../lib/psql.php";
	
	// Define id and alarm
	$name=$_POST['name'];
	$table=$_POST['table'];
	$setAlarm=$_POST['alarm'];
	
	if ( $setAlarm == 0 ){
  	echo unSetAlarm($name,$table,$server);
	} else {
		echo setAlarm($name,$table,$server);
	}
?>