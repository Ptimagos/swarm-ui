<?php
  session_start();
  //Inclusion du fichier de configuration
  require "../../../cfg/conf.php";

  $uri = $server['setup']['uri'];
  session_destroy();
  header("location:".$uri);
?>

