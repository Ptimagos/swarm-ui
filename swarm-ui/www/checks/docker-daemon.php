<?PHP
/*
  Check Docker-Daemon Service And update Store CONSUL
*/

// Get Information about SWARM Cluster
$swarm_p = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm/leader");
$swarmPrimary = base64_decode($swarm_p[0]['Value']);
$swarmInfos = restRequestSSL("GET","https://".$swarmPrimary,"/info");

// Set Nodes Information and status
$checkNodes = array();
$arrlength = count($swarmInfos['DriverStatus']);
for($x = 4, $j = 5, $t = 9; $x < $arrlength; $x += 6, $j += 6, $t += 6){
  $nodeName = $swarmInfos['DriverStatus'][$x][0];
  $nodeServiceUrl = $swarmInfos['DriverStatus'][$x][1];
  $nodeHealth = $swarmInfos['DriverStatus'][$j][1];
  $nodeVersion = explode(" ",$swarmInfos['DriverStatus'][$t][1]);
  $checkNodes[$nodeName] = $nodeName; 
  $nodeSet = '{"name":"'.$nodeName.'","version":"'.$nodeVersion[3].'","url":"https://'.$nodeServiceUrl.'","status":"'.$nodeHealth.'"}';
  setNode($nodeName,$nodeSet,$server);
}

// Check Status for all Nodes registored
$listNodes = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
$arrlength = count($listNodes);
for($x = 0; $x < $arrlength; $x++){
  $nodeValue = base64_decode($listNodes[$x]['Value']);
  $value = json_decode($nodeValue);
  if (isset($value->name) && !isset($checkNodes[$value->name])){
    $checkDirectAccess = restRequestSSL("GET",$value->url,"/version");
    if (!isset($checkDirectAccess['Version'])) {
      $nodeSet = '{"name":"'.$value->name.'","version":"'.$value->version.'","url":"'.$value->url.'","status":"down"}';
      setNode($value->name,$nodeSet,$server);
    }
  }
}              

?>
