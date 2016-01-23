<?PHP
/******************************************************************************
		Ensemble des fonctions mysql pour le site
******************************************************************************/

/**** Fonction de connexion a la base de donnees ****/
 function conMysql ($server) {
  $connexion = mysql_connect($server['mysql']['host'], $server['mysql']['user'], $server['mysql']['pass'])
  	or die ("Connexion au serveur MYSQL impossible : ". mysql_error());
  
  mysql_select_db($server['mysql']['db'], $connexion) 
  	or die ("Connexion a la base de donnees impossible : ". mysql_error($connexion));
  
  return $connexion;
 } // Fin de la fonction conMysql

/**** Fonction de deconnexion a la base de donnees ****/
 function decMysql ($connexion) {
  mysql_close($connexion);
 } // Fin de la fonction de decMysql

/**** Fonction d'execution de requete dans la base MYSQL ****/
 function execRequete ($req, $connexion) {
  $resultat = mysql_query($req, $connexion) or die ("Execution de la requete impossible : "
  	. mysql_error());
  return $resultat;
 } // Fin de la fonction execRequete

/**** Fonction display error Mysql Query ****/
function errorRequete () {
  print "Execution de la requete impossible : "
  	. mysql_error()."<br/>";
}

/**** Fonction de chargement des donnees d'une ligne dans un objet MYSQL ****/
 function ligneSuivante ($res) {
  $resultat = mysql_fetch_object($res);
  return $resultat; 
 } // Fin de la fonction ligneSuivante

/**** Fonction de comptage du nombre de ligne dans dans un objet MYSQL ****/
 function nbLigne ($res) {
  $resultat = mysql_num_rows($res);
  return $resultat;
} // Fin de la fonction nbLigne
?>
