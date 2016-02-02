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
<th>Hostname</th>
<th>Container ID</th>
<th>Name</th>
<th>Image</th>
<th>Container Status</th>
<th>Actions</th>
<th>Alarms</th>
</tr>
<?PHP
	$nb_containersDocker = count($containersDocker['responce']);
	for($x=0;$x<$nb_containersDocker;$x++)
	{
		$containerDockerValue = base64_decode($containersDocker['responce'][$x]['Value']);
		$valueDocker = json_decode($containerDockerValue);
		print "<tr>";
		print "<td>";
		print $valueDocker->nodeName;
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
		$button_actif="active";
		switch ($valueDocker->status) {
			case "Up":
				$label="label-success";
				$stat="running";
				$icon="glyphicon glyphicon-off";
				$label_action="label-danger";
				$button_action="stop";
				$describe="Stop container";
				break;
			case "Exited":
				$label="label-warning";
				$stat="stopped";
				$icon="glyphicon glyphicon-play";
				$label_action="label-success";
				$button_action="start";
				$describe="Start container";
				break;
			default:                                                                                                                                                                          
				$label="label-danger";                                                                                                                                                          
				$stat="unknown";                                                                                                                                                                
				$icon="glyphicon glyphicon-play";
				$label_action="label-success";
				$button_action="start";
				$button_actif="disabled";
				$describe="Start container";
				break;
		}
		print "<span class='label ". $label ."' style='font-size: 95%;'>". $stat ."</span>";
		print "</td>";
		print "<td>";
		$actionCall = "'".$button_action."','".$valueDocker->nodeName."','".$valueDocker->id."','".$describe."'";
		if ( $button_actif == "active"){
			print "<button type='button' id='button_agent_action".$x."' onclick=\"actionContainer(".$actionCall.")\" class='btn btn-sm btn-default' style='padding: 4px;' autocomplete='off'>";
		} else {
			print "<button type='button' id='button_agent_action".$x."' class='btn btn-sm btn-default disabled' style='padding: 4px;' autocomplete='off'>";
		}
		print "<span class='label ".$label_action." ".$icon."' style='font-size: 100%;top: 1.5px;'> </span></button>";
		print "</td>";
		print "<td>";
		$checkAlarmSet = getAlarmStatus($valueDocker->id,"containers",$server);
		if ( $checkAlarmSet == 0 ) {
			$label_off = "btn btn-xs btn-default";
			$label_on = "btn btn-xs btn-success active";
			$setAlarm = 0;
		} else {
			$label_on = "btn btn-xs btn-default";
			$label_off = "btn btn-xs btn-danger active";
			$setAlarm = 1;
		}
		print "<div class='btn-group' data-toggle='buttons' onClick=\"setAlarmCont('".$server['setup']['uri']."tpl/alarm/setAlarm.php',"
			. "{name:'" . $valueDocker->id . "',alarm:" . $setAlarm . ",table:'containers'})\">"
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
			$('#wrapper-instances').load('alarms/dashboard-wrapper-containers-alarm.php');
		}	 
	});
}
</script>

<!-- Middle Container -->	
</div>
</div>
</div>