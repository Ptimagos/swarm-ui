<?php
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
$host = $_POST['hostId'];
$image = $_POST['image'];
$url = $_POST['url'];
$action=$_POST['actionImg'];
$describe=$_POST['describeAction'];
$jobId=time()."-".rand();
$startDate="";
$endDate="";
$status="waiting";
$log="";
$image .= ":latest";


$imageSet = '{"nodeName":"'.$host.'","url":"'.$url.'","image":"'.$image
				.'","action":"'.$action.'","stat":"'.$status
				.'","describe":"'.$describe.'","progress":"100","startDate":"'.$startDate
				.'","endDate":"'.$endDate.'","logs":"'.$logs.'"}';
createImageTask($imageSet,$jobId,$server);

print "<h4>".$describe." <b class='font-db-danger'>".$image."</b> on host ".$host." added to task list</h4>";

?>