<?PHP
/*
  Check Swarms manager
*/

// Get Information about SWARM Cluster
$swarm_p = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm/leader");
$swarmPrimary = base64_decode($swarm_p[0]['Value']);
$swarmInfos = restRequestSSL("GET","https://".$swarmPrimary,"/containers/json","?all=1");

// Set Swarm manager Information and status
$checkSwarm = array();
$arrlength = count($swarmInfos);

for($x = 0; $x < $arrlength; $x++){
  $findme = "/swarm manage";
  $checkCommande = strpos($swarmInfos[$x]['Command'], $findme);
  if ( $checkCommande === 0 ){
    list($empty, $nodeName, $serviceName) =  explode("/", $swarmInfos[$x]['Names'][0]);
    list($status) =  explode(" ", $swarmInfos[$x]['Status']);
    $checkSwarm[$nodeName]['serviceName'] = $serviceName; 
    $swarmSet = '{"nodeName":"'.$nodeName.'","serviceName":"'.$serviceName.'","status":"'.$status.'"}';
    setSwarmManager($nodeName,$swarmSet,$server);
  }
}

for($x = 0; $x < $arrlength; $x++){
  $findme = "/swarm join";
  $checkCommande = strpos($swarmInfos[$x]['Command'], $findme);
  if ( $checkCommande === 0 ){
    list($empty, $nodeName, $serviceName) =  explode("/", $swarmInfos[$x]['Names'][0]);
    list($status) =  explode(" ", $swarmInfos[$x]['Status']);
    $checkSwarm[$nodeName]['serviceName'] = $serviceName; 
    $swarmSet = '{"nodeName":"'.$nodeName.'","serviceName":"'.$serviceName.'","status":"'.$status.'"}';
    setSwarmAgent($nodeName,$swarmSet,$server);
  }
}

// Check Status for all Swarm manager registored
$listNodes = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/swarm-manager","?recurse");
$arrlength = count($listNodes);
for($x = 0; $x < $arrlength; $x++){
  $nodeValue = base64_decode($listNodes[$x]['Value']);
  $value = json_decode($nodeValue);
  if (isset($value->nodeName) && !isset($checkSwarm[$value->nodeName])){
    $swarmSet = '{"nodeName":"'.$value->nodeName.'","serviceName":"'.$value->serviceName.'","status":"Unknown"}';
    setSwarmManager($value->nodeName,$swarmSet,$server);
  }
}

// Check Status for all Swarm agent registored
$listNodes = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/swarm-agent","?recurse");
$arrlength = count($listNodes);
for($x = 0; $x < $arrlength; $x++){
  $nodeValue = base64_decode($listNodes[$x]['Value']);
  $value = json_decode($nodeValue);
  if (isset($value->nodeName) && !isset($checkSwarm[$value->nodeName])){
    $swarmSet = '{"nodeName":"'.$value->nodeName.'","serviceName":"'.$value->serviceName.'","status":"Unknown"}';
    setSwarmAgent($value->nodeName,$swarmSet,$server);
  }
}               

?>
