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
	// ----- Containers Docker ----- //
$taskDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/tasks","?recurse");
?>

<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
	<div class="row">
		<!-- Middle Container -->
		<div class="col-xs-12" id="body-middle-container">
			<h1 class="page-header">Tasks</h1>
			<div class="panel panel-default" style="padding-bottom: 10px;">
				<div class="panel-heading">
					<h3 class="panel-title">Tasks's List</h3>
				</div>
				<div class="panel-body">
					Place a filter input here and action button !
					<br/>
				</div>
				<!-- Table -->
				<div class="panel panel-default" style="margin: 0px 10px 5px 10px;">
					<table class="table table-striped">
						<tr style="width: 100%;">
							<th class="col-xs-2">Action</th>
							<th class="col-xs-2">Server</th>
							<th class="col-xs-3">Status</th>
							<th class="col-xs-1"></th>
							<th class="col-xs-2">Start</th>
							<th class="col-xs-2">End</th>
						</tr>
					</table>
				</div>

				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin: 0px 10px 0px 10px;">
					<?PHP
					$num_main_tsk = count($taskDocker['responce']);
					for($x=$num_main_tsk - 1;$x>=0;$x--)
					{
						$collapseIn="";
						$taskDockerValue = base64_decode($taskDocker['responce'][$x]['Value']);
						$valueTaskDocker = json_decode($taskDockerValue);
						if (isset($_SESSION['valCollapse']) && $_SESSION['valCollapse'] == $x ) {
							$collapseIn = "in";
						}
						print '<div class="panel panel-default">';
						print '<div class="panel-heading" role="tab" id="headingOne" style="padding: 0px;">';
						print '<a onclick="doalert('.$x.')" class="myhref" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$x.'" aria-expanded="true" aria-controls="collapse'.$x.'">';
						print "<table class='table' style='margin-bottom: 0px;'>";		
						print "<tr style='width: 100%;'>";
						print "<td class='col-xs-2'>";
						print $valueTaskDocker->describe;
						if (isset($valueTaskDocker->containerID)){
							print " : ".$valueTaskDocker->containerID;
						}
						if (isset($valueTaskDocker->image)){
							print " : ".$valueTaskDocker->image;
						}
						print "</td>";
						print "<td class='col-xs-2'>";
						print $valueTaskDocker->nodeName;
						print "</td>";
						print "<td class='col-xs-3'>";
						$getProgressBar = getBarProgress($valueTaskDocker->stat,$valueTaskDocker->progress);
						print $getProgressBar;
						print "</td>";
						print "</td>";
						print "<td class='col-xs-1'>";
						print "</td>";
						print "<td class='col-xs-2'>";
						if (isset($valueTaskDocker->startDate))
						{
							echo date("Y-m-d H:i:s",$valueTaskDocker->startDate);
						}
						print "</td>";
						print "<td class='col-xs-2'>";
						if (isset($valueTaskDocker->endDate))
						{
							echo date("Y-m-d H:i:s",$valueTaskDocker->endDate);
						}
						print "</tr>";
						print "</table>";
						print "</a>";
						print "</div>";
						print '<div id="collapse'.$x.'" class="panel-collapse collapse '.$collapseIn.'" role="tabpanel" aria-labelledby="headingOne">';
						print '<div class="panel-body" style="padding: 0px;">';
						if (isset($valueTaskDocker->logs)) {
							print $valueTaskDocker->logs;
						}
						print "</div>";
						print "</div>";
						print "</div>";
					}
					?>
				</div>
			</div>

			<div id="setTasksCollapse"></div> 
			<script type="text/javascript">
			function viewLogTasks(logFile) {
				$('#viewLogTasks').modal('show');
				jQuery("#logFile").load(logFile);
			}
			function doalert(item){
				uri = '/tpl/setTasksCollapse.php?setTasksCollapse=' + item;
				$("#setTasksCollapse").load(uri);
			}
			</script>


			<!-- Middle Container -->	
		</div>
	</div>
</div>