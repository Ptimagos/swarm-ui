<?PHP
//Inclusion du fichier de configuration
require "/opt/swarm-ui/cfg/conf.php";

//Inclusion des differentes librairies
require "/opt/swarm-ui/lib/fonctions.php";

// ----- Waiting Tasks Docker ----- //
$taskWaiting = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/tasks/waiting/","?recurse");
$num_waiting_tsk = count($taskWaiting['responce']);
for($x=0;$x<$num_waiting_tsk;$x++){
	$taskWaitingValue = base64_decode($taskWaiting['responce'][$x]['Value']);
	$valueTaskWaiting = json_decode($taskWaitingValue);
	$checkTaskRunning = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/tasks/running/".$valueTaskWaiting->nodeName,"?recurse");
	$num_TaskRunning = count($checkTaskRunning['responce']);
	if ( $num_TaskRunning >= 1 ){
		continue;
	}
	$date_tasks=date("Y-m-d H:i:s");
	switch ($valueTaskWaiting->describe) {
		case "add host" :
			print $date_tasks." - INFO - Launch Tasks with job_id ".$res->job_id." on server ".$res->host_id." => add host\n";
			$cmd = "nohup /usr/bin/php5 ".$server['ansible']['script-tasks']."addHostScript.php ".$res->host_id." ".$res->job_id." >> ".$server['cfg']['logs']."searchTasks.log 2>&1 &";
			exec ($cmd, $result_Exec);
			break;
		case "Pull image" :
			print $date_tasks." - INFO - Launch task for the server ".$valueTaskWaiting->nodeName." => Pull image ".$valueTaskWaiting->image."\n";
			// Update Task in CONSUL
			$startDate=time();
			$endDate="";
			$imageSet = '{"nodeName":"'.$valueTaskWaiting->nodeName.'","image":"'.$valueTaskWaiting->image
				.'","action":"'.$valueTaskWaiting->action.'","stat":"starting","describe":"'
				.$valueTaskWaiting->describe.'","progress":"100","startDate":"'.$startDate
				.'","endDate":"'.$endDate.'","logs":""}';

			restRequest("DELETE",$server['consul']['url'],"/v1/kv/".$taskWaiting['responce'][$x]['Key']);
			createRunningTask($imageSet,$valueTaskWaiting->nodeName,$server);
			$cmd = "nohup /usr/bin/php5 pullDockerImage.php ".$valueTaskWaiting->nodeName." >> ".$server['cfg']['logs']."searchTasks.log 2>&1 &";
			exec ($cmd, $result_Exec);
			break;
		case "Create Container" :
			print $date_tasks." - INFO - Launch Tasks with job_id ".$res->job_id." on server ".$res->host_id." => Create Container\n";
			$cmd = "nohup /usr/bin/php5 ".$server['ansible']['script-tasks']."createDockerCont.php ".$res->host_id." ".$res->job_id." "
				.$res->instance_id." \"".$res->options."\" >> ".$server['cfg']['logs']."searchTasks.log 2>&1 &";
			exec ($cmd, $result_Exec);
			break;
		case "Stop agent" :
			print $date_tasks." - INFO - Launch Tasks with job_id ".$res->job_id." on server ".$res->host_id." => Stop agent\n";
			$cmd = "nohup /usr/bin/php5 ".$server['ansible']['script-tasks']."stopAgent.php ".$res->host_id." ".$res->job_id." "
				." >> ".$server['cfg']['logs']."searchTasks.log 2>&1 &";
			exec($cmd, $result_Exec);
			break;
		case "Start agent" :
			print $date_tasks." - INFO - Launch Tasks with job_id ".$res->job_id." on server ".$res->host_id." => Start agent\n";
			$cmd = "nohup /usr/bin/php5 ".$server['ansible']['script-tasks']."startAgent.php ".$res->host_id." ".$res->job_id." "
				." >> ".$server['cfg']['logs']."searchTasks.log 2>&1 &";
			exec($cmd, $result_Exec);
			break;
		case "Stop container" :
			print $date_tasks." - INFO - Launch Tasks with job_id ".$res->job_id." on server ".$res->host_id." => Stop container\n";
			$cmd = "nohup /usr/bin/php5 ".$server['ansible']['script-tasks']."stopContainer.php ".$res->host_id." ".$res->job_id." "
				.$res->instance_id." ".$res->options." >> ".$server['cfg']['logs']."searchTasks.log 2>&1 &";
			exec($cmd, $result_Exec);
			break;
		case "Start container" :
			print $date_tasks." - INFO - Launch Tasks with job_id ".$res->job_id." on server ".$res->host_id." => Start container\n";
			$cmd = "nohup /usr/bin/php5 ".$server['ansible']['script-tasks']."startContainer.php ".$res->host_id." ".$res->job_id." "
				.$res->instance_id." ".$res->options." >> ".$server['cfg']['logs']."searchTasks.log 2>&1 &";
			exec($cmd, $result_Exec);
			break;
		default :
			break;
	}
}
?>