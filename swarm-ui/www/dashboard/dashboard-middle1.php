<?PHP
include "dashboard-middle1-get.php";
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
            <h3 class="panel-title">Dockers daemon / Swarm</h3>                                                                                
          </div>                                                                                                            

          <div class="panel-body placeholders">                                                                             
            <div class="col-xs-12 panel panel-default" style='padding: 0px;'>
              <div class="panel-heading">                                                                                       
                <h3 class="panel-title">Docker daemon and Swarm agent</h3>                                                                                
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
                  <span class="text-muted font-db font-db-warning"><?PHP print $dashboard_agents['stopped']; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Containers Docker</h3>
          </div>
          <div class="panel-body placeholders">
            <div class="col-xs-4 col-xs-offset-2 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Total</h3>
              </div>
              <span class="text-muted font-db font-db-default"><?PHP print $dashboard_instances['total']; ?></span>
            </div>
            <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Running</h3>
              </div>
              <span class="text-muted font-db font-db-success"><?PHP print $dashboard_instances['running']; ?></span>
            </div>
            <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Stopped</h3>
              </div>
              <span class="text-muted font-db font-db-warning"><?PHP print $dashboard_instances['stopped']; ?></span>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Tasks</h3>
          </div>
          <div class="panel-body placeholders">
            <div class="col-xs-4 col-xs-offset-3 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Waiting</h3>
              </div>
              <span class="text-muted font-db font-db-primary">0</span>
            </div>
            <div class="col-xs-4 col-xs-offset-1 col-sm-2 panel panel-default" style='padding-left: 0px; padding-right: 0px;'>
              <div class="panel-heading">
                <h3 class="panel-title">Running</h3>
              </div>
              <span class="text-muted font-db font-db-success">0</span>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!-- Middle Container -->

  </div>
</div>