<!-- Modal -->
<div id="addHost" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Host Client</h4>
      </div>
	  <form  id="addHostForm" action="tpl/hosts/addHostClient.php" method="post">
      <div class="modal-body">
		  <div class="input-group">
		    <span class="input-group-addon" id="basic-addon1"></span>
			<input type="text" class="form-control" name='hostname' placeholder="Hostname / FQDN" aria-describedby="basic-addon1">
		  </div>
		  <br />
		  <div class="input-group">
		    <span class="input-group-addon" id="basic-addon1"></span>
			<input type="text" class="form-control" name='ipaddr' placeholder="IP Address" aria-describedby="basic-addon1">
		  </div>
		<br/>
		<input type="hidden" name='job_id' value="<?PHP print time() + rand(); ?>">
		<div class="row">
		  <div class="col-md-12">
		    <div id="resultAddHost"></div>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
	    <button id="#addDone" name="submit" class="btn btn-success"  value="Continue" type="submit">Done</button>
        <button id="#addCancel" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
	</form>  
    </div>
  </div>
</div>
<!-- end modal -->

<!-- Modal -->
<div id="registerHost" class="modal fade" data-backdrop="static" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <!--<div class="modal-header">
        <h4 class="modal-title">Registering Host</h4>
      </div>-->
      <div class="modal-body">
		<div class="row">
		  <div class="col-md-12">
			<div id="addedHost"></div>
		  </div>
		</div>
      </div>
      <!--<div class="modal-footer">
	    <button id="#registerDone" type="button" class="btn btn-success" data-dismiss="modal">Done</button>
      </div>-->
    </div>
  </div>
</div>
<!-- end modal -->


<script type="text/javascript">
    var frm = $('#addHostForm');
    frm.submit(function (ev) { 
        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (data) {
				jQuery("#resultAddHost").html(data);
				if(data.indexOf("Registering host") > -1){
					$('#registerHost').modal('show');
					$('#addHost').modal('hide');
					var url_post = 'tpl/hosts/addHostTasks.php';
					$.ajax({
						type: frm.attr('method'),
						url: url_post,
						data: frm.serialize(),
						success: function (data) {
							jQuery("#addedHost").html(data);
							setTimeout(function(){
								$('#registerHost').modal('hide');
							}, 2000);
							setTimeout(function(){
								$("#body-host-middle-container_0001").load("hosts/hosts-middle1.php");
							}, 2300);
						}
					});
				}	
			}
		});
	ev.preventDefault();
	});
</script>