<?PHP

$getStatus = $_GET['getStatus'];
$progess = $_GET['progress'];

switch ($getStatus) {
	case "start" :
		print '<div class="col-xs-2"></div><div class="col-xs-8 progress">'
			. '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:100%">'
			. '<span class="sr-only"></span>'
			. '</div>'
			. '</div><div class="col-xs-2"></div>';
		break;
	case "success" :
		print '<div class="progress">'
			. '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:100%">'
			. '<span class="sr-only"></span>'
			. '</div>'
			. '</div>';
		break;
	case "failed" :
		print '<div class="progress">'
			. '<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:100%">'
			. '<span class="sr-only"></span>'
			. '</div>'
			. '</div>';
		break;
	case "canceled" :
		print '<div class="progress">'
			. '<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:100%">'
			. '<span class="sr-only"></span>'
			. '</div>'
			. '</div>';
		break;
	case "waiting" :
		print '<div class="progress">'
			. '<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width:100%">'
			. '<span class="sr-only"></span>'
			. '</div>'
			. '</div>';
		break;
}

?>