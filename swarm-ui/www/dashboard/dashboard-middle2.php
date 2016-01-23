<?PHP
	include "dashboard-middle2-get.php";
?>

<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
<div class="row">
<!-- Middle Container -->
<div class="col-xs-12" id="body-middle-container">
<h1 class="page-header">Agents Docker</h1>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Agents's List</h3>
</div>
<div class="panel-body">
Place a filter input here and action button !
<br/>
Taille du tableau host : <?PHP print $arr_length; ?>
</div>
<!-- Table -->
<table class="table table-striped">
<tr>
<th>#</th>
<th>Agent name</th>
<th>version</th>
<th>Hostname</th>
<th>Agent Status</th>
<th>Actions</th>
<th>Alarms</th>
</tr>
<?PHP
	for($t=0;$t<$num_agent;$t++)
	{
		$x = $all_host_agents[$t]['id'];
		print "<tr>";
		print "<td>";
		print $x;
		print "</td>";
		print "<td>";
		print $agents[$all_host_agents[$x]['agent_id']]['name'];
		print "</td>";
		print "<td>";
		print $agents[$all_host_agents[$x]['agent_id']]['version'];
		print "</td>";
		print "<td>";
		print $all_hosts[$all_host_agents[$x]['host_id']]['hostname'];
		print "</td>";
		print "<td>";
		$button_actif="active";
		switch ($status_id[$all_host_agents[$x]['status_id']]) {
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
		print "<button type='button' id='button_agent_action" . $x . "' onclick=\"actionAgent('".$button_action."','".$all_host_agents[$x]['host_id']."')\" class='btn btn-sm" . $button_actif . "' style='padding: 5px;' autocomplete='off'>";
		print "<span class='label ". $label_action ." ". $icon ."' style='font-size: 100%;top: 0px;'> </span></button>";
		print "</td>";
		print "<td>";
		if ( $all_host_agents[$x]['set_alarm'] == 1 ) {
			$label_off = "btn btn-xs btn-default";
			$label_on = "btn btn-xs btn-success active";
			$setAlarm = 0;
		} else {
			$label_on = "btn btn-xs btn-default";
			$label_off = "btn btn-xs btn-danger active";
			$setAlarm = 1;
		}
		print "<div class='btn-group' data-toggle='buttons' onClick=\"setAlarmAgent('/dockerstation/tpl/alarm/setAlarm.php',"
			. "{id:" . $x . ",alarm:" . $setAlarm . ",table:'ds_hosts_agents',hearthbeat:'" . $all_host_agents[$x]['hearthbeat']. "'})\">"
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
function setAlarmAgent(path, params, method) {
	method = method || "post"; // Set method to post by default if not specified.
	path = path;
	sendData = params;
	$.ajax({
		type: method,
		url: path,
		data: sendData,
		success: function (data) {
			//jQuery("#resultSetAlarm").html(data);
			$('#body-middle-container_0002').load('dashboard/dashboard-middle2.php');
			$('#wrapper-agents').load('alarms/dashboard-wrapper-agent-alarm.php');
		}	 
	});
}
</script>

<!-- Middle Container -->	
</div>
</div>
</div>