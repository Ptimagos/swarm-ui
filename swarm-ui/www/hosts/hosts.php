<?PHP
if ( !isset($server['projectName']) ) {
	session_start();
	//Inclusion du fichier de configuration
	require "../../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../../lib/fonctions.php";
	require "../../lib/mysql.php";
	require "../../lib/psql.php";

	include "../tpl/hosts/hosts-add-host-modal.php";
}
?>
<div id="wrapper">     
	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<ul id="sidebar_menu" class="sidebar-nav">
			<li class="sidebar-brand"><a id="menu-toggle" href="#"><span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
		</ul>
		<ul class="sidebar-nav" id="sidebar">     
			<li id="wrapper-hosts_0001" class="active"><a onclick='hostsWrapper(1)' href="#" id="hosts_elem_0001" data-toggle="tooltip" data-placement="right" title="Hosts">Hosts<span id="main_icon" class="glyphicon glyphicon-th-list"></span></a></li>
			<li id="wrapper-hosts_0004"><a onClick='hostsWrapper(4)' href="#" id="hosts_elem_0003" data-toggle="tooltip" data-placement="right" title="Tasks">Tasks<div id="wrapper-tasks"><?php include $server['cfg']['home'] . "www/alarms/dashboard-wrapper-tasks-alarm.php"; ?></div></a></li>
			<li id="wrapper-hosts_0002"><a onclick='hostsWrapper(2)' href="#" id="hosts_elem_0002" data-toggle="tooltip" data-placement="right" title="Parameters">Parameters<span id="main_icon" class="glyphicon glyphicon-cog"></span></a></li>
		</ul>
	</div>
          
      <!-- Page content -->
      <div id="page-content-wrapper">
        <!-- Keep all page content within the page-content inset div! -->

	 <!-- Middle Container -->
    <div class="col-xs-12 collapse.in" id="body-host-middle-container_0001">
		<?PHP include "hosts-middle1.php"; ?>
	</div>
	<div class="col-xs-12 collapse" id="body-host-middle-container_0002">
		<?PHP include "hosts-middle2.php"; ?>
	</div>
	<div class="col-xs-12 collapse" id="body-host-middle-container_0003">
	</div>
	<div class="col-xs-12 collapse" id="body-host-middle-container_0004">
		<?PHP include "../dashboard/dashboard-middle4.php"; ?>
    </div>
	
        <!-- End Middle Container -->
      </div>
      
    </div>
<script type="text/javascript">
function hostsWrapper(page){
    var containerRootName="#body-host-middle-container_000";
	var containerWrapperName="#wrapper-hosts_000";
    var totalDashElem=4+1;
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


