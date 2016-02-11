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
$host_id = $_GET['host_id'];
$nodesDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
$nodeDockerValue = base64_decode($nodesDocker['responce'][$host_id]['Value']);
$valueDocker = json_decode($nodeDockerValue);
// ----- Containers Docker ----- //
$container_id = $_GET['container_id'];
$containerConsul = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers/".$valueDocker->name."-".$container_id);
$containerConsulValue = base64_decode($containerConsul['responce'][0]['Value']);
$valueContainerConsul = json_decode($containerConsulValue);
$containerDocker = restRequestSSL("GET",$valueDocker->url,"/containers/".$container_id."/json");

include "../tpl/hosts/hosts-modal.php";
?>

<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
	<div class="row">
		<!-- Middle Container -->
		<div class="col-xs-12" id="body-middle-container">
			<h1 class="page-header">Container information</h1>
			<a onclick='backHost("<?PHP print $host_id; ?>")' href='#'><- Return Host</a><br/><br/>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<span class='col-xs-11' style='padding-top: 5px; font-size:18px;'>
							Container : <?PHP print $valueContainerConsul->serviceName; ?>
						</span>
						<?PHP include "hosts-middle3-btnAction.php"; ?>
					</h3>
				</div>
				<div class="panel-body">
					<?PHP include "hosts-middle4-infoContainer.php"; ?>
				</div>
				<?PHP include "hosts-middle4-list.php"; ?>				
				<!-- Middle Container -->	
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function backHost(item){
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
	function actionContainer(action,host_id,container_id,describe) {
		$('#tasksAction').modal('show');
		url = "tpl/actionLoader.php";
		method = "post";
		$.ajax({
			type: method,
			url: url,
			data: {hostId: host_id,containerId: container_id,describeAction: describe},
			success: function (data) {
				jQuery("#actionCont").html(data);
				url = "tpl/hosts/actionContainer.php";
				method = "post";
				$.ajax({
					type: method,
					url: url,
					data: {actionCont: action,hostId: host_id,containerId: container_id,describeAction: describe},
					success: function (data) {
						jQuery("#actionCont").html(data);
						setTimeout(function(){
							$('#tasksAction').modal('hide');
						}, 2000);
						setTimeout(function(){
							$("#body-host-middle-container_0003").load("hosts/hosts-middle3.php?host_id=<?PHP print $host_id; ?>");
							$("#body-host-middle-container_0001").load("hosts/hosts-middle1.php");
							$("#body-host-middle-container_0004").load("dashboard/dashboard-middle4.php");
							//$('#body-middle-container_0003').load('dashboard/dashboard-middle3.php');
							//$('#body-middle-container_0004').load('dashboard/dashboard-middle4.php');
							//$('#wrapper-instances').load('alarms/dashboard-wrapper-containers-alarm.php');
						}, 2300);
					} 
				});
			}
		});
	}
</script>
