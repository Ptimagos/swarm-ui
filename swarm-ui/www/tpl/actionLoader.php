<?PHP
session_start();
if ( !isset($_SESSION['login_user']) ) {
	header('Location: /');
	exit();
}
	//Inclusion du fichier de configuration
require "../../cfg/conf.php";

	//Inclusion des differentes librairies
require "../../lib/fonctions.php";
require "../../lib/mysql.php";
require "../../lib/psql.php";

	// Define $hostname and $ipaddr
$host=$_POST['hostId'];
$containerID=$_POST['containerId'];
$describe=$_POST['describeAction'];

print "<h4>".$describe." <b class='font-db-danger'>".$containerID."</b> on host ".$host."</h4>";

$getProgressBar = getBarProgress('starting','100');
print $getProgressBar;
?>