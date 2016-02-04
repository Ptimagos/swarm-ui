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
	
	// request bdd - search hostname and ip_address from host_id
	$req="select hostname, ip_address from ds_hosts_client where id = '".$host_id."';";
	$resu = execRequete ($req, $connexion);
	while ($res = ligneSuivante($resu)) {
		$hostname=$res->hostname;
		$ipaddr=$res->ip_address;
	}
	
	// request bdd - search version DockerStation Agent version
	$req="select id, version from ds_agent where active = '1';";
	$resu = execRequete ($req, $connexion);
	while ($res = ligneSuivante($resu)) {
		$dsAgentID=$res->id;
		$dsAgentVersion=$res->version;
	}
	
	/***** Check Ansible access *****/
	
	// SQL Update Task Check ansible access to start
	$date_jobs=date("Y-m-d H:i:s");
	$req="update ds_tasks set status_id = '8', start_date = '".$date_jobs."', progress = '10' where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."';";
	$resu = execRequete ($req, $connexion);
	$req="update ds_tasks set status_id = '8', start_date = '".$date_jobs."', progress = '100' where job_id = '".$job_id."' and action_id = '".$actions_name['Check ansible access']."';";
	$resu = execRequete ($req, $connexion);
	
	// Execution Ansible Check
	$fileout=$server['ansible']['checkhostdir'].$hostname.".json";
	ansibleCheck($server,$hostname,$fileout);
	$date_jobs=date("Y-m-d H:i:s");
	if ( filesize($fileout) != 0 ) {
		$ansible_status=$status_name['success'];
		$req="update ds_tasks set progress = '25' where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
		$resu = execRequete ($req, $connexion);
	} else {
		$ansible_status=$status_name['failed'];
		$req="update ds_tasks set status_id = '".$status_name['canceled']."', start_date = '".$date_jobs."', "
			. "end_date = '".$date_jobs."', progress = '100' where job_id = '".$job_id."' and status_id = '".$status_name['waiting']."'";
		$resu = execRequete ($req, $connexion);
		$req="update ds_tasks set status_id = '".$status_name['failed']."', end_date = '".$date_jobs."', progress = '100' "
			. "where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
		$resu = execRequete ($req, $connexion);
	}
	// SQL Update Task Check ansible access to finish
	$req="update ds_tasks set status_id = '".$ansible_status."', end_date = '".$date_jobs."', progress = '100' "
		. "where job_id = '".$job_id."' and action_id = '".$actions_name['Check ansible access']."'";
	$resu = execRequete ($req, $connexion);
	
	/***** END Check Ansible access *****/
	
	/***** Install Docker application *****/
	
	if ( $ansible_status == $status_name['success'] ) {
		// SQL Update Task Install Docker to start
		$date_jobs=date("Y-m-d H:i:s");
		$req="update ds_tasks set status_id = '8', start_date = '".$date_jobs."', progress = '100' "
			. "where job_id = '".$job_id."' and action_id = '".$actions_name['Install Docker package']."'";
		$resu = execRequete ($req, $connexion);
		
		// Execution Ansible action
		$fileout=$server['cfg']['logs'].$hostname.".".$job_id.".log";
		$fileurl="/logs/".$hostname.".".$job_id.".log";
		$role="docker-dpkg,docker-restart";
		$ansible_args="";
		$ansiblePlay = ansiblePlay($server,$hostname,$fileout,$role,$ansible_args);
		$date_jobs=date("Y-m-d H:i:s");
		if ( $ansiblePlay == 0 ) {
			$ansible_status=$status_name['success'];
			$req="insert into ds_hosts_docker (host_id,status_id,set_alarm) values ('".$host_id."','".$status_name['running']."','1');";
			$resu = execRequete ($req, $connexion);
			$req="update ds_tasks set progress = '50' where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
			$resu = execRequete ($req, $connexion);
		} else {
			$ansible_status=$status_name['failed'];
			$req="update ds_tasks set status_id = '".$status_name['canceled']."', start_date = '".$date_jobs."', "
				. "end_date = '".$date_jobs."', progress = '100' where job_id = '".$job_id."' and status_id = '".$status_name['waiting']."'";
			$resu = execRequete ($req, $connexion);
			$req="update ds_tasks set status_id = '".$status_name['failed']."', end_date = '".$date_jobs."', progress = '100' "
				. "where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
			$resu = execRequete ($req, $connexion);
		}
		$addBR = "sed -i 's%$%<br/>%' ".$fileout;
		exec($addBR);
		// SQL Ansible action to finish
		$req="update ds_tasks set status_id = '".$ansible_status."', end_date = '".$date_jobs."', log_file ='".$fileurl."', "
			. "progress = '100' where job_id = '".$job_id."' and action_id = '".$actions_name['Install Docker package']."'";
		$resu = execRequete ($req, $connexion);
	
	/***** END Install Docker application *****/

		/***** Install Docker agent *****/
		if ( $ansible_status == $status_name['success'] ) {
			// SQL Update Task Install Docker to start
			$date_jobs=date("Y-m-d H:i:s");
			$req="update ds_tasks set status_id = '8', start_date = '".$date_jobs."', progress = '100' "
				. "where job_id = '".$job_id."' and action_id = '".$actions_name['Install DS Agent']."'";
			$resu = execRequete ($req, $connexion);
			
			// Execution Ansible action
			$fileout=$server['cfg']['logs'].$hostname.".".$job_id.".log";
			$fileurl="/logs/".$hostname.".".$job_id.".log";
			$role="docker-agent";
			$agentVersion = "\"DS_AGENT_VERSION\":\"".$dsAgentVersion."\"";
			$hostClientId = "\"HOST_CLIENT_ID\":\"".$host_id."\"";
			$hostHostname = "\"HOST_CLIENT_HOSTNAME\":\"".$hostname."\"";
			$ansible_args = "--extra-vars '{".$agentVersion.",".$hostClientId.",".$hostHostname."}'";
			$ansiblePlay = ansiblePlay($server,$hostname,$fileout,$role,$ansible_args);
			$date_jobs=date("Y-m-d H:i:s");
			if ( $ansiblePlay == 0 ) {
				$ansible_status=$status_name['success'];
				$req="insert into ds_hosts_agents (host_id,agent_id,status_id,set_alarm) values ('".$host_id."','".$dsAgentID."','".$status_name['running']."','1');";
				$resu = execRequete ($req, $connexion);
				$req="update ds_tasks set progress = '75' where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
				$resu = execRequete ($req, $connexion);
			} else {
				$ansible_status=$status_name['failed'];
				$req="update ds_tasks set status_id = '".$status_name['canceled']."', start_date = '".$date_jobs."', "
					. "end_date = '".$date_jobs."', progress = '100' where job_id = '".$job_id."' and status_id = '".$status_name['waiting']."'";
				$resu = execRequete ($req, $connexion);
				$req="update ds_tasks set status_id = '".$status_name['failed']."', end_date = '".$date_jobs."', progress = '100' "
					. "where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
				$resu = execRequete ($req, $connexion);
			}
			$addBR = "sed -i 's%$%<br/>%' ".$fileout;
			exec($addBR);
			// SQL Ansible action to finish
			$req="update ds_tasks set status_id = '".$ansible_status."', end_date = '".$date_jobs."', log_file ='".$fileurl."', "
				. "progress = '100' where job_id = '".$job_id."' and action_id = '".$actions_name['Install DS Agent']."'";
			$resu = execRequete ($req, $connexion);
		
		/***** End Install Docker agent *****/
			
			/***** Update Host Information *****/
			
			if ( $ansible_status == $status_name['success'] ) {
				// SQL Update Task Install Docker to start
				$date_jobs=date("Y-m-d H:i:s");
				$req="update ds_tasks set status_id = '8', start_date = '".$date_jobs."', progress = '100' "
					. "where job_id = '".$job_id."' and action_id = '".$actions_name['Update host information']."'";
				$resu = execRequete ($req, $connexion);
				
				$fileout="";
				// WAIT 10 SEC
				sleep(10);
				
				$date_jobs=date("Y-m-d H:i:s");
				
				if ( $ansiblePlay == 0 ) {
					$ansible_status=$status_name['success'];
					$req="update ds_tasks set progress = '100', status_id = '".$status_name['success']."', "
						. "end_date = '".$date_jobs."' where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
					$resu = execRequete ($req, $connexion);
					$req="update ds_hosts_client set status_id = '".$status_name['running']."', "
						. "set_alarm = '1' where id = '".$host_id."';";
					$resu = execRequete ($req, $connexion);
				} else {
					$ansible_status=$status_name['failed'];
					$req="update ds_tasks set status_id = '".$status_name['canceled']."', start_date = '".$date_jobs."', "
						. "end_date = '".$date_jobs."', progress = '100' where job_id = '".$job_id."' and status_id = '".$status_name['waiting']."'";
					$resu = execRequete ($req, $connexion);
					$req="update ds_tasks set status_id = '".$status_name['failed']."', end_date = '".$date_jobs."', progress = '100' "
						. "where job_id = '".$job_id."' and action_id = '".$actions_name['add host']."'";
					$resu = execRequete ($req, $connexion);
				}
				
				// SQL Ansible action to finish
				$req="update ds_tasks set status_id = '".$ansible_status."', end_date = '".$date_jobs."', log_file ='".$fileout."', "
					. "progress = '100' where job_id = '".$job_id."' and action_id = '".$actions_name['Update host information']."'";
				$resu = execRequete ($req, $connexion);
			}
		}
	}
	// Deconnexion de la base de donnees :
	decMysql($connexion);
?>