	
<!-- Table -->
<table class="table table-striped">
<tr>
<th class='col-xs-1'>Container ID</th>
<th class='col-xs-2'>Container Image</th>
<th class='col-xs-2'>Options</th>
<th class='col-xs-4'>Description</th>
<th class='col-xs-2'>Action</th>
<th class='col-xs-2'>Status</th>
</tr>
<?php
	for($t=0;$t<$num_cont;$t++){
		$test_cont = $host_hosts[$t]['container_id'];
		if ($test_cont != '0'){
			switch ($status_id[$host_hosts[$t]['status_id']]) {
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
			print "<tr>";
			print "<td>";
			print "<a onclick='loadCont(".$x.")' href='#'>".$host_hosts[$t]['container_id']."</a>";
			print "</td>";
			print "<td>";
			print $instance_list[$host_hosts[$t]['instance_id']]['name'];
			print "</td>";
			print "<td>";
			print $host_hosts[$t]['options'];
			print "</td>";
			print "<td>";
			print $instance_list[$host_hosts[$t]['instance_id']]['description'];
			print "</td>";
			print "<td>";
			$actionCall = "'".$button_action."','".$host_id."','".$host_hosts[$t]['container_id']."','".$host_hosts[$t]['instance_id']."'";
			print "<button type='button' id='button_agent_action" . $x . "' data-loading-text='Loading...' onclick=\"actionContainer(".$actionCall.")\" class='btn btn-sm" . $button_actif . "' style='padding: 5px;' autocomplete='off'>";
			print "<span class='label ". $label_action ." ". $icon ."' style='font-size: 100%;top: 0px;'> </span></button>";
			print "</td>";
			print "<td>";
			print "<span class='label ". $label ."' style='font-size: 95%;'>". $stat ."</span>";
			print "</td>";
			print "</tr>";
		}
	}
?>
</table>