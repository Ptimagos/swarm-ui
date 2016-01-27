<?PHP
/*
  Check Containers by Swarm
*/

// Get Information about SWARM Cluster
$swarm_p = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm/leader");
$swarmPrimary = base64_decode($swarm_p[0]['Value']);

// Get All containers in the cluster
$containerInfos = restRequestSSL("GET","https://".$swarmPrimary,"/containers/json","?all=1");

// Set Containers Information and status
$checkContainers = array();
$arrlength = count($containerInfos);

for($x = 0; $x < $arrlength; $x++){
  list($empty, $nodeName, $serviceName) =  explode("/", $containerInfos[$x]['Names'][0]);
  list($status, $uptime) = explode(" ", $containerInfos[$x]['Status'], 2);
  $containerID = substr($containerInfos[$x]['Id'], 0, 12);
  $containerImage = $containerInfos[$x]['Image'];
  $checkContainer[$containerID]['serviceName'] = $serviceName; 
  $containerSet = '{"nodeName":"'.$nodeName.'","id":"'.$containerID.'","image":"'.$containerImage.'","serviceName":"'.$serviceName.'","status":"'.$status.'","uptime":"'.$uptime.'"}';
  $table = $nodeName."-".$containerID;
  setContainer($table,$containerSet,$server);
}         

// Check Status for all Swarm manager registored
$listContainers = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
$arrlength = count($listContainers);
for($x = 0; $x < $arrlength; $x++){
  $containerValue = base64_decode($listContainers[$x]['Value']);
  $value = json_decode($containerValue);
  if (isset($value->id) && !isset($checkContainer[$value->id])){
    $swarmSet = '{"nodeName":"'.$value->nodeName.'","id":"'.$value->id.'","image":"'.$value->image.'","serviceName":"'.$value->serviceName.'","status":"Unknown","uptime":"Unknown"}';
    $table = $value->nodeName."-".$value->id;
    setContainer($table,$swarmSet,$server);
  }
}       
?>
