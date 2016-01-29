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
// ----- Host Docker ----- //
$nodesDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
// ----- Containers Docker ----- //
$containersDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
?>


<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
<div class="row">
<!-- Middle Container -->
<div class="col-xs-12" id="body-middle-container">
<h1 class="page-header">Hosts</h1>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Hosts's List</h3>
</div>
<div class="panel-body">
	<div class="btn-group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Action <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class='disabled'><a href="#" data-toggle="modal" data-target="#addHost">Add Image</a></li>
			<li role="separator" class="divider"></li>
			<li class='disabled'><a href="#">Stop all Containers</a></li>
		</ul>
	</div>
</div>

<!-- Table -->
<table class="table table-striped">
<tr>
<th>#</th>
<th>Hostname</th>
<th>Host Status</th>
<th>Docker version</th>
<th>Containers Status</th>
</tr>
<?PHP
	date_default_timezone_set('CET');
	$nb_nodeDocker = count($nodesDocker);
	for($x=0;$x<$nb_nodeDocker;$x++)
	{
		$nodeDockerValue = base64_decode($nodesDocker[$x]['Value']);
		$valueDocker = json_decode($nodeDockerValue);
		print "<tr>";
		print "<td>";
		print $x;
		print "</td>";
		print "<td>";
		print "<a onclick='loadHost(".$x.")' href='#'>".$valueDocker->name."</a>";
		print "</td>";
		print "<td>";
		switch ($valueDocker->status) {
			case "Healthy":
				$label="success";
				$stat="running";
				break;
			case "down":
				$label="danger";
				$stat="unkonwn";
				break;
		}
		print "<span class='label label-".$label."' style='font-size: 95%;'>".$stat."</span>";
		print "</td>";
		print "<td>";
		print $valueDocker->version;
		print "</td>";
		print "<td>";
		$allContainers=0;
		$allContainersRunning=0;
		$allContainersStopped=0;
		$allContainersUnknown=0;
		$nb_containerDocker = count($containersDocker);
		for($t=0;$t<$nb_containerDocker;$t++)
		{
			$containerDockerValue = base64_decode($containersDocker[$t]['Value']);
			$valueContainerDocker = json_decode($containerDockerValue);
			if ( $valueDocker->name == $valueContainerDocker->nodeName )
			{
				$allContainers++;
				switch ($valueContainerDocker->status) {
					case 'Up':
						$allContainersRunning++;
						break;
					case 'Exited':
						$allContainersStopped++;
						break;
					case 'Unknown':
						$allContainersUnknown++;
						break;
				}
			}
		}
		if ( $allContainersRunning == 0 ) $allContainersRunning="";
		if ( $allContainersStopped == 0 ) $allContainersStopped="";
		if ( $allContainersUnknown == 0 ) $allContainersUnknown="";
		if ( $stat == "unkonwn" )
		{
			print "<a href='#' style='text-decoration: none;' data-toggle='tooltip' data-placement='top' title='Container(s) offline'><span class='label label-danger' style='font-size: 95%;'>".$allContainers."</span></a>";
		} else {
			print "<a href='#' style='text-decoration: none;' data-toggle='tooltip' data-placement='top' title='Container(s) running'><span class='label label-success' style='font-size: 95%;'>".$allContainersRunning."</span></a>";
			print "<a href='#' style='text-decoration: none;' data-toggle='tooltip' data-placement='top' title='container(s) stopped'><span class='label label-warning' style='font-size: 95%;'>".$allContainersStopped."</span></a>";
			print "<a href='#' style='text-decoration: none;' data-toggle='tooltip' data-placement='top' title='Container(s) unknown'><span class='label label-danger' style='font-size: 95%;'>".$allContainersUnknown."</span></a>";
		}
		print "</td>";
		print "</tr>";
	}
?>
</table>
</div>
<script type="text/javascript">
function loadHost(item){
	var containerRootName= '#body-host-middle-container_000';
	var uri = '<?PHP print $server['setup']['uri'];?>hosts/hosts-middle3.php?host_id=' + item;
	var totalDashElem=$("ul").size()+1;
	var currentContainer=3;
	for (var i = 1; i < totalDashElem; i++ ) {
		if(i!=currentContainer){
			$(containerRootName+i).addClass('collapse');
			$(containerRootName+i).removeClass('collapse.in');
		}
	}
	$(containerRootName+currentContainer).removeClass('collapse');
	$(containerRootName+currentContainer).addClass('collapse.in');
    $(containerRootName+currentContainer).load(uri);
}
</script>
</div>

<!-- Middle Container -->
	
 </div>
</div>