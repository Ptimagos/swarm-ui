<?PHP
include "hosts-middle3-get.php";
$jsonInfoServer = file_get_contents($server['cfg']['home']."hosts/".$host_hosts['hostname'].".json", False);
$infoServer = json_decode($jsonInfoServer, true);
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
						if ( $host_hosts['status_id'] == $status_name['installing'] && $active_task == 0 ){
							print "<span style='font-size:18px;'>".$host_hosts['hostname']."</span>";
							$registerHost = "toReRegister";
						} elseif ( $active_task > 0 ) {
							print "<span style='font-size:18px;'>".$host_hosts['hostname']."</span>";
							$registerHost = "registering";
						} elseif ( $host_hosts['status_id'] == $status_name['offline'] ) {
							print "<span style='font-size:18px;'>".$host_hosts['hostname']."</span>";
							$registerHost = "offline";
						} else {
							$registerHost = "done";
							print "<span class='col-xs-11' style='padding-top: 5px; font-size:18px;'>".$host_hosts['hostname']."</span>";
							include "hosts-middle3-btnAction.php";
						}
						?>
					</h3>
				</div>
				<div class="panel-body">
					<?PHP
					switch ($registerHost) {
						case 'toReRegister':
							$newJob_id = time() + rand();
							print '<form  id="addReHostForm" action="tpl/hosts/addHostTasks.php" method="post">';
							print "<input type='hidden' name='job_id' value='".$newJob_id."'>";
							print "<input type='hidden' name='hostname' value='".$host_hosts['hostname']."'>";
							print "<input type='hidden' name='ipaddr' value='".$host_hosts['ip_address']."'>";
							print "<span class='col-xs-5 col-md-3 label label-danger'><h4>Registration failed</h4>";
							print '<button id="#addDone" type="submit" name="submit" value="Continue" class="btn btn-default">';
							print "To register this server";
							print "</button></span></form>";
							break;
						case 'registering':
							print "<span class='col-xs-3 col-md-2 label label-default'><h5>Registration ...</h5><i class='glyphicon glyphicon-cog gly-spin'></i></span>";
							break;
						case 'offline':
							print "<span class='col-xs-3 col-md-2 label label-danger'><h4>Server is offline</h4></span>";
							break;
						case 'done':
							include "hosts-middle3-infoServer.php";
							include "hosts-middle3-list.php";
							break;
					}
					?>
				</div>

				<?PHP include $server['cfg']['home']."www/tpl/hosts/hosts-add-container-modal.php"; ?>


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
				function actionContainer(action,host_id,container_id,instance_id) {
					$('#tasksAction').modal('show');
					url = "tpl/hosts/actionContainer.php";
					method = "post";
					$.ajax({
						type: method,
						url: url,
						data: {actionCont: action,hostId: host_id,containerId: container_id,instanceId: instance_id},
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

			</div>
			<!-- Middle Container -->	
		</div>
	</div>
