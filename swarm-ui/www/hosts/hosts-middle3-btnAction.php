<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Action <span class="caret"></span>
	</button>
	<ul class="dropdown-menu">
		<li><a href="#" data-toggle="modal" data-target="#addImage">Add Image</a></li>
		<?PHP
		if ($nb_containerDocker != 0){
			$createActive="class=''";
		} else {
			$createActive="class='disabled'";
		}
		print "<li ".$createActive."><a href='#' data-toggle='modal' data-target='#createCont'>Create Container</a></li>";
		?>
		<li role="separator" class="divider"></li>
		<li class='disabled'><a href="#">Start all Containers</a></li>
		<li class='disabled'><a href="#">Stop all Containers</a></li>
	</ul>
</div>