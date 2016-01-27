	
<!-- Table -->
<table class="table table-striped">
<tr>
<th class='col-xs-1'>Container ID</th>
<th class='col-xs-2'>Container Image</th>
<th class='col-xs-4'>Name</th>
<th class='col-xs-2'>Action</th>
<th class='col-xs-2'>Status</th>
</tr>
<?php
	// ----- Containers Docker ----- //
	$containersDocker = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
	$nb_containerDocker = count($containersDocker);
	for($t=0;$t<$nb_containerDocker;$t++)
	{
		$containerDockerValue = base64_decode($containersDocker[$t]['Value']);
		$valueContainerDocker = json_decode($containerDockerValue);
		if ( $valueDocker->name == $valueContainerDocker->nodeName )
		{
			$button_actif="active";
			switch ($valueContainerDocker->status) {
			case "Up":
				$label="label-success";
				$stat="running";
				$icon="glyphicon glyphicon-off";
				$label_action="label-danger";
				$button_action="stop";
				break;
			case "Exited":
				$label="label-warning";
				$stat="stopped";
				$icon="glyphicon glyphicon-play";
				$label_action="label-success";
				$button_action="start";
				break;
			default:                                                                                                                                                                          
				$label="label-danger";                                                                                                                                                          
				$stat="unknown";                                                                                                                                                                
				$icon="glyphicon glyphicon-play";
				$label_action="label-success";
				$button_action="start";
				$button_actif="disabled";
				break;
			}
			print "<tr>";
			print "<td>";
			print "<a onclick='loadCont(".$t.")' href='#'>".$valueContainerDocker->id."</a>";
			print "</td>";
			print "<td>";
			print $valueContainerDocker->image;
			print "</td>";
			print "<td>";
			print $valueContainerDocker->serviceName;
			print "</td>";
			print "<td>";
			$actionCall = "'".$button_action."','".$valueContainerDocker->nodeName."','".$valueContainerDocker->id."'";
			if ( $button_actif == "active"){
				print "<button type='button' id='button_agent_action".$x."' onclick=\"actionContainer(".$actionCall.")\" class='btn btn-sm btn-default' style='padding: 4px;' autocomplete='off'>";
			} else {
				print "<button type='button' id='button_agent_action".$x."' class='btn btn-sm btn-default disabled' style='padding: 4px;' autocomplete='off'>";
			}
			print "<span class='label ".$label_action." ".$icon."' style='font-size: 100%;top: 1.5px;'> </span></button>";
			print "<td>";
			print "<span class='label ". $label ."' style='font-size: 95%;'>". $stat ."</span>";
			print "</td>";
			print "</tr>";
		}
	}
?>
</table>