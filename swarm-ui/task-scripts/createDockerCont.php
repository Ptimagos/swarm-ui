<?php
	//Inclusion du fichier de configuration
	require "/opt/dockerstation/cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "/opt/dockerstation/lib/fonctions.php";
	require "/opt/dockerstation/lib/mysql.php";
	require "/opt/dockerstation/lib/psql.php";
	require "/opt/dockerstation/lib/ansible.php";

	
	date_default_timezone_set('CET');
	
	// Define $hostname and $ipaddr
	$host_id=$argv['1'];
	$job_id=$argv['2'];
	$instance_id=$argv['3'];
	$options=$argv['4'];
	
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
	
	$ansible_status=$status_name['success'];
	
	// request bdd - select all actions
	$req="select id, name from ds_actions;";
	$resu = execRequete ($req, $connexion);
	// Create array status
	while ($res = ligneSuivante($resu)) {
		$actions_id[$res->id]=$res->name;
		$actions_name[$res->name]=$res->id;
	}
	
	// request bdd - search hostname from host_id
	$req="select hostname from ds_hosts_client where id = '".$host_id."';";
	$resu = execRequete ($req, $connexion);
	while ($res = ligneSuivante($resu)) {
		$hostname=$res->hostname;
	}

	// request bdd - search name from instance_id
	$req="select name from ds_instance where id = '".$instance_id."';";
	$resu = execRequete ($req, $connexion);
	while ($res = ligneSuivante($resu)) {
		$nameImage=$res->name;
	}
	
	/***** Create Docker Container *****/
	
	// SQL Update Task Create Container to start
	$date_jobs=date("Y-m-d H:i:s");
	$req="update ds_tasks set status_id = '8', start_date = '".$date_jobs."', progress = '100' where job_id = '".$job_id."' and action_id = '".$actions_name['Create Container']."';";
	$resu = execRequete ($req, $connexion);
	
	// Execution Pull Docker Image
	$fileout=$server['cfg']['logs'].$hostname.".".$job_id.".log";
	$fileurl="/logs/".$hostname.".".$job_id.".log";
	$role="docker-create-container";
	$optionCreateCont = "\"OPTIONS_CREATE\":\"".$options."\"";
	$dockerImage = "\"DOCKER_IMAGE\":\"".$nameImage."\"";
	$ansible_args = "--extra-vars '{".$optionCreateCont.",".$dockerImage."}'";
	$ansiblePlay = ansiblePlay($server,$hostname,$fileout,$role,$ansible_args);
	if ( $ansiblePlay == 0 ) {
		$ansible_status=$status_name['success'];
		$cmd="grep container ".$fileout." | grep -v create | awk '{print $2}' | cut -c2-13";
		print "Valeur de cmd : ".$cmd."\n";
		exec($cmd,$resultCont);
		$req="insert into ds_hosts_instances (host_id,instance_id,container_id,status_id,set_alarm,options) "
		 . "values ('".$host_id."','".$instance_id."','".$resultCont[0]."','".$status_name['stopped']."','1','".$options."');";
		$resu = execRequete ($req, $connexion);
	} else {
		$ansible_status=$status_name['failed'];
	}
	$addBR = "sed -i 's%$%<br/>%' ".$fileout;
	exec($addBR);
	$date_jobs=date("Y-m-d H:i:s");
	// SQL Ansible action to finish
	$req="update ds_tasks set status_id = '".$ansible_status."', end_date = '".$date_jobs."', log_file ='".$fileurl."', "
		. "progress = '100' where job_id = '".$job_id."' and action_id = '".$actions_name['Create Container']."'";
	$resu = execRequete ($req, $connexion);
	
	/***** END Create Container *****/

	// Deconnexion de la base de donnees :
	decMysql($connexion);
?>