<?PHP
	/******************************************************************************
	index.php : DockerStation site
	*******************************************************************************
	$Log$
	******************************************************************************/
	session_start();
	//Inclusion du fichier de configuration
	require "../cfg/conf.php";
	
	//Inclusion des differentes librairies
	require "../lib/fonctions.php";
	require "../lib/mysql.php";
	require "../lib/psql.php";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?PHP 
		// Chargement des fichier CSS :
		include 'common/css.php';
	?>
	<title><?PHP print $server['setup']['title'] ?></title>
	<meta name='description' content='supervision des plateformes integration' />
	<meta http-equiv='language' content='en' />
	<?PHP 
		// Chargement des javascript
		include 'common/javascript.php';
	?>
	</head>
	<body>
	<?PHP
		include 'dashboard/dashboard-middle1-get.php';
	?>
	</body>
</html>

