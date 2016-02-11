<?php
if ( !isset($server['projectName']) ) {
  session_start();
  if ( !isset($_SESSION['login_user']) ) {
    header('Location: /');
    exit();
  }
  //Inclusion du fichier de configuration
  require "../../../cfg/conf.php";
  
  //Inclusion des differentes librairies
  require "../../../lib/fonctions.php";
  require "../../../lib/mysql.php";
  require "../../../lib/psql.php";
}
$searchImageResult = restRequestSSL("GET",$_POST['url'],"/images/search","?term=".$_POST['search']);
$arrlength=count($searchImageResult['responce']);
$input_option="";
for($x=0;$x<$arrlength;$x++){
  $nbCaracterDescribe = strlen($searchImageResult['responce'][$x]['description']);
  if ( $nbCaracterDescribe >= 41 ){
    $input_option .= "<option value='".$searchImageResult['responce'][$x]['name']."'>"
    .$searchImageResult['responce'][$x]['name']." --> ".substr($searchImageResult['responce'][$x]['description'], 0, 40)."...</option>";
  } else {
    $input_option .= "<option value='".$searchImageResult['responce'][$x]['name']."'>"
    .$searchImageResult['responce'][$x]['name']." --> ".$searchImageResult['responce'][$x]['description']."</option>";
  }
}
?>
<form  id="installDockerImages" action="tpl/hosts/actionImage.php" method="post">
  <div class="col-lg-12">
    <div class="input-group">
      <select class="form-control" name="image">
        <option value=""></option>
        <?php print $input_option; ?>
      </select>
      <span class="input-group-btn">
        <button id="btnInstall" onClick="actionImage()" class="btn btn-success glyphicon glyphicon-save" style="top:0;" type="button"></button>
      </span>
    </div>
    <br/>
    <input type="text" class="form-control" name='tags' placeholder="Tag latest"></input>
    <input type="hidden" name="hostId" value="<?PHP print $_POST['host_id']; ?>"></input>
    <input type="hidden" name="url" value="<?PHP print $_POST['url']; ?>"></input>
    <input type="hidden" name="actionImg" value="pull"></input>
    <input type="hidden" name="describeAction" value="Pull image"></input>
  </div>
</form>
