<?PHP
if ( !isset($server['projectName']) ) {
  session_start();
  if ( !isset($_SESSION['login_user']) ) {
    header('Location: /');
      exit();
  }
  //Inclusion du fichier de configuration
  require "../../cfg/conf.php";
  
  //Inclusion des differentes librairies
  require "../../lib/fonctions.php";
  require "../../lib/mysql.php";
  require "../../lib/psql.php";
}

$current_time = time();

// ----- Host Docker ----- //
$nodes = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/nodes","?recurse");
$nodesStatus = getNumberUpOrDown($nodes['responce'],"Healthy");
$dashboard_hosts['total'] = $nodesStatus['total'];
$dashboard_hosts['running'] = $nodesStatus['running'];
$dashboard_hosts['offline'] = $nodesStatus['down'];

// ----- Swarm manager ----- //
$swarmsManger = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/swarm-manager","?recurse");
$swarmsManagerStatus = getNumberUpOrDown($swarmsManger['responce'],"Up");
$dashboard_managers['total'] = $swarmsManagerStatus['total'];
$dashboard_managers['running'] = $swarmsManagerStatus['running'];
$dashboard_managers['stopped'] = $swarmsManagerStatus['down'];

// ----- Swarm Agent ----- //
$swarmsAgent = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/swarm-agent","?recurse");
$swarmsAgentStatus = getNumberUpOrDown($swarmsAgent['responce'],"Up");
$dashboard_agents['total'] = $swarmsAgentStatus['total'];
$dashboard_agents['running'] = $swarmsAgentStatus['running'];
$dashboard_agents['stopped'] = $swarmsAgentStatus['down'];

// ----- Containers Docker ----- //
$containers = restRequest("GET",$server['consul']['url'],"/v1/kv/docker/swarm-ui/containers","?recurse");
$containersStatus = getNumberUpOrDown($containers['responce'],"Up");
$dashboard_containers['total'] = $containersStatus['total'];
$dashboard_containers['running'] = $containersStatus['running'];
$dashboard_containers['stopped'] = $containersStatus['down'];

?>

<!-- Main component for a primary marketing message or call to action -->
<div class="container-fluid">
  <div class="row">
    <!-- Middle Container -->
    <div class="col-xs-12" id="body-middle-container">

      <h1 class="page-header">Dashboard</h1>
      <div class="row ">

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Containers Docker</h3>
          </div>
          <div class="panel-body placeholders">
            <div class="col-xs-4 col-xs-offset-2 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Total</h3>
              </div>
              <span class="text-muted font-db font-db-default"><?PHP print $dashboard_containers['total']; ?></span>
            </div>
            <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Running</h3>
              </div>
              <span class="text-muted font-db font-db-success"><?PHP print $dashboard_containers['running']; ?></span>
            </div>
            <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Stopped</h3>
              </div>
              <span class="text-muted font-db font-db-warning"><?PHP print $dashboard_containers['stopped']; ?></span>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Dockers daemon / Swarm</h3>
          </div>
          <div class="panel-body placeholders">
            <div class="col-xs-12 panel panel-default" style='padding: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Docker daemon</h3>
              </div>
              <div class="panel-body placeholders">
                <div class="col-xs-4 col-xs-offset-2 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Total</h3>
                  </div>
                  <span class="text-muted font-db font-db-default"><?PHP print $dashboard_hosts['total']; ?></span>
                </div>
                <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Running</h3>
                  </div>
                  <span class="text-muted font-db font-db-success"><?PHP print $dashboard_hosts['running']; ?></span>
                </div>
                <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Offline</h3>
                  </div>
                  <span class="text-muted font-db font-db-danger"><?PHP print $dashboard_hosts['offline']; ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="panel-body placeholders">
            <div class="col-xs-12 panel panel-default" style='padding: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Swarm Manager</h3>
              </div>
              <div class="panel-body placeholders">
                <div class="col-xs-4 col-xs-offset-2 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Total</h3>
                  </div>
                  <span class="text-muted font-db font-db-default"><?PHP print $dashboard_managers['total']; ?></span>
                </div>
                <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Running</h3>
                  </div>
                  <span class="text-muted font-db font-db-success"><?PHP print $dashboard_managers['running']; ?></span>
                </div>
                <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Stopped</h3>
                  </div>
                  <span class="text-muted font-db font-db-danger"><?PHP print $dashboard_managers['stopped']; ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="panel-body placeholders">
            <div class="col-xs-12 panel panel-default" style='padding: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Swarm Agent</h3>
              </div>
              <div class="panel-body placeholders">
                <div class="col-xs-4 col-xs-offset-2 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Total</h3>
                  </div>
                  <span class="text-muted font-db font-db-default"><?PHP print $dashboard_agents['total']; ?></span>
                </div>
                <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Running</h3>
                  </div>
                  <span class="text-muted font-db font-db-success"><?PHP print $dashboard_agents['running']; ?></span>
                </div>
                <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
                  <div class="panel-heading">
                    <h3 class="panel-title">Stopped</h3>
                  </div>
                  <span class="text-muted font-db font-db-danger"><?PHP print $dashboard_agents['stopped']; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!-- Middle Container -->

  </div>
</div>