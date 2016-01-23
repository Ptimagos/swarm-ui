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

// ----- Agent Docker ----- //

// request bdd - Number of agent docker 
$req="select count(*) as nb_agent from ds_hosts_agents;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$dashboard_agents['total']=$res->nb_agent;
}


// request bdd - Number of agent running or stopped
$req="select status_id, count(*) as nb_agent_by_status from ds_hosts_agents where status_id in ('".$status_name['running']."','".$status_name['stopped']."') group by status_id;";
$resu = execRequete ($req, $connexion);
$dashboard_agents[$status_id[$status_name['running']]]=0;
$dashboard_agents[$status_id[$status_name['stopped']]]=0;
while ($res = ligneSuivante($resu)) {
	$dashboard_agents[$status_id[$res->status_id]]=$res->nb_agent_by_status;	
}

// request bdd - Number of agent other status
$req="select count(*) as nb_agent_unknown from ds_hosts_agents where status_id not in ('".$status_name['running']."','".$status_name['stopped']."');";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$dashboard_agents['unknown']=$res->nb_agent_unknown;	
}

// ----- Instance Docker ----- //

// request bdd - Number of instance docker 
$req="select count(*) as nb_instance from ds_hosts_instances;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$dashboard_instances['total']=$res->nb_instance;
}

// request bdd - Number of instance running or stopped
$req="select status_id, count(*) as nb_instance_by_status from ds_hosts_instances where status_id in ('".$status_name['running']."','".$status_name['stopped']."') group by status_id;";
$resu = execRequete ($req, $connexion);
$dashboard_instances[$status_id[$status_name['running']]]=0;
$dashboard_instances[$status_id[$status_name['stopped']]]=0;
while ($res = ligneSuivante($resu)) {
	$dashboard_instances[$status_id[$res->status_id]]=$res->nb_instance_by_status;	
}

// request bdd - Number of instance other status
$req="select count(*) as nb_instance_unknown from ds_hosts_instances where status_id not in ('".$status_name['running']."','".$status_name['stopped']."');";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$dashboard_instances['unknown']=$res->nb_instance_unknown;	
}

// ----- Host Docker ----- //

// request bdd - Number of host docker 
$req="select count(*) as nb_host from ds_hosts_client;";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$dashboard_hosts['total']=$res->nb_host;
}

// request bdd - Number of host running
$req="select count(*) as nb_host_running from ds_hosts_client where status_id = '".$status_name['running']."';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$dashboard_hosts['running']=$res->nb_host_running;	
}

// request bdd - Number of host other status
$req="select count(*) as nb_host_offline from ds_hosts_client where status_id = '".$status_name['offline']."';";
$resu = execRequete ($req, $connexion);
while ($res = ligneSuivante($resu)) {
	$dashboard_hosts['offline']=$res->nb_host_offline;	
}

// Disconnect to bdd :
decMysql($connexion);

print "<span id='main_icon_alarm' class='glyphicon glyphicon-eye-open'><span class='wrapper-badge wrapper-badge-danger'>".$dashboard_agents['unknown']."</span></span>";

?>