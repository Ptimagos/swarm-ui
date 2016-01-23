<?PHP
	include "dashboard-middle3-get.php";
?>

<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
<div class="row">
<!-- Middle Container -->
<div class="col-xs-12" id="body-middle-container">
<h1 class="page-header">Containers Docker</h1>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Containers's List</h3>
</div>
<div class="panel-body">
Place informations here and action button ...
<br/>
</div>
<!-- Table -->
<table class="table table-striped">
<tr>
<th>#</th>
<th>Container ID</th>
<th>Image</th>
<th>Hostname</th>
<th>Container Status</th>
<th>Actions</th>
<th>Alarms</th>
</tr>
<?PHP
	//print "Valeur de num_cont : ".$num_cont."<br/>";
	for($t=0;$t<$num_cont;$t++)
	{
		$x = $all_host_instances[$t]['id'];
		print "<tr>";
		print "<td>";
		print $x;
		print "</td>";
		print "<td>";
		print $all_host_instances[$x]['container_id'];
		print "</td>";
		print "<td>";
		print $instances[$all_host_instances[$x]['instance_id']]['name'];
		print "</td>";
		print "<td>";
		print $all_hosts[$all_host_instances[$x]['host_id']]['hostname'];
		print "</td>";
		print "<td>";
		$button_actif="active";
		switch ($status_id[$all_host_instances[$x]['status_id']]) {
			case "running":
				$label="label-success";
				$icon="glyphicon glyphicon-off";
				$label_action="label-danger";
				$button_action="stop";
				$stat="running";
				break;
			case "stopped":
				$label="label-warning";
				$icon="glyphicon glyphicon-play";
				$stat="stopped";
				$button_action="start";
				$label_action="label-success";
				break;
			case "unknown":
				$label="label-danger";
				$stat="unknown";
				$icon="glyphicon glyphicon-play";
				$button_action="start";
				$label_action="label-success";
				break;
		}
		print "<span class='label ". $label ."' style='font-size: 95%;'>". $stat ."</span>";
		print "</td>";
		print "<td>";
		$actionCall = "'".$button_action."','".$all_host_instances[$x]['host_id']."','".$all_host_instances[$x]['container_id']."','".$all_host_instances[$x]['instance_id']."'";
		print "<button type='button' id='button_agent_action" . $x . "' onclick=\"actionContainer(".$actionCall.")\" class='btn btn-sm" . $button_actif . "' style='padding: 5px;' autocomplete='off'>";
		print "<span class='label ". $label_action ." ". $icon ."' style='font-size: 100%;top: 0px;'> </span></button>";
		print "</td>";
		print "<td>";
		if ( $all_host_instances[$x]['set_alarm'] == 1 ) {
			$label_off = "btn btn-xs btn-default";
			$label_on = "btn btn-xs btn-success active";
			$setAlarm = 0;
		} else {
			$label_on = "btn btn-xs btn-default";
			$label_off = "btn btn-xs btn-danger active";
			$setAlarm = 1;
		}
		print "<div class='btn-group' data-toggle='buttons' onClick=\"setAlarmCont('/dockerstation/tpl/alarm/setAlarm.php',"
			. "{id:" . $x . ",alarm:" . $setAlarm . ",table:'ds_hosts_instances',hearthbeat:'" . $all_host_instances[$x]['hearthbeat']. "'})\">"
			. "<label class='" . $label_on . "'>"
			. "<input type='radio' name='alarmSet' autocomplete='off' /> On"
			. "</label>"
			. "<label class='" . $label_off . "'>"
			. "<input type='radio' name='alarmSet' autocomplete='off' /> Off"
			. "</label>"
			. "</div>";
		print "</td>";
		print "</tr>";
	}
?>


</table>
</div>

<script type="text/javascript">
function setAlarmCont(path, params, method) {
	method = method || "post"; // Set method to post by default if not specified.
	path = path;
	sendData = params;
	$.ajax({
		type: method,
		url: path,
		data: sendData,
		success: function (data) {
			//jQuery("#resultSetAlarm").html(data);
			$('#body-middle-container_0003').load('dashboard/dashboard-middle3.php');
			$('#wrapper-instances').load('alarms/dashboard-wrapper-instance-alarm.php');
		}	 
	});
}
</script>

<!-- Middle Container -->	
</div>
</div>
</div>