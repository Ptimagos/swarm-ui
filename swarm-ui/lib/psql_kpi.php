<?PHP
/******************************************************************************
		Ensemble des fonctions psql pour le site
******************************************************************************/

/**** Fonction de connexion a la base de donnees ****/
 function conPsqlKpi ($server) {
  $dbconn = pg_connect("host=".$server['psqlkpi']['host']." port=".$server['psqlkpi']['port']." dbname=".$server['psqlkpi']['db']." user=".$server['psqlkpi']['user']." password=".$server['psqlkpi']['pass']."")
	or die('Connexion impossible : ' . pg_last_error());
  
  return $dbconn;
 } // Fin de la fonction conPsql
?>
