<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Action <span class="caret"></span>
	</button>
	<ul class="dropdown-menu">
		<li><a href="#" data-toggle="modal" data-target="#addCont">Add Image</a></li>
		<?PHP
		if ($num_cont != 0){
			$createActive="class=''";
		} else {
			$createActive="class='disabled'";
		}
		print "<li ".$createActive."><a href='#' data-toggle='modal' data-target='#createCont'>Create Container</a></li>";
		?>
		<li role="separator" class="divider"></li>
		<?PHP
		switch ($agent_status) {
			case 'success':
				print "<li><a href='#' onclick=\"actionAgent('stop','".$host_id."')\" >Stop Agent</a></li>";
				break;
			default:
				print "<li><a href='#' onclick=\"actionAgent('start','".$host_id."')\" >Start Agent</a></li>";
				break;
		}
		?>
		<li role="separator" class="divider"></li>
		<li class='disabled'><a href="#">Start all Containers</a></li>
		<li class='disabled'><a href="#">Stop all Containers</a></li>
	</ul>
</div>