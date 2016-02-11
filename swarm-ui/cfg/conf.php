<?PHP
$server['cfg']['domain']=".service.consul";		                     // Domain server
$server['cfg']['home']="/opt/swarm-ui/";			                     // APP Homedir
$server['cfg']['logs']=$server['cfg']['home']."logs/";
$server['cfg']['task-scripts']=$server['cfg']['home']."task-scripts/";
$server['projectName']="Docker SwarmUI";

/* Configuration Page Web */
$server['setup']['title']="Docker SwarmUI";
$server['setup']['toptitle']="";
$server['setup']['uri']="/";

/* Settting TLS Docker */
$server['tls']['cert']=$server['cfg']['home']."certs/cert.pem";
$server['tls']['key']=$server['cfg']['home']."certs/key.pem";
$server['tls']['cacert']=$server['cfg']['home']."certs/ca.pem";

/* Setting Conul server */
$server['consul']['url']="http://consul.service.consul:8500";			// Server CONSUL URL
$server['consul']['store_keys']="/v1/kv/docker/swarm-ui";	        // Directory Key Store in CONSUL
?>
