<?PHP
	include "dashboard-middle4-get.php";
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
<th class="col-xs-2">Start</th>
<th class="col-xs-2">End</th>
<th class="col-xs-1"></th>
</tr>
</table>
</div>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin: 0px 10px 0px 10px;">
<?PHP
	for($t=0;$t<$num_main_tsk;$t++)
	{
		$collapseIn="";
		$x = $main_tasks[$t]['id'];
		if (isset($_SESSION['valCollapse']) && $_SESSION['valCollapse'] == $x ) {
			$collapseIn = "in";
		}
		print '<div class="panel panel-default">';
		print '<div class="panel-heading" role="tab" id="headingOne" style="padding: 0px;">';
		print '<a onclick="doalert('.$x.')" class="myhref" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$x.'" aria-expanded="true" aria-controls="collapse'.$x.'">';
		print "<table class='table' style='margin-bottom: 0px;'>";		
		print "<tr style='width: 100%;'>";
		print "<td class='col-xs-2'>";
		print $actions_id[$main_tasks[$x]['action_id']];
		if ($main_tasks[$x]['instance_name'] != ""){
			print " : ".$main_tasks[$x]['instance_name'];
		}
		print "</td>";
		print "<td class='col-xs-2'>";
		print $hosts_id[$main_tasks[$x]['host_id']];
		print "</td>";
		print "<td class='col-xs-3'>";
		$getProgressBar = getBarProgress($status_id[$main_tasks[$x]['status_id']],$main_tasks[$x]['progress']);
		print $getProgressBar;
		print "</td>";
		print "<td class='col-xs-2'>";
		if ( $main_tasks[$x]['start_date'] != "0000-00-00 00:00:00" ) 
		{
			print $main_tasks[$x]['start_date'];
		}
		print "</td>";
		print "<td class='col-xs-2'>";
		if ( $main_tasks[$x]['end_date'] != "0000-00-00 00:00:00" ) 
		{
			print $main_tasks[$x]['end_date'];
		}
		print "</td>";
		print "<td class='col-xs-1'>";
		print "</td>";
		print "</tr>";
		print "</table>";
		print "</a>";
		print "</div>";
		print '<div id="collapse'.$x.'" class="panel-collapse collapse '.$collapseIn.'" role="tabpanel" aria-labelledby="headingOne">';
		print '<div class="panel-body" style="padding: 0px;">';
		$j_id = $main_tasks[$x]['job_id'];
		$arr_step_length=count($main_tasks[$x][$j_id]['step']) + 1;
		for($s=1;$s<$arr_step_length;$s++) 
		{
			print "<table class='table' style='margin-bottom: 0px;'>";		
			print "<tr style='width: 100%;'>";
			print "<td class='col-xs-2'>";
			print $actions_id[$main_tasks[$x][$j_id]['step'][$s]['action_id']];
			print "</td>";
			print "<td class='col-xs-2'>";
			print $hosts_id[$main_tasks[$x]['host_id']];
			print "</td>";
			print "<td class='col-xs-3'>";
			$getProgressBar = getBarProgress($status_id[$main_tasks[$x][$j_id]['step'][$s]['status_id']],$main_tasks[$x][$j_id]['step'][$s]['progress']);
			print $getProgressBar;
			print "</td>";
			print "<td class='col-xs-2'>";
			if ( $main_tasks[$x][$j_id]['step'][$s]['start_date'] != "0000-00-00 00:00:00" ) 
			{
				print $main_tasks[$x][$j_id]['step'][$s]['start_date'];
			}
			print "</td>";
			print "<td class='col-xs-2'>";
			if ( $main_tasks[$x][$j_id]['step'][$s]['end_date'] != "0000-00-00 00:00:00" ) 
			{
				print $main_tasks[$x][$j_id]['step'][$s]['end_date'];
			}
			print "</td>";
			print "<td class='col-xs-1'>";
			if ( $main_tasks[$x][$j_id]['step'][$s]['logFile'] != "" ) {
				print "<a href='#' onclick=\"viewLogTasks('".$main_tasks[$x][$j_id]['step'][$s]['logFile']."')\" >log</a>";
			}
			print "</td>";
			print "</tr>";
			print "</table>";
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
	uri = '/dockerstation/tpl/setTasksCollapse.php?setTasksCollapse=' + item;
    $("#setTasksCollapse").load(uri);
}
</script>


<!-- Middle Container -->	
</div>
</div>
</div>