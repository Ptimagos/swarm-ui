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

$host=$_POST['hostId'];
$describe=$_POST['describeAction'];
if (isset($_POST['containerId'])){
	$containerID=$_POST['containerId'];
}
if (isset($_POST['image'])){
	$containerID=$_POST['image'];
}

print "<h4>".$describe." <b class='font-db-danger'>".$containerID."</b> on host ".$host."</h4>";

$getProgressBar = getBarProgress('starting','100');
print $getProgressBar;
?>