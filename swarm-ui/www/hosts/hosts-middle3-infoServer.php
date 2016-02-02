<?php
	// ----- Information Docker ----- //
	$versionDocker = restRequestSSL("GET",$valueDocker->url,"/version");
?>
<div class='col-xs-8 well'>
	<h5><b>System :</b></h5>
	<b>Os : </b>
	<?php print $versionDocker['responce']['Os']." ".$versionDocker['responce']['Arch']; ?>
	<br/>
	<b>Kernel : </b>
	<?php print $versionDocker['responce']['KernelVersion']; ?>
	<br/>
</div>
<div class='col-xs-1'>
	<br/>
</div>
<div class='col-xs-3 well'>
	<b>Docker Daemon</b> version : <br/><br/>
	<center><span class='label label-success' style='font-size: 16px;'><?PHP print $versionDocker['responce']['Version']; ?></span></center>
</div>