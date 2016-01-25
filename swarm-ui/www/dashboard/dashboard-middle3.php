<?PHP
// ----- Host Docker ----- //
$containersDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
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
<th>Name</th>
<th>Image</th>
<th>Hostname</th>
<th>Container Status</th>
<th>Actions</th>
<th>Alarms</th>
</tr>
<?PHP
	$nb_containersDocker = count($containersDocker);
	for($x=0;$x<$nb_containersDocker;$x++)
	{
		$containerDockerValue = base64_decode($containersDocker[$x]['Value']);
		$valueDocker = json_decode($containerDockerValue);
		print "<tr>";
		print "<td>";
		print $x + 1;
		print "</td>";
		print "<td>";
		print $valueDocker->id;
		print "</td>";
		print "<td>";
		print $valueDocker->serviceName;
		print "</td>";
		print "<td>";
		print $valueDocker->image;
		print "</td>";
		print "<td>";
		print $valueDocker->nodeName;
		print "</td>";
		print "<td>";
		$button_actif="active";
		switch ($valueDocker->status) {
			case "Up":
				$label="label-success";
				$stat="running";
				$icon="glyphicon glyphicon-off";
				$label_action="label-danger";
				$button_action="stop";
				break;
			case "exited":
				$label="label-danger";
				$stat="stopped";
				$icon="glyphicon glyphicon-play";
				$label_action="label-success";
				$button_action="start";
				break;
			case "default":                                                                                                                                                                          
                                $label="label-danger";                                                                                                                                                          
                                $stat="unknown";                                                                                                                                                                
				$icon="glyphicon glyphicon-play";
				$label_action="label-success";
				$button_action="start";
                                break;
		}
		print "<span class='label ". $label ."' style='font-size: 95%;'>". $stat ."</span>";
		print "</td>";
		print "<td>";
		$actionCall = "'".$button_action."','".$valueDocker->nodeName."','".$valueDocker->id."'";
		print "<button type='button' id='button_agent_action" . $x . "' onclick=\"actionContainer(".$actionCall.")\" class='btn btn-sm" . $button_actif . "' style='padding: 5px;' autocomplete='off'>";
		print "<span class='label ". $label_action ." ". $icon ."' style='font-size: 100%;top: 0px;'> </span></button>";
		print "</td>";
		print "<td>";
		if (!isset($valueDocker->alarm)) {
			$label_off = "btn btn-xs btn-default";
			$label_on = "btn btn-xs btn-success active";
			$setAlarm = 0;
		} else {
			$label_on = "btn btn-xs btn-default";
			$label_off = "btn btn-xs btn-danger active";
			$setAlarm = 1;
		}
		print "<div class='btn-group' data-toggle='buttons' onClick=\"setAlarmCont('".$server['setup']['uri']."tpl/alarm/setAlarm.php',"
			. "{id:" . $x . ",alarm:" . $setAlarm . ",table:'ds_hosts_instances',hearthbeat:'1234'})\">"
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