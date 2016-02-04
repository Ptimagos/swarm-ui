<?php
	//Inclusion du fichier de configuration
	require "/opt/swarm-ui/cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "/opt/swarm-ui/lib/fonctions.php";
	
	date_default_timezone_set('CET');
	
	// Define $hostname and $ipaddr
	$job_id=$argv['1'];
	$startDate=time();
	$endDate="";

	/***** Pull Docker Image *****/
	$task = restRequest("GET",$server['consul']['url'],"/v1/kv/".$job_id);
	$taskValue = base64_decode($task['responce'][0]['Value']);
	$valueTask = json_decode($taskValue);
	
	/*
	// Execution Pull Docker Image

	$fileout=$server['cfg']['logs'].$hostname.".".$job_id.".log";
	$fileurl="/logs/".$hostname.".".$job_id.".log";
	$role="docker-pull-image";
	$dockerImage = "\"DOCKER_IMAGE\":\"".$nameImage."\"";
	$ansible_args = "--extra-vars '{".$dockerImage."}'";
	$ansiblePlay = ansiblePlay($server,$hostname,$fileout,$role,$ansible_args);
	$date_jobs=date("Y-m-d H:i:s");
	if ( $ansiblePlay == 0 ) {
		$ansible_status=$status_name['success'];
		$req="insert into ds_hosts_instances (host_id,instance_id,status_id,set_alarm,container_id) values ('".$host_id."','".$instance_id."','".$status_name['stopped']."','1','0');";
		$resu = execRequete ($req, $connexion);
	} else {
		$ansible_status=$status_name['failed'];
	}
	$addBR = "sed -i 's%$%<br/>%' ".$fileout;
	exec($addBR);	
	// SQL Ansible action to finish
	$req="update ds_tasks set status_id = '".$ansible_status."', end_date = '".$date_jobs."', log_file ='".$fileurl."', "
		. "progress = '100' where job_id = '".$job_id."' and action_id = '".$actions_name['Pull Docker Image']."'";
	$resu = execRequete ($req, $connexion);
	
	/***** END Pull Docker Image *****/
?>