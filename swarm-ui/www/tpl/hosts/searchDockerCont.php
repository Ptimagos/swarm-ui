<?php
	$aContext = array(
    	'http' => array(
        	'proxy' => 'tcp://niceway:3128',
        	'request_fulluri' => true,
    	),
	);
	$cxContext = stream_context_create($aContext);
	$url_docker = "https://index.docker.io/v1/search?q=".$_POST['search'];
	//$searchResult = file_get_contents($url_docker, False, $cxContext);
	$searchResult = file_get_contents($url_docker, False);
	$json_searchResult = json_decode($searchResult, true);
	$arrlength=count($json_searchResult['results']);
	$input_option="";
	for($x=0;$x<$arrlength;$x++){
		$input_option .= "<option value='".$json_searchResult['results'][$x]['name']." | ".$json_searchResult['results'][$x]['description']."'>"
			.$json_searchResult['results'][$x]['name']."</option>";
	}
?>
<form  id="installDockerImages" action="tpl/hosts/installDockerImg.php" method="post">
<div class="col-lg-12">
	<div class="input-group">
	   <select class="form-control" name="image">
		<option value=""></option>
        <?php print $input_option; ?>
	   </select>
	   <input type="hidden" name="host_id" value="<?PHP print $_POST['host_id']; ?>"></input>
	   <span class="input-group-btn">
	     <button id="btnInstall" onClick="installDockerImage()" class="btn btn-success glyphicon glyphicon-save" style="top:0;" type="button"></button>
	   </span>
  </div>
</div>
</form>
