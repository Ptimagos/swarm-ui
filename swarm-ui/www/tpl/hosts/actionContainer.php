<?PHP
	session_start();
	if ( !isset($_SESSION['login_user']) ) {
		header('Location: /');
  		exit();
	}
	//Inclusion du fichier de configuration
	require "../../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../../lib/fonctions.php";
	require "../../../lib/mysql.php";
	require "../../../lib/psql.php";
	
	// Define $hostname and $ipaddr
	$host=$_POST['hostId'];
	$containerID=$_POST['containerId'];
	$action=$_POST['actionCont'];
	$jobId=time()."-".rand();

	$containerSet = '{"nodeName":"'.$host.'","containerID":"'.$containerID.'","action":"'.$action.'","stat":"waiting","describe":"'.$action.' container","progress":"0"}';
  createTask($host,$containerID,$containerSet,$jobId,$server);

	print "<h4>".$action." container <b class='font-db-danger'>".$containerID."</b> added to task lists</h4>";
?>