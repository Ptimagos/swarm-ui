<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type='text/javascript' src='js/jquery.min.js'></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type='text/javascript' src='js/bootstrap.min.js'></script>

<!-- Loader animation 
<script type='text/javascript' src='js/canvas.js'></script>
-->

<!-- Highcharts 
<script type="text/javascript" src="js/highstock.js"></script>
<script type="text/javascript" src="js/modules/exporting.js"></script>
<script type="text/javascript" src="js/highslide-full.min.js"></script>
<script type="text/javascript" src="js/highslide.config.js" charset="utf-8"></script>
<script type="text/javascript" src="js/highcharts-more.js"></script>
-->

<!-- global variable conf.js 
<script type="text/javascript" src="js/conf.js"></script>
<!-- Operation dashboard 
<script type="text/javascript" src="js/dashboard_badge.js"></script>
<!-- Login ajax 
<script type="text/javascript" src="js/login.js"></script>
-->

<!-- loading modal 
<script type="text/javascript" src="js/loading.js"></script>
-->

<!-- Hidden the loader
<script type='text/javascript'>
	function load(){
		document.getElementById('loaderImage').style.display='none'
		document.getElementById('MainTabLoader').style.display='none'
	}
</script>
-->
<!-- Include Jquery here in a script tag -->

<script type="text/javascript">
function actionAgent(action,host_id) {
  $('#tasksAction').modal('show');
  url = "tpl/hosts/actionAgent.php";
  method = "post";
  $.ajax({
    type: method,
    url: url,
    data: {actionCont: action,hostId: host_id},
    success: function (data) {
      jQuery("#actionCont").html(data);
      setTimeout(function(){
        $('#tasksAction').modal('hide');
      }, 2000);
      setTimeout(function(){
        $('#body-middle-container_0002').load('dashboard/dashboard-middle2.php');
        $("#body-host-middle-container_0003").load("hosts/hosts-middle3.php");
      }, 2300);
    }  
  });
}
</script>

