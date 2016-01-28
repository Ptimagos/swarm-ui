<?PHP 
include "checks/docker-daemon.php";
include "checks/docker-swarm-manager.php";
include "checks/docker-swarm-containers.php"
?>
<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><?php print $server['projectName'];?></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="active"><a href="#" id="home">Dashboards</a></li>
        <li>
          <a href="#" id="hosts">
            Hosts
          </a>
        </li>
		    <?PHP
		    if ( $_SESSION['login_profile'] == 1 ) {
          print "<li><a href='#' id='adm-users'>Admin</a></li>";
		    }
		    ?>
		    <li>
          <a href="tpl/login/logout.php" id="logout">
            <span class="glyphicon glyphicon-off" style="font-size: 14px;"></span>
            (<?PHP print $_SESSION['login_user']; ?>)
          </a>
        </li>		
      </ul>
    </div>
    <!--/.nav-collapse -->
  </div>
</nav>

<!-- Modal -->
<div id="viewLogTasks" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<div id="logFile"></div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- end modal -->

<script type="text/javascript">
    $(document).ready(function(){
         $("#home").click(function(){
			var element = $(this).parent('li');
            element.addClass('active');
            element.siblings('li').removeClass('active');
            element.siblings('li').find('li').removeClass('active');
			$("#body").load("dashboard/dashboard.php");
			$("#wrapper-agents").load("/dockerstation/alarms/dashboard-wrapper-agent-alarm.php");
		 });
		 $("#hosts").click(function(){
			var element = $(this).parent('li');
            element.addClass('active');
            element.siblings('li').removeClass('active');
            element.siblings('li').find('li').removeClass('active');
			$("#body").load("hosts/hosts.php");
         });
		 $("#adm-users").click(function(){
			var element = $(this).parent('li');
            element.addClass('active');
            element.siblings('li').removeClass('active');
            element.siblings('li').find('li').removeClass('active');
			$("#body").load("admin/users.php");
         });
	});
	/*
	setInterval(function(){
		$('#body-middle-container_0001').load('dashboard/dashboard-middle1.php');
		$('#body-middle-container_0002').load('dashboard/dashboard-middle2.php');
		$('#body-middle-container_0003').load('dashboard/dashboard-middle3.php');
		$('#wrapper-agents').load('alarms/dashboard-wrapper-agent-alarm.php');
		$('#wrapper-instances').load('alarms/dashboard-wrapper-instance-alarm.php');
		$('#body-host-middle-container_0001').load('hosts/hosts-middle1.php');
	}, 15000);
	setInterval(function(){
		$('#body-host-middle-container_0004').load('dashboard/dashboard-middle4.php');
		$('#body-middle-container_0004').load('dashboard/dashboard-middle4.php');
		$('#wrapper-tasks').load('alarms/dashboard-wrapper-tasks-alarm.php');
	}, 5000);
	*/
</script>

<div class="container-fluid" id="body">
	<?PHP  include "dashboard/dashboard.php"; ?>
</div> 
<!-- /container -->
