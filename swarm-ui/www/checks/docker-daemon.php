<?PHP
/*
  Check Docker-Daemon Service And update Store CONSUL
*/

// Get Information about SWARM Cluster
$swarm_p = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm/leader");
$swarmPrimary = base64_decode($swarm_p['responce'][0]['Value']);
$swarmInfos = restRequestSSL("GET","https://".$swarmPrimary,"/info");

// Set Nodes Information and status
$checkNodes = array();
$arrlength = count($swarmInfos['responce']['SystemStatus']);
for($x = 4, $j = 5, $t = 9; $x < $arrlength; $x += 8, $j += 8, $t += 8){
  $nodeName = trim($swarmInfos['responce']['SystemStatus'][$x][0]);
  $nodeServiceUrl = $swarmInfos['responce']['SystemStatus'][$x][1];
  $nodeHealth = $swarmInfos['responce']['SystemStatus'][$j][1];
  $nodeVersion = explode(" ",$swarmInfos['responce']['SystemStatus'][$t][1]);
  $checkNodes[$nodeName] = $nodeName;
  $nodeSet = '{"name":"'.$nodeName.'","version":"'.$nodeVersion[3].'","url":"https://'.$nodeServiceUrl.'","status":"'.$nodeHealth.'"}';
  setNode($nodeName,$nodeSet,$server);
}

// Check Status for all Nodes registored
$listNodes = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
$arrlength = count($listNodes['responce']);
for($x = 0; $x < $arrlength; $x++){
  $nodeValue = base64_decode($listNodes['responce'][$x]['Value']);
  $value = json_decode($nodeValue);
  if (isset($value->name) && !isset($checkNodes[$value->name])){
    $checkDirectAccess = restRequestSSL("GET",$value->url,"/version");
    if (!isset($checkDirectAccess['responce']['Version'])) {
      $nodeSet = '{"name":"'.$value->name.'","version":"'.$value->version.'","url":"'.$value->url.'","status":"down"}';
      setNode($value->name,$nodeSet,$server);
    } elseif ($value->status == "down") {
      $nodeSet = '{"name":"'.$value->name.'","version":"'.$value->version.'","url":"'.$value->url.'","status":"Healthy"}';
      setNode($value->name,$nodeSet,$server);
    }
  }
}              

?>
