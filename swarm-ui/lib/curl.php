<?PHP
/******************************************************************************
		Ensemble des fonctions curl 
******************************************************************************/

/**** Fonction de curl ****/
 function curlRequest($method,$url,$uri,$querry=NULL,$json=NULL,$option=NULL) {

  // Initialize options for REST interface
  $curl_option_default = array(
    CURLOPT_HEADER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 2
  );

  // Connect
  if(!isset($curl_handle)) $curl_handle = curl_init();

  // Compose querry
  $options = array(
    CURLOPT_URL => $url.$uri."?".$querry,
    CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS 
    CURLOPT_POSTFIELDS => $json,
  ); 
  curl_setopt_array($curl_handle,($options + $curl_option_defaults));

  // send request and wait for responce
  $responce =  json_decode(curl_exec($curl_handle),true);

  // Return resultat
  return $responce

 } // Fin de la fonction curlRequest
?>
