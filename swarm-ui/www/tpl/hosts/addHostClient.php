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
	
	// check variables
	$check = "in";
	if ( $hostname == "") {
		$check = "out";
		print "<div class='bg-danger' style='text-align:center'>Invalide Hostname</div>";
	}
	if ( $ipaddr == "") {
		$check = "out";
		print "<div class='bg-danger' style='text-align:center'>Invalide Ip Address</div>";
	}
	
	if ( $check == "in" ) {
		// Connexion a la base de donnees :
		$connexion = conMysql ($server);
		// To protect MySQL injection for Security purpose
		$hostname = stripslashes($hostname);
		$ipaddr = stripslashes($ipaddr);
		$hostname = mysql_real_escape_string($hostname);
		$ipaddr = mysql_real_escape_string($ipaddr);
		
		// SQL query to fetch information of registerd users and finds user match.
		$req="select hostname from ds_hosts_client where hostname='".$hostname."';";
		$resu = execRequete ($req, $connexion);
		$rows = nbLigne($resu);
		if ($rows != 0) {
			$check = "unregister";
			print "<div class='bg-danger' style='text-align:center'>Hostname : <b>".$hostname."</b> already registered !</div>";	
		}
		$req="select ip_address from ds_hosts_client where ip_address='".$ipaddr."';";
		$resu = execRequete ($req, $connexion);
		$rows = nbLigne($resu);
		if ($rows != 0) {
			$check = "unregister";
			print "<div class='bg-danger' style='text-align:center'>Ip Address : <b>".$ipaddr."</b> already registered !</div>";
		}
		if ( $check == "in" ) {
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
			
			// SQL Insert new host
			$req="insert into ds_hosts_client (hostname, ip_address, status_id) values ('".$hostname."','".$ipaddr."','".$status_name['installing']."');";
			$resu = execRequete ($req, $connexion);
			if ( !$resu ) {
				print "Failed to execute : ".$resu."<br/>";
				errorRequete();
			} else {
				$req="select hostname, ip_address from ds_hosts_client where hostname <> 'all';";
				$resu = execRequete ($req, $connexion);
				if ( !$resu ) {
					print "Failed to execute : ".$resu."<br/>";
					errorRequete();
				} else {
					$inventory = fopen($server['ansible']['inventory'], 'w+');
					fwrite($inventory, "[configuration-ds]\n");
					while ($res = ligneSuivante($resu)) {
						fwrite($inventory, $res->hostname." ansible_ssh_user=root ansible_ssh_host=".$res->ip_address."\n");
					}
					fclose($inventory);
					print "Registering host";
				}
			}
		}
		// Deconnexion de la base de donnees :
		decMysql($connexion);
	}
?>