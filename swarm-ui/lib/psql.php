<?PHP
/******************************************************************************
		Ensemble des fonctions psql pour le site
******************************************************************************/

/**** Fonction de connexion a la base de donnees ****/
 function conPsql ($server) {
  $dbconn = pg_connect("host=".$server['psql']['host']." port=".$server['psql']['port']." dbname=".$server['psql']['db']." user=".$server['psql']['user']." password=".$server['psql']['pass']."")
	or die('Connexion impossible : ' . pg_last_error());
  
  return $dbconn;
 } // Fin de la fonction conPsql

/**** Fonction de deconnexion a la base de donnees ****/
 function decPsql ($dbconn) {
  pg_close($dbconn);
 } // Fin de la fonction de decPsql

/**** Fonction d'execution de requete dans la base Psql ****/
 function execRequetePsql ($req, $psqlConnexion) {
  $resultat = pg_query($psqlConnexion, $req) or die ("Execution de la requete impossible : "
  	. pg_last_error());
  return $resultat;
 } // Fin de la fonction execRequete

/**** Fonction de chargement des donnees d'une ligne dans un objet MYSQL ****/
 function ligneSuivantePsql ($res) {
  $resultat = pg_fetch_object($res);
  return $resultat; 
 } // Fin de la fonction ligneSuivantePsql

/**** Fonction de comptage du nombre de ligne dans dans un objet MYSQL ****/
 function nbLignePsql ($res) {
  $resultat = pg_num_rows($res);
  return $resultat;
} // Fin de la fonction nbLignePsql

/**** Fonction de liberation d'un resultat PSQL ****/
function libererPsqlResult ($res) {
  pg_free_result($res);
} // Fin de la fonction libererPsqlResult
?>
