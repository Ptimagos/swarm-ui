<?php
	session_start();
	//Inclusion du fichier de configuration
	require "../../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../../lib/fonctions.php";
	require "../../../lib/mysql.php";
	require "../../../lib/psql.php";
	
	// Define $username and $password
	$username=$_POST['username'];
	$password=$_POST['password'];
	
	// Get Password from Consul
	$resu = getUserPassword($username,$server);
	$rows = count($resu);
	if ($rows == 1) {
		if (password_verify($password, base64_decode($resu[0]['Value']))) {
			$_SESSION['login_user']=$username;
			$_SESSION['login_profile']= getUserInfo($username,"profile",$server);
			print "<div class='bg-success' style='text-align:center'>You are now connected</div>";
		} else {
			print "<div class='bg-danger' style='text-align:center'>Invalid Password, try again.</div>";
		}
	} else {
		print "<div class='bg-danger' style='text-align:center'>Invalid User, try again.</div>";
	}
?>