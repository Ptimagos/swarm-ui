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
// ----- Host Docker ----- //
$nodesDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
?>

<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
<div class="row">
<!-- Middle Container -->
<div class="col-xs-12" id="body-middle-container">
<h1 class="page-header">Hosts Docker</h1>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Hosts's List</h3>
</div>
<div class="panel-body">
Place a filter input here and action button !
<br/>
</div>
<!-- Table -->
<table class="table table-striped">
<tr>
<th>#</th>
<th>Hostname</th>
<th>Docker version</th>
<th>Status</th>
<th>Alarms</th>
</tr>
<?PHP
	$nb_nodeDocker = count($nodesDocker);
	for($x=0;$x<$nb_nodeDocker;$x++)
	{
		$nodeDockerValue = base64_decode($nodesDocker[$x]['Value']);
		$valueDocker = json_decode($nodeDockerValue);
		print "<tr>";
		print "<td>";
		print $x + 1;
		print "</td>";
		print "<td>";
		print $valueDocker->name;
		print "</td>";
		print "<td>";
		print $valueDocker->version;
		print "</td>";
		print "<td>";
		$button_actif="active";
		switch ($valueDocker->status) {
			case "Healthy":
				$label="label-success";
				$stat="running";
				break;
			case "down":
				$label="label-danger";
				$stat="unknown";
				break;
		}
		print "<span class='label ". $label ."' style='font-size: 95%;'>". $stat ."</span>";
		print "</td>";
		print "<td>";
		$checkAlarmSet = getAlarmStatus($valueDocker->name,"nodes",$server);
		if ( $checkAlarmSet == 0 ) {
			$label_off = "btn btn-xs btn-default";
			$label_on = "btn btn-xs btn-success active";
			$setAlarm = 0;
		} else {
			$label_on = "btn btn-xs btn-default";
			$label_off = "btn btn-xs btn-danger active";
			$setAlarm = 1;
		}
		print "<div class='btn-group' data-toggle='buttons' onClick=\"setAlarmAgent('".$server['setup']['uri']."tpl/alarm/setAlarm.php',"
			. "{name:'" . $valueDocker->name . "',alarm:" . $setAlarm . ",table:'nodes'})\">"
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
			$('#wrapper-agents').load('alarms/dashboard-wrapper-nodes-alarm.php');
		}	 
	});
}
</script>

<!-- Middle Container -->	
</div>
</div>
</div>