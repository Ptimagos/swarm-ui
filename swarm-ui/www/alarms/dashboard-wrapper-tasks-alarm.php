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
$tasks_list['nb_tasks'] = 0;
if ( $tasks_list['nb_tasks'] == 0 ) {
	print "<span id='main_icon_dash' class='glyphicon glyphicon-list-alt'><span class='wrapper-badge'></span></span>";
} else {
	print "<span id='main_icon_alarm' class='glyphicon glyphicon-list-alt'><span class='wrapper-badge wrapper-badge-info'>".$tasks_list['nb_tasks']."</span></span>";
}
?>