<?PHP
	if ( !isset($server['projectName']) ) {
		session_start();
	}
	$valCollapse = $_GET['setTasksCollapse'];
	if ( isset($_SESSION['valCollapse'])) {
		if ( $_SESSION['valCollapse'] != $valCollapse ) {
			$_SESSION['valCollapse'] = $valCollapse;
		} else {
			unset($_SESSION['valCollapse']);
		}
	} else {
		$_SESSION['valCollapse'] = $valCollapse;
	}
?>