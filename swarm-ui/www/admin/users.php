
<div id="wrapper">     
	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<ul id="sidebar_menu" class="sidebar-nav">
			<li class="sidebar-brand"><a id="menu-toggle" href="#"><span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
		</ul>
		<ul class="sidebar-nav" id="sidebar">     
			<li class="active"><a href="#" id="users_elem_0001" data-toggle="tooltip" data-placement="right" title="Users">Users<span id="main_icon" class="glyphicon glyphicon-user"></span></a></li>
			<li><a href="#" id="users_elem_0002" data-toggle="tooltip" data-placement="right" title="Parameters">Parameters<span id="main_icon" class="glyphicon glyphicon-cog"></span></a></li>
		</ul>
	</div>
          
      <!-- Page content -->
      <div id="page-content-wrapper">
        <!-- Keep all page content within the page-content inset div! -->



	 <!-- Middle Container -->
    <div class="col-xs-12 collapse.in" id="body-users-middle-container_0001">
		<?PHP include "users-middle1.php"; ?>
	</div>
	<div class="col-xs-12 collapse" id="body-users-middle-container_0002">
		<?PHP include "users-middle2.php"; ?>
	</div>
	
        <!-- End Middle Container -->
      </div>
      
    </div>
<script type="text/javascript">
    var containerRootName="#body-users-middle-container_000";
    var totalDashElem=$("ul").size()+1;
    $(document).ready(function(){
		$("body").tooltip({ selector: '[data-toggle=tooltip]', trigger: "hover" });
        $("#users_elem_0001").click(function(){
			var currentContainer=1;
			var element = $(this).parent('li');
            element.addClass('active');
            element.siblings('li').removeClass('active');
            element.siblings('li').find('li').removeClass('active');
			for (var i = 1; i < totalDashElem; i++ ) {
				if(i!=currentContainer){
					$(containerRootName+i).addClass('collapse');
					$(containerRootName+i).removeClass('collapse.in');
				}
			}
			$(containerRootName+currentContainer).removeClass('collapse');
			$(containerRootName+currentContainer).addClass('collapse.in');
		});
		$("#users_elem_0002").click(function(){
			var currentContainer=2;
			var element = $(this).parent('li');
            element.addClass('active');
            element.siblings('li').removeClass('active');
            element.siblings('li').find('li').removeClass('active');
			for (var i = 1; i < totalDashElem; i++ ) {
				if(i!=currentContainer){
					$(containerRootName+i).addClass('collapse');
					$(containerRootName+i).removeClass('collapse.in');
				}
			}
			$(containerRootName+currentContainer).removeClass('collapse');
			$(containerRootName+currentContainer).addClass('collapse.in');
		});
	});
	$("#menu-toggle").click(function(e) {
		e.preventDefault();
		$("#wrapper").toggleClass("active");
	});
</script>


