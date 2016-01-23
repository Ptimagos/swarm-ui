<?PHP
	include "hosts-middle1-get.php";
?>

<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
<div class="row">
<!-- Middle Container -->
<div class="col-xs-12" id="body-middle-container">
<h1 class="page-header">Hosts</h1>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Hosts's List</h3>
</div>
<div class="panel-body">
	<div class="btn-group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Action <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="#" data-toggle="modal" data-target="#addHost">Add Host</a></li>
			<li role="separator" class="divider"></li>
			<li class='disabled'><a href="#">Stop all Agents</a></li>
			<li class='disabled'><a href="#">Stop all Containers</a></li>
		</ul>
	</div>
</div>

<!-- Table -->
<table class="table table-striped">
<tr>
<th>#</th>
<th>Hostname</th>
<th>Host Status</th>
<th>Agent Status</th>
<th>Instances Status</th>
</tr>
<?PHP
	date_default_timezone_set('CET');
	for($t=1;$t<$num_serv;$t++)
	{
		$x = $host_hosts[$t]['id'];
		print "<tr>";
		print "<td>";
		print $x;
		print "</td>";
		print "<td>";
		print "<a onclick='loadHost(".$x.")' href='#'>".$host_hosts[$x]['hostname']."</a>";
		print "</td>";
		if ( $host_hosts[$x]['status_id'] == $status_name['installing'] ) {
			print "<td>";
			print "</td>";
			print "<td>";
			print "</td>";
			print "<td>";
			print "</td>";
			continue;
		}
		print "<td>";
		$label = status_color($status_id[$host_hosts[$x]['status_id']]);
		print "<span class='label label-".$label."' style='font-size: 95%;'>". $status_id[$host_hosts[$x]['status_id']] ."</span>";
		print "</td>";
		print "<td>";
		if ( $status_id[$host_hosts[$x]['status_id']] == "offline" )
		{
			print "<span class='label label-danger' style='font-size: 95%;'>".$status_id[$host_hosts[$x]['status_id']]."</span>";
		} else {
			if (isset($status_id[$host_hosts[$x]['agent'][$status_name['running']]])) {	
				print "<span class='label label-success' style='font-size: 95%;'>". $status_id[$host_hosts[$x]['agent'][$status_name['running']]] ."</span>";
			}
			if (isset($status_id[$host_hosts[$x]['agent'][$status_name['stopped']]])){
			print "<span class='label label-warning' style='font-size: 95%;'>". $status_id[$host_hosts[$x]['agent'][$status_name['stopped']]] ."</span>";
			}
			if (isset($host_hosts[$x]['agent']['unknown'])){
				print "<span class='label label-danger' style='font-size: 95%;'>". $host_hosts[$x]['agent']['unknown'] ."</span>";
			}
		}
		print "</td>";
		print "<td>";
		if ( $status_id[$host_hosts[$x]['status_id']] == "offline" )
		{
			print "<a href='#' style='text-decoration: none;' data-toggle='tooltip' data-placement='top' title='Instance(s) offline'><span class='label label-danger' style='font-size: 95%;'>". $status_id[$host_hosts[$x]['status_id']] ."</span></a>";
		} else {
			print "<a href='#' style='text-decoration: none;' data-toggle='tooltip' data-placement='top' title='Instance(s) running'><span class='label label-success' style='font-size: 95%;'>". $host_hosts[$x]['instances'][$status_name['running']] ."</span></a>";
			print "<a href='#' style='text-decoration: none;' data-toggle='tooltip' data-placement='top' title='Instance(s) stopped'><span class='label label-warning' style='font-size: 95%;'>". $host_hosts[$x]['instances'][$status_name['stopped']] ."</span></a>";
			print "<a href='#' style='text-decoration: none;' data-toggle='tooltip' data-placement='top' title='Instance(s) unknown'><span class='label label-danger' style='font-size: 95%;'>". $host_hosts[$x]['instances']['unknown'] ."</span></a>";
		}
		print "</td>";
		print "</tr>";
	}
?>
</table>
</div>
<script type="text/javascript">
function loadHost(item){
	var containerRootName= '#body-host-middle-container_000';
	var uri = '/dockerstation/hosts/hosts-middle3.php?host_id=' + item;
	var totalDashElem=$("ul").size()+1;
	var currentContainer=3;
	for (var i = 1; i < totalDashElem; i++ ) {
		if(i!=currentContainer){
			$(containerRootName+i).addClass('collapse');
			$(containerRootName+i).removeClass('collapse.in');
		}
	}
	$(containerRootName+currentContainer).removeClass('collapse');
	$(containerRootName+currentContainer).addClass('collapse.in');
    $(containerRootName+currentContainer).load(uri);
}
</script>
</div>

<!-- Middle Container -->
	
 </div>
</div>