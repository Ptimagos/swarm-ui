<div class="loginmodal-container">
<h1><?PHP print $server['projectName'];?></h1><br>
<form  id="idLoginForm" class="form-4" action="tpl/login/login.php" method="post">
	<input type="text" id='username' name='username' placeholder="User" required>
	<input type="password" id='password' name='password' placeholder="Password" required> 
	<button name="submit" class="btn btn-default"  value="Continue" type="submit" style="width:100%">Login</button>
</form>
<br />
	<div class="row">
		<div class="col-md-12"><div id="resultLogin"></div>
	</div>
</div>

</div>
<script type="text/javascript">
    var frm = $('#idLoginForm');
    frm.submit(function (ev) { 
        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (data) {
				jQuery("#resultLogin").html(data);
				if(data.indexOf("connected") > -1){
					location.reload();
				}	 
			}
		});
	ev.preventDefault();
	});
</script>