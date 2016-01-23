<?PHP
/******************************************************************************
		Ensemble des fonctions ldap pour le site
******************************************************************************/

/***** Fonction de connexion au le serveur LDAP *****/
 function conLdap ($server) {
  // Connexion au serveur LDAP
  $connexion = ldap_connect($server[ldap][host])
  	or die ("Connexion au serveur LDAP impossible ... : ". ldap_error($connexion));
  
  // Configuration du protocol a utiliser :
  ldap_set_option($connexion, LDAP_OPT_PROTOCOL_VERSION, 3 );

  // Creation de la liaison avec le serveur LDAP
  ldap_bind($connexion, $server[ldap][userbind], $server[ldap][userbindpass])
  	or die ("La liaison a echouee ... : ". ldap_error($connexion));

  return $connexion;
 } // Fin de la fonction conLdap

/***** Fonction de deconnexion du serveur LDAP *****/
 function decLdap ($connexion) {
  ldap_close($connexion);
 } // Fin de la fonction decLdap

/***** Fonction de recherche dans le serveur LDAP *****/
 function rechercheLdap ($connexion, $basedn, $search, $attrs=NULL) {
  // Recherche dans le serveur LDAP
  if (is_null($attrs)) {
     $resultat = ldap_search($connexion, $basedn, $search)
  	or die ("La recherche dans le serveur LDAP a echouee ... : ". ldap_error($connexion));
  } else {
     $resultat = ldap_search($connexion, $basedn, $search, $attrs)
        or die ("La recherche dans le serveur LDAP a echouee ... : ". ldap_error($connexion));
  }

  return $resultat;
 } // Fin de la fonction rechercheLdap

/**** Fonction de lecture d'une recherche dans le serveur LDAP *****/
 function lecResultatLdap ($connexion, $resultat) {
  // Recupération du resultat dans un tableau
  $tab = ldap_get_entries($connexion, $resultat); 

  return $tab;
 } // Fin de la fonction lecResultatLdap

/***** Fonction de creation dans le serveur LDAP *****/
 function addLdap ($connexion, $dn, $create) {
  // Creation dans l'annuaire LDAP
  ldap_add($connexion, $dn, $create)
  	or die ("La creation a échoué ... ");
 }

/***** Fonction d'ajout dans un DN existant *****/
 function modAddLdap ($connexion, $dn, $modadd) {
  // Ajout dans un DN
  ldap_mod_add($connexion, $dn, $modadd)
  	or die ("La modification a échoué ... ");
 } // Fin de la fonction modAddLdap

/***** Fonction de renomage d'un RDN existant *****/
 function modRename ($connexion, $dn, $new_dn, $contenaire) {
  // Remplacement dans l'annuaire LDAP
  ldap_rename($connexion, $dn, $new_dn, $contenaire, TRUE)
       or die ("La modification a échoué ...");
 } // Fin de la fonction modReplace

/***** Fonction de modification dans le serveur LDAP *****/
 function modLdap ($connexion, $dn, $modif) {
  // Modification dans l'annuaire LDAP
  ldap_modify($connexion, $dn, $modif)
  	or die ("La modification a échoué ... ");
 } // Fin de la fonction modLdap

/***** Fonction de suppression d'attribut *****/
 function delAttLdap ($connexion, $dn, $delatt) {
  // Suppression d'attribut
  ldap_mod_del($connexion, $dn, $delatt)
  	or die ("La modification a échoué ... ");
} // Fin de la fonction delAttLdap

/***** Fonction de suppression d'une entree dans l'annuaire *****/
 function delLdap ($connexion, $dn) {
  // Suppression de l'entree
  ldap_delete($connexion, $dn)
  	or die ("La suppression a échoué ... ");
 } // Fin de la fonction delLdap

/***** Fonction de trie d'un resultat LDAP *****/
// $entrie => array
// $attrib => array
 function trieLdap (&$entries, $attribs) {
   for ($i=1; $i<$entries['count']; $i++){
       $index = $entries[$i];
       $j=$i;
       do {
           //create comparison variables from attributes:
           $a = $b = null;
           foreach($attribs as $attrib){
               $a .= $entries[$j-1][$attrib][0];
               $b .= $index[$attrib][0];
           }
           // do the comparison
           if ($a > $b){
               $is_greater = true;
               $entries[$j] = $entries[$j-1];
               $j = $j-1;
           }else{
               $is_greater = false;
           }
       } while ($j>0 && $is_greater);
      
       $entries[$j] = $index;
   }
   return $entries;
 } // Fin de la fonction treiLdap
?>
