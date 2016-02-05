<?php
	//Inclusion du fichier de configuration
	require "/opt/swarm-ui/cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "/opt/swarm-ui/lib/fonctions.php";
	
	date_default_timezone_set('CET');
	
	// Define $host
	$host=$argv['1'];

	/***** Pull Docker Image *****/
	$task = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/tasks/running/".$host);
	$taskValue = base64_decode($task['responce'][0]['Value']);
	$valueTask = json_decode($taskValue);
	
	// Execution Pull Docker Image
	$imagePull = unixCurlSSL("POST",$valueTask->url,"/images/create","?fromImage=".$valueTask->image);
	/***** END Pull Docker Image *****/

	$endDate=time();
	$jobId=time()."-".rand();
	$imageSet = '{"nodeName":"'.$valueTask->nodeName.'","url":"'.$valueTask->url.'","image":"'.$valueTask->image
		.'","action":"'.$valueTask->action.'","stat":"success","describe":"'
		.$valueTask->describe.'","progress":"100","startDate":"'.$valueTask->startDate
		.'","endDate":"'.$endDate.'","logs":"'.addslashes($imagePull[0]).'<br/>'.addslashes($imagePull[2]).'"}';
	restRequest("DELETE",$server['consul']['url'],"/v1/kv/docker/swarm-ui/tasks/running/".$host);
	createTask($imageSet,$jobId,$server);

?>