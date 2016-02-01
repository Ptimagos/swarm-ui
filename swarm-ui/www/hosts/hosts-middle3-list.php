<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#list-containers" aria-controls="containers" role="tab" data-toggle="tab">Containers</a></li>
	<li role="presentation"><a href="#list-images" aria-controls="images" role="tab" data-toggle="tab">Images</a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="list-containers">
		<!-- Table -->
		<table class="table table-striped no-margin-bottom">
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
					$actionCall = "'".$button_action."','".$valueContainerDocker->nodeName."','".$valueContainerDocker->id."','".$describe."'";
					if ( $button_actif == "active"){
						print "<button type='button' id='button_agent_action".$t."' onclick=\"actionContainer(".$actionCall.")\" class='btn btn-sm btn-default' style='padding: 4px;' autocomplete='off'>";
					} else {
						print "<button type='button' id='button_agent_action".$t."' class='btn btn-sm btn-default disabled' style='padding: 4px;' autocomplete='off'>";
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
	</div>
	<div role="tabpanel" class="tab-pane" id="list-images">
		<table class="table table-striped no-margin-bottom">
			<tr>
				<th class='col-xs-1'>Imager ID</th>
				<th class='col-xs-2'>Image RepoTags</th>
				<th class='col-xs-4'>Created</th>
				<th class='col-xs-4'>VirtualSize</th>
			</tr>
			<?php
			// ----- Images Docker ----- //
			$imageDocker = restRequestSSL("GET",$valueDocker->url,"/images/json");
			$nb_imageDocker = count($imageDocker);
			for($t=0;$t<$nb_imageDocker;$t++)
			{
				print "<tr>";
				print "<td>";
				print "<a onclick='loadCont(".$t.")' href='#'>".substr($imageDocker[$t]['Id'], 0, 12)."</a>";
				print "</td>";
				print "<td>";
				print $imageDocker[$t]['RepoTags'][0];
				print "</td>";
				print "<td>";
				echo date("Y-m-d H:i:s", $imageDocker[$t]['Created']);
				print "</td>";
				print "<td>";
				$virtualSize = intval($imageDocker[$t]['VirtualSize'] / 1024 / 1024);
				print $virtualSize." Mo";
				print "</td>";
				print "</tr>";
				
			}
			?>
		</table>
	</div>
</div>

<script type="text/javascript">
	$('#list-containers a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	});
	$('#list-images a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	});
</script>