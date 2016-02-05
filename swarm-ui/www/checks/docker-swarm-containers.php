<?PHP
/*
  Check Containers by Swarm
*/

// Get Information about SWARM Cluster
$swarm_p = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm/leader");
$swarmPrimary = base64_decode($swarm_p['responce'][0]['Value']);

// Get All containers in the cluster
$containerInfos = restRequestSSL("GET","https://".$swarmPrimary,"/containers/json","?all=1");

// Set Containers Information and status
$checkContainers = array();
$arrlength = count($containerInfos['responce']);

for($x = 0; $x < $arrlength; $x++){
  list($empty, $nodeName, $serviceName) =  explode("/", $containerInfos['responce'][$x]['Names'][0]);
  //list($status, $uptime) = explode(" ", $containerInfos['responce'][$x]['Status'], 2);
  $status=$containerInfos['responce'][$x]['Status'];
  $containerID = substr($containerInfos['responce'][$x]['Id'], 0, 12);
  $containerImage = $containerInfos['responce'][$x]['Image'];
  $checkContainer[$containerID] = $containerID; 
  $containerSet = '{"nodeName":"'.$nodeName.'","id":"'.$containerID.'","image":"'.$containerImage.'","serviceName":"'.$serviceName.'","status":"'.$status.'","uptime":"'.$uptime.'"}';
  $table = $nodeName."-".$containerID;
  setContainer($table,$containerSet,$server);
}         

// Check Status for all Swarm manager registored
$listContainers = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
$arrlength = count($listContainers['responce']);
for($x = 0; $x < $arrlength; $x++){
  $containerValue = base64_decode($listContainers['responce'][$x]['Value']);
  $value = json_decode($containerValue);
  if (isset($value->id) && !isset($checkContainer[$value->id])){
    $table = $value->nodeName."-".$value->id;
    $checkNodeStatus = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes/".$value->nodeName);
    $checkNodeStatusValue = base64_decode($checkNodeStatus['responce'][0]['Value']);
    $valueCheckNodeStatus = json_decode($checkNodeStatusValue);
    if ( $valueCheckNodeStatus->status == "Healthy"){
      $checkLocalContainer = restRequestSSL("GET",$valueCheckNodeStatus->url,"/containers/".$value->id."/json");
      if (isset($checkLocalContainer['responce']['Id'])){
        switch ($checkLocalContainer['responce']['State']['Status']) {
          case 'running':
            $status="Up ";
            break;
          case 'exited':
            $status="Exited ";
            break;
          default:
            $status=$checkLocalContainer['responce']['State']['Status'];
            break;
        }
      } else {
        $status="Delete";
      }
    } else {
      $status="Unknown";
      $uptime="Unknown";
    }
    if ($status == "Delete"){
      unsetContainer($table,$server);
    } else {
      $swarmSet = '{"nodeName":"'.$value->nodeName.'","id":"'.$value->id.'","image":"'.$value->image.'","serviceName":"'.$value->serviceName.'","status":"'.$status.'","uptime":"'.$uptime.'"}';
      setContainer($table,$swarmSet,$server);
    }
  }
}       
?>
