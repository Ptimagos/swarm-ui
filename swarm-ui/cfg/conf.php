<?PHP
$server['cfg']['domain']=".rouen.francetelecom.fr";		// Domain server
$server['cfg']['home']="/opt/swarm-ui/";			// APP Homedir
$server['cfg']['logs']=$server['cfg']['home']."logs/";
$server['projectName']="Swarm-UI";

/* Configuration Page Web */
$server['setup']['title']="Swarm-UI";
$server['setup']['toptitle']="";
$server['setup']['uri']="/";

/* Setting access to MySQL Server */
$server['mysql']['user']="swarm-ui"; 				// Login access to MYSQL
$server['mysql']['pass']="swarm-ui"; 				// Password access to MYSQL
$server['mysql']['host']="192.168.99.110";			// Server Host MYSQL
$server['mysql']['db']="swarm-ui";				// BD Name

/* Setting Conul server */
$server['consul']['url']="http://consul:8500";			// Server CONSUL URL
$server['consul']['store_keys']="/v1/kv/docker/swarm-ui";	// Directory Key Store in CONSUL
$server['swarm']['url']="https://swarm-manager:3376";		// Server Swarm URL

/* Configuration ansible */
$server['ansible']['script-tasks']=$server['cfg']['home']."task-scripts/";
$server['ansible']['home']=$server['cfg']['home']."ansible/";
$server['ansible']['inventory']=$server['ansible']['home']."inventory-ds-host";
$server['ansible']['playbook']=$server['ansible']['home']."playbook-ds.yml";
$server['ansible']['checkhostdir']=$server['cfg']['home']."hosts/";

?>
