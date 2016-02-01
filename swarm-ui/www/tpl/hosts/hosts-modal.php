<!-- Modal -->
<div id="addImage" class="modal fade" data-backdrop="static" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Image</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<form  id="searchCont" action="tpl/hosts/searchImages.php" method="post">
						<div class="col-md-12">
							<div class="input-group">
								<input type="hidden" name="host_id" value="<?PHP print $valueDocker->name; ?>">
								<input type="text" name="search" class="form-control" placeholder="Search image...">
								<span class="input-group-btn">
									<button id="btnSearch" onClick="searchDockerCont()" class="btn btn-default glyphicon glyphicon-search" style="top:0;" type="button"></button>
								</span>
							</div>
						</div>
					</form>
				</div>
				<br/>
				<div id="resultSearch" class="row">
				</div>
			</div>
			<div class="modal-footer">
				<button id="#addCancel" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>  
		</div>
	</div>
</div>
<!-- end modal -->

<!-- Modal -->
<div id="tasksInstallImg" class="modal fade" data-backdrop="static" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="addedImage"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end modal -->

<!-- Modal -->
<div id="createCont" class="modal fade" data-backdrop="static" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Create Container</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<form  id="formCreateCont" action="tpl/hosts/createDockerCont.php" method="post">
						<div class="col-md-12">
							<input type="hidden" name="host_id" value="<?PHP print $host_id; ?>">
							<select class="form-control" name="image">
								<option value=""></option>
								<?php print $formCreateCont_inputOption; ?>
							</select>
							<br/>
							<input type="text" name="options" class="form-control" placeholder="Options ...">
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button id="btnCreateCont" onClick="createDockerCont()" class="btn btn-success" type="button">Create</button>
				<button id="#addCancel" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>  
		</div>
	</div>
</div>
<!-- end modal -->

<!-- Modal -->
<div id="tasksCreateCont" class="modal fade" data-backdrop="static" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="createdCont"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end modal -->


<script type="text/javascript">
$("form").keypress(function(e) {
  //Enter key
  if (e.which == 13) {
  	return false;
  }
});
function createDockerCont() {
	$('#createCont').modal('hide');
	$('#tasksCreateCont').modal('show');
	var frm5 = $("#formCreateCont");
	$.ajax({
		type: frm5.attr('method'),
		url: frm5.attr('action'),
		data: frm5.serialize(),
		success: function (data) {
			jQuery("#createdCont").html(data);
			setTimeout(function(){
				$('#tasksCreateCont').modal('hide');
			}, 2000);
			setTimeout(function(){
				$("#body-host-middle-container_0003").load("hosts/hosts-middle3.php?host_id=<?PHP print $host_id; ?>");
			}, 2300);
		}	 
	});
}
function installDockerImage() {
	$('#addImage').modal('hide');
	$('#tasksInstallImg').modal('show');
	var frm4 = $("#installDockerImages");
	$.ajax({
		type: frm4.attr('method'),
		url: frm4.attr('action'),
		data: frm4.serialize(),
		success: function (data) {
			jQuery("#addedImage").html(data);
			setTimeout(function(){
				$('#tasksInstallImg').modal('hide');
			}, 2000);
			setTimeout(function(){
				$("#body-host-middle-container_0003").load("hosts/hosts-middle3.php?host_id=<?PHP print $host_id; ?>");
			}, 2300);
		}	 
	});
}
function searchDockerCont() {
	$("#btnSearch").addClass('disabled');
	$("#resultSearch").load('tpl/actionsBarResult.php?getStatus=starting&progress=100');
	var frm3 = $("#searchCont");
	$.ajax({
		type: frm3.attr('method'),
		url: frm3.attr('action'),
		data: frm3.serialize(),
		success: function (data) {
			$("#btnSearch").removeClass('disabled');
			jQuery("#resultSearch").html(data);
		}	 
	});
}
</script>
