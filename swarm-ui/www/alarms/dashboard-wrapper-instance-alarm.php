<?PHP
if ( !isset($server['projectName']) ) {
	session_start();
	//Inclusion du fichier de configuration
	require "../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../lib/fonctions.php";
	require "../../lib/mysql.php";
	require "../../lib/psql.php";
}

// Connection to bdd :
$connexion = conMysql ($server);

// request bdd - select all status 
$req="select * from ds_status;";
$resu = execRequete ($req, $connexion);

// Create array status
while ($res = ligneSuivante($resu)) {
	$status_id[$res->id]=$res->status;
	$status_name[$res->status]=$res->id;
}

// ----- Instance Docker ----- //

// request bdd - Count instance unknown status
$req="select count(*) as nb_instance_unknown from ds_hosts_instances"
	. " where status_id not in ('".$status_name['running']."','".$status_name['stopped']."');";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$dashboard_instances['unknown']=$res->nb_instance_unknown;	
}

// ----- Agents Docker ----- //
$current_time = time();

// request bdd - Count agents unknown status
$instance_unknown=0;
$req="select * from ds_hosts_instances where set_alarm = '1' and container_id <> '0';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	switch($res->status_id){
		case $status_name['running']:
			$up_time = $current_time - strtotime($res->hearthbeat);
			if ( $up_time > 600 ) {
				$instance_unknown++;
			}
			break;
		default:
			$instance_unknown++;
		;;
	}
}

$dashboard_instances['unknown']=$instance_unknown;

// Disconnect to bdd :
decMysql($connexion);

if ( $dashboard_instances['unknown'] == 0 ) {
	print "<span id='main_icon_dash' class='glyphicon glyphicon-oil'><span class='wrapper-badge wrapper-badge-danger'></span></span>";
} else {
	print "<span id='main_icon_alarm' class='glyphicon glyphicon-oil'><span class='wrapper-badge wrapper-badge-danger'>".$dashboard_instances['unknown']."</span></span>";
}
?>