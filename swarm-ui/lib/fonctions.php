<?PHP
/******************************************************************************
		Ensemble des fonctions principal pour le site
******************************************************************************/
/*****  *****/
 function random_salt( $length )
 {
        $possible = '0123456789'.
                        'abcdefghijklmnopqrstuvwxyz'.
                        'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.
                        './';
        $str = "";
        mt_srand((double)microtime() * 1000000);
        while( strlen( $str ) < $length )
        {
                $str .= substr( $possible, ( rand() % strlen( $possible ) ), 1 );
        }
        /**
         * Commented out following line because of problem
         * with crypt function in update.php
         * --- 20030625 by S C Rigler <srigler@houston.rr.com> ---
         */
        //$str = "\$1\$".$str."\$";
        return $str;
}

/**** Function getUserPassword in CONSUL ****/                                                  
function getUserPassword($username,$server){
  $url = $server['consul']['url'];
  $uri = $server['consul']['store_keys']."/users/".$username."/passwd";
  $userPasswd = restRequest("GET",$url,$uri);

  // Return result               
  return $userPasswd;
}

/**** Function getUserInfo CONSUL ****/
function getUserInfo($username,$key,$server){
  $url = $server['consul']['url'];
  $uri = $server['consul']['store_keys']."/users/".$username."/".$key;
  $userInfo = restRequest("GET",$url,$uri);

  // Return result
  return $userInfo;
}

/**** Function getNumberUpOrDown CONSUL ****/
function getNumberUpOrDown($nodes,$stat){
  $nb_nodes = count($nodes);
  $nb_nodes_running=0;
  $nb_nodes_down=0;
  for($x = 0; $x < $nb_nodes; $x++){
    $nodeValue = base64_decode($nodes[$x]['Value']);
    $value = json_decode($nodeValue);
    if (isset($value->status) && $value->status == $stat ){
      $nb_nodes_running++;
    } else {
      $nb_nodes_down++;
    }  
  }
  $numberUpOrDown['total'] = $nb_nodes;
  $numberUpOrDown['running'] = $nb_nodes_running;
  $numberUpOrDown['down'] = $nb_nodes_down;

  // Return result
  return $numberUpOrDown;
}

/**** Function getAlarmStatus CONSUL ****/
function getAlarmStatus($name,$table,$server){
  $url = $server['consul']['url'];
  $uri = $server['consul']['store_keys']."/alarms/unset/".$table."/".$name;
  $alarmSet = restRequest("GET",$url,$uri);
  return count($alarmSet);
}

/**** Function unSetAlarm CONSUL ****/
function unSetAlarm($name,$table,$server){
  $url = $server['consul']['url'];
  $uri = $server['consul']['store_keys']."/alarms/unset/".$table."/".$name;
  restRequest("PUT",$url,$uri,"","unSetAlarm");
}

/**** Function setAlarm CONSUL ****/
function setAlarm($name,$table,$server){
  $url = $server['consul']['url'];
  $uri = $server['consul']['store_keys']."/alarms/unset/".$table."/".$name;
  restRequest("DELETE",$url,$uri);
}

/**** Function setNode CONSUL ****/
function setNode($nodeName,$nodeSet,$server){
  $url = $server['consul']['url'];
  $uri = $server['consul']['store_keys']."/nodes/".$nodeName;
  restRequest("PUT",$url,$uri,"",$nodeSet);
}

/**** Function setSwarm CONSUL ****/                                    
function setSwarm($nodeName,$swarmSet,$server){                         
  $url = $server['consul']['url'];                                    
  $uri = $server['consul']['store_keys']."/swarm-manager/".$nodeName;         
  restRequest("PUT",$url,$uri,"",$swarmSet);                           
} 

/**** Function setContainer CONSUL ****/                                    
function setContainer($containerID,$containerSet,$server){                         
  $url = $server['consul']['url'];                                    
  $uri = $server['consul']['store_keys']."/containers/".$containerID;         
  restRequest("PUT",$url,$uri,"",$containerSet);                           
} 

/**** Fonction de curl ****/                  
function restRequest($method,$url,$uri,$querry=NULL,$json=NULL,$option=NULL) { 
                                          
  // Initialize options for REST interface
  $curl_option_defaults = array(    
    CURLOPT_HEADER => false,       
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 2                              
  );                                                  
                                                      
  // Connect                                          
  if(!isset($curl_handle)) $curl_handle = curl_init();
                                    
  // Compose querry                 
  $options = array(                                            
    CURLOPT_URL => $url.$uri."".$querry,                                      
    CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS
    CURLOPT_SSL_VERIFYPEER => false, // No Verify SSL
    CURLOPT_POSTFIELDS => $json,
  );
  curl_setopt_array($curl_handle,($options + $curl_option_defaults));

  // send request and wait for responce 
  $responce =  json_decode(curl_exec($curl_handle),true);

  // Return resultat                
  return $responce;
                                    
} // Fin de la fonction curlRequest


/***** Fonction colors status ******/
function status_color ($status)
{
	switch ($status) {
		case "running":
			$status_color="success";
			break;
		case "stopped":
			$status_color="warning";
			break;
		case "offline":
			$status_color="danger";
			break;
		case "unknown":
			$status_color="danger";
			break;
	}
	return $status_color;
}

/***** Fonction bar de progression *****/
function getBarProgress ($getStatus,$progress) 
{
	switch ($getStatus) {
		case "starting" :
			$barProgress = '<div class="progress" style="margin-bottom: 0px;">'
				. '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$progress.'%">'
				. '<span class="sr-only"></span>'
				. '</div>'
				. '</div>';
			break;
		case "success" :
			$barProgress = '<div class="progress" style="margin-bottom: 0px;">'
				. '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:100%">'
				. '<span class="sr-only"></span>'
				. '</div>'
				. '</div>';
			break;
		case "failed" :
			$barProgress = '<div class="progress" style="margin-bottom: 0px;">'
				. '<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:100%">'
				. '<span class="sr-only"></span>'
				. '</div>'
				. '</div>';
			break;
		case "canceled" :
			$barProgress = '<div class="progress" style="margin-bottom: 0px;">'
				. '<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:100%">'
				. '<span class="sr-only"></span>'
				. '</div>'
				. '</div>';
			break;
		case "waiting" :
			$barProgress = '<div class="progress" style="margin-bottom: 0px;">'
				. '<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$progress.'%">'
				. '<span class="sr-only"></span>'
				. '</div>'
				. '</div>';
			break;
	}
	
	return $barProgress;
}


/***** Fonction de creation du homedir utilisateur *****/
 function mkhomedir($homedir, $uid, $groupuid)
 {
  $msg="Ok";
  $sudo="/usr/bin/sudo";
  $mkdir="/bin/mkdir";
  $chown="/bin/chown";

  exec("$sudo $mkdir $homedir 0700");
  exec("$sudo $chown $uid:$groupuid $homedir"); 
  
  return $msg;
 }

/***** Fonction de comparaison de chaine *****/
 function compare($a, $b)
 {
  if ($a == $b) return 0;
  return ($a > $b) ? -1 : 1;
 }

?>
