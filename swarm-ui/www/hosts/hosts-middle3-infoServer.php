<div class='col-xs-8 well'>
	<h5><b>System :</b></h5>
	<b>Distribution : </b>
	<?php print $infoServer['ansible_facts']['ansible_distribution']." ". $infoServer['ansible_facts']['ansible_distribution_version']; ?>
	<br/>
	<br/>
	<span class="col-xs-2"><b>Memory</b></span>
	<span class="col-xs-4"><b>Using</b></span>
	<span class="col-xs-2"><b>Used</b></span>
	<span class="col-xs-2"><b>Free</b></span>
	<span class="col-xs-2"><b>Total</b></span>
	<br/> 
	<?php
	$memoryFree = $infoServer['ansible_facts']['ansible_memfree_mb'];
	$memoryTotal = $infoServer['ansible_facts']['ansible_memtotal_mb'];
	$memoryUsed = $memoryTotal - $memoryFree ; 
	$memoryUsing = $memoryUsed / $memoryTotal * 100;
	?>
	<span class="col-xs-2">Physical</span>
	<span class="col-xs-4">
		<div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="<?PHP print $memoryUsing; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?PHP print $memoryUsing; ?>%">
				<?PHP print $memoryUsing; ?>%
			</div>
		</div>
	</span>
	<span class="col-xs-2"><?php print $memoryUsed; ?>Mo</span>
	<span class="col-xs-2"><?php print $memoryFree; ?>Mo</span>
	<span class="col-xs-2"><?php print $memoryTotal; ?>Mo</span>
	<br/>
	<br/>
	<h5><b>Network :</b></h5>
	<b>Server Ip Address : </b><?php print $infoServer['ansible_facts']['ansible_default_ipv4']['address']; ?>
	<br/>
	<b>Docker Ip Address : </b><?php print $infoServer['ansible_facts']['ansible_docker0']['ipv4']['address']; ?>
	&nbsp;(<?php print $infoServer['ansible_facts']['ansible_docker0']['type']; ?>)
	<br/>
</div>
<div class='col-xs-1'>
	<br/>
</div>
<div class='col-xs-3 well'>
	<b>Agent DockerStation</b> version : <br/><br/>
	<center><span class='label label-<?PHP print $agent_status; ?>' style='font-size: 16px;'><?PHP print $agent_version[$agent_id]['version']; ?></span></center>
</div>
</div>