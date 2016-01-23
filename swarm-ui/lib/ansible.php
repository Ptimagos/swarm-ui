<?PHP
/******************************************************************************
		Ensemble des fonctions ansible pour le site
******************************************************************************/

/**** Fonction setup Ansible Host Client ****/
 function ansibleCheck ($server,$dsclient,$fileout) {
  $command = "cd ".$server['ansible']['home'].";sudo ansible -i ".$server['ansible']['inventory']." ".$dsclient." -m setup | sed '1s/.*/{/' > ".$fileout." 2>&1";
  exec($command, $result);
  return $result;
 } // Fin de la fonction
 
/**** Fonction setup Ansible Host Client ****/
 function ansiblePlay ($server,$dsclient,$fileout,$role,$ansible_args) {
  $command = "cd ".$server['ansible']['home'].";sudo ansible-playbook -i ".$server['ansible']['inventory']." ".$server['ansible']['playbook']." "
	. "--limit ".$dsclient." --tags ".$role." ".$ansible_args." > ".$fileout." 2>&1";
  //print "Valeur de command : " .$command. "<br/>";
  exec($command, $op, $result);
  return $result;
 }
// Fin de la fonction

function testExec ($dir) {
	$command = "ls -l ".$dir;
	exec ($command, $result);
	return $result;
}

?>
