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
$containersDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
$nb_containerDocker = count($containersDocker['responce']);

include "../tpl/hosts/hosts-modal.php";
?>

<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
	<div class="row">
		<!-- Middle Container -->
		<div class="col-xs-12" id="body-middle-container">
			<h1 class="page-header">Host information</h1>
			<a onclick='backHosts()' href='#'><- Return Hosts</a><br/><br/>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<?php
						if ( $valueDocker->status == "down" ) {
							print "<span style='font-size:18px;'>".$valueDocker->name."</span>";
							$registerHost = "offline";
						} else {
							$registerHost = "done";
							print "<span class='col-xs-11' style='padding-top: 5px; font-size:18px;'>".$valueDocker->name."</span>";
							include "hosts-middle3-btnAction.php";
						}
						?>
					</h3>
				</div>
				<div class="panel-body">
					<?PHP
					switch ($registerHost) {
						case 'offline':
						print "<span class='col-xs-3 col-md-2 label label-danger'><h4>Server is offline</h4></span>";
						break;
						case 'done':
						include "hosts-middle3-infoServer.php";
						break;
					}
					?>
				</div>
				<?PHP include "hosts-middle3-list.php"; ?>
				

				<?PHP //include $server['cfg']['home']."www/tpl/hosts/hosts-add-container-modal.php"; ?>


				<script type="text/javascript">
					function backHosts(){
						var containerRootName= '#body-host-middle-container_000';
						var totalDashElem=$("ul").size()+1;
						var currentContainer=1;
						for (var i = 1; i < totalDashElem; i++ ) {
							if(i!=currentContainer){
								$(containerRootName+i).addClass('collapse');
								$(containerRootName+i).removeClass('collapse.in');
							}
						}
						$(containerRootName+currentContainer).removeClass('collapse');
						$(containerRootName+currentContainer).load('hosts/hosts-middle1.php');
						$(containerRootName+currentContainer).addClass('collapse.in');
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
					function actionImage() {
						$('#addImage').modal('hide');
						$('#tasksAction').modal('show');
						url = "tpl/actionLoader.php";
						method = "post";
						var frm4 = $("#installDockerImages");
						$.ajax({
							type: method,
							url: url,
							data: frm4.serialize(),
							success: function (data) {
								jQuery("#actionCont").html(data);
								$.ajax({
									type: frm4.attr('method'),
									url: frm4.attr('action'),
									data: frm4.serialize(),
									success: function (data) {
										jQuery("#actionCont").html(data);
										setTimeout(function(){
											$('#tasksAction').modal('hide');
										}, 2000);
										setTimeout(function(){
											$("#body-host-middle-container_0003").load("hosts/hosts-middle3.php?host_id=<?PHP print $host_id; ?>");
										}, 2300);
									}	 
								});
							}
						});
					}
					function actionAgent(action,host_id) {
						$('#tasksAction').modal('show');
						url = "tpl/hosts/actionAgent.php";
						method = "post";
						$.ajax({
							type: method,
							url: url,
							data: {actionCont: action,hostId: host_id},
							success: function (data) {
								jQuery("#actionCont").html(data);
								setTimeout(function(){
									$('#tasksAction').modal('hide');
								}, 2000);
								setTimeout(function(){
									$("#body-host-middle-container_0003").load("hosts/hosts-middle3.php?host_id=<?PHP print $host_id; ?>");
								}, 2300);
							}	 
						});
					}
				</script>
				<script type="text/javascript">
					var frm2 = $('#addReHostForm');
					frm2.submit(function (ev) { 
						$.ajax({
							type: frm2.attr('method'),
							url: frm2.attr('action'),
							data: frm2.serialize(),
							success: function (data) {
								jQuery("#actionCont").html(data);
								$("#tasksAction").modal('show');
								setTimeout(function(){
									$("#tasksAction").modal('hide');
								}, 2000);
								setTimeout(function(){
									$("#body-host-middle-container_0003").load("hosts/hosts-middle3.php?host_id=<?PHP print $host_id; ?>");
								}, 2300);
							}
						});
						ev.preventDefault();
					});
				</script>
				<!-- Middle Container -->	
			</div>
		</div>
	</div>
</div>
