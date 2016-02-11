<?php
	// ----- Information Docker ----- //
	$versionDocker = restRequestSSL("GET",$valueDocker->url,"/version");
?>
<div class='col-xs-9 well'>
	<h4>Global</h4>
	<b>Id : </b>
	<?php print $containerDocker['responce']['Id']; ?>
	<br/>
	<b>Created : </b>
	<?php 
		$dateCreate = explode("T", $containerDocker['responce']['Created']);
		$hourCreate = explode(".", $dateCreate[1]);
		print $dateCreate[0]." ".$hourCreate[0];
	?>
	<br/>
	<h4>Network</h4>
	<b>IP : </b>
	<?PHP
		print $containerDocker['responce']['NetworkSettings']['IPAddress'];
	?>
	<br/>
	<b>Gateway : </b>
	<?PHP
		print $containerDocker['responce']['NetworkSettings']['Gateway'];
	?>
	<?PHP
		$arraylength = count($containerDocker['responce']['HostConfig']['Dns']);
		if ( $arraylength > 0 ){
			print "<br/>";
			print "<b>Dns : </b>";
			for ($t=0;$t<$arraylength;$t++){
				print $containerDocker['responce']['HostConfig']['Dns'][$t]." ";
			}
		}
	?>
</div>
<div class='col-xs-1'>
	<br/>
</div>
<div class='col-xs-2 well'>
	<center>
		<?PHP 
			list($statusCont, $uptime) = explode(" ", $valueContainerConsul->status, 2);
			switch ($statusCont) {
				case "Up":
					$label="label-success";
					$stat=$valueContainerConsul->status;
					break;
				case "Exited":
					$label="label-warning";
					$stat=$valueContainerConsul->status;
					$icon="glyphicon glyphicon-play";
					break;
				default:                                                                                                                                                                          
					$label="label-danger";                                                                                                                                                          
					$stat="unknown";                                                                                                                                                                
					break;
			}
			print "<span class='label ". $label ."' style='font-size: 16px;'>". $stat ."</span>"; 
		?>
	</center>
</div>