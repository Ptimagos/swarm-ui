<?PHP
if ( !isset($server['projectName']) ) {
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
}

$getStatus = $_GET['getStatus'];
$progress = $_GET['progress'];

echo getBarProgress($getStatus,$progress)

?>