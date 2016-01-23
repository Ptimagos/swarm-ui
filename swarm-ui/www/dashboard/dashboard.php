<?PHP
if ( !isset($server['projectName']) ) {
	session_start();
	//Inclusion du fichier de configuration
	require "../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../lib/fonctions.php";
	require "../../lib/mysql.php";
	require "../../lib/psql.php";
}
?>
<div id="wrapper">     
	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<ul id="sidebar_menu" class="sidebar-nav">
			<li class="sidebar-brand">
				<a id="menu-toggle" href="#">
					<span id="main_icon" class="glyphicon glyphicon-align-justify">
					</span>
				</a>
			</li>
		</ul>
		<ul class="sidebar-nav" id="sidebar">     
			<li id="wrapper-dash_0001" class="active">
				<a onClick='dashWrapper(1)' href="#" id="dash_elem_0001" data-toggle="tooltip" data-placement="right" title="Dashboard">
					Dashboard
					<div id="wrapper-dash">
						<span id="main_icon_dash" class="glyphicon glyphicon-dashboard">
						</span>
					</div>
				</a>
			</li>
			<li id="wrapper-dash_0002">
				<a onClick='dashWrapper(2)' href="#" id="dash_elem_0002" data-toggle="tooltip" data-placement="right" title="Agents Docker">
					Agents
					<div id="wrapper-agents">
						<?php include $server['cfg']['home'] . "www/alarms/dashboard-wrapper-agent-alarm.php"; ?>
					</div>
				</a>
			</li>
			<li id="wrapper-dash_0003">
				<a onClick='dashWrapper(3)' href="#" id="dash_elem_0003" data-toggle="tooltip" data-placement="right" title="Containers Docker">
					Containers
					<div id="wrapper-instances">
						<?php include $server['cfg']['home'] . "www/alarms/dashboard-wrapper-instance-alarm.php"; ?>
					</div>
				</a>
			</li>
			<li id="wrapper-dash_0004">
				<a onClick='dashWrapper(4)' href="#" id="dash_elem_0004" data-toggle="tooltip" data-placement="right" title="Tasks">
					Tasks
					<div id="wrapper-tasks">
						<?php include $server['cfg']['home'] . "www/alarms/dashboard-wrapper-tasks-alarm.php"; ?>
					</div>
				</a>
			</li>
			<li id="wrapper-dash_0005">
				<a onClick='dashWrapper(5)' href="#" id="dash_elem_0005" data-toggle="tooltip" data-placement="right" title="Parameters">
					Parameters
					<div id="wrapper-params">
						<span id="main_icon_dash" class="glyphicon glyphicon-cog">
						</span>
					</div>
				</a>
			</li>
		</ul>
	</div>
	<!-- Page content -->
	<div id="page-content-wrapper">
		<!-- Keep all page content within the page-content inset div! -->
		<!-- Middle Container -->
		<div class="col-xs-12 collapse.in" id="body-middle-container_0001">
			<?PHP include "dashboard-middle1.php"; ?>
		</div>
		<div class="col-xs-12 collapse" id="body-middle-container_0002">
			<?PHP include "dashboard-middle2.php"; ?>
		</div>
		<div class="col-xs-12 collapse" id="body-middle-container_0003">
			<?PHP include "dashboard-middle3.php"; ?>
		</div>
		<div class="col-xs-12 collapse" id="body-middle-container_0004">
			<?PHP include "dashboard-middle4.php"; ?>
		</div>
		<div class="col-xs-12 collapse" id="body-middle-container_0005">
			<?PHP include "dashboard-middle5.php"; ?>
		</div>
		<!-- End Middle Container -->
	</div>	
</div>

<!-- Modal -->
<div id="tasksAction" class="modal fade" data-backdrop="static" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="actionCont"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end modal -->

<script type="text/javascript">
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
				$('#body-middle-container_0002').load('dashboard/dashboard-middle2.php');
				$("#body-host-middle-container_0003").load("hosts/hosts-middle3.php");
			}, 2300);
		}	 
	});
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
				$('#body-middle-container_0003').load('dashboard/dashboard-middle3.php');
				$("#body-host-middle-container_0003").load("hosts/hosts-middle3.php");
			}, 2300);
		}	 
	});
}
function dashWrapper(page){
    var containerRootName="#body-middle-container_000";
    var containerWrapperName="#wrapper-dash_000";
    var totalDashElem=5+1;
	var currentContainer=page;
	for (var i = 1; i < totalDashElem; i++ ) {
		if(i!=currentContainer){
			$(containerRootName+i).addClass('collapse');
			$(containerRootName+i).removeClass('collapse.in');
			$(containerWrapperName+i).removeClass('active');
		}
	}
	$(containerRootName+currentContainer).removeClass('collapse');
	$(containerRootName+currentContainer).addClass('collapse.in');
	$(containerWrapperName+currentContainer).addClass('active');
}
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("active");
});
</script>


