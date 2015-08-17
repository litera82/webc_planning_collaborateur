<?php
/**
* @package		Reghalal
* @subpackage 	commun
* @version  1
* @author NEOV
*/

/**
* Classe utile dans tout le site 
* @package 		Reghalal
* @subpackage 	commun
*/
class communSrv{
   
  /**
  * chargement du fil d'ariane
  * @param   array  $tMenu        tableau vide initial
  * @return	array  $tFilAriane   tableau contenant le fil
  **/	 	 
	static function chargerFilAriane ($tMenu = array()) {
		global $gJCoord;
		jClasses::inc('commun~MenuItem');
		// fil d'Ariane
		$tFilAriane = array();
		switch ($tMenu[0]) {
			// menu accueil
			case HtmlFoResponse::MENU_ACCUEIL:
				array_push($tFilAriane, new MenuItem('Accueil', 'Accueil', jUrl::get('accueil~accueilFo:index'), ''));
				if (isset($tMenu[1])){
					switch ($tMenu[1]) {
						case HtmlFoResponse::MENU_GARANTIE_HALAL:
							array_push($tFilAriane, new MenuItem('Garantie Halal', 'Garantie Halal', jUrl::get('garantie~garantieHalalFo:index'), ''));
							if (isset($tMenu[2])){
								switch ($tMenu[2]) {
									case HtmlFoResponse::MENU_LIEN_UTILE:
										array_push($tFilAriane, new MenuItem('Liens utiles', 'Liens utiles', jUrl::get('garantie~garantieHalalFo:liensUtiles'), ''));
									break;
									case HtmlFoResponse::MENU_QUESTION_FREQUENTE:
										array_push($tFilAriane, new MenuItem('Questions Fréquentes', 'Questions Fréquentes', jUrl::get('garantie~garantieHalalFo:questionFrequente'), ''));
									break;
								}
							}
						break;
						//Page introuvable
						case HtmlFoResponse::MENU_NOT_FOUND:
							array_push($tFilAriane, new MenuItem('Page introuvable', 'Page introuvable', '#', ''));
						break;
						//espace reghalal
						case HtmlFoResponse::MENU_ESPACE:
							array_push($tFilAriane, new MenuItem('Espace Réghalal', 'Espace Réghalal', jUrl::get('espace~espaceFo:index'), ''));
							if (isset($tMenu[2])){
								switch ($tMenu[2]) {
									case HtmlFoResponse::MENU_OFFRE:
										array_push($tFilAriane, new MenuItem('Les offres du moment', 'Les offres du moment', jUrl::get('offre~offreFo:offreDetail'), ''));
									break;
									case HtmlFoResponse::MENU_ESPACE_TEST_CONSOMMATEUR:
										array_push($tFilAriane, new MenuItem('Les tests consommateur', 'Les tests consommateur', jUrl::get('espace~espaceFo:espaceTestConsommateur', array('iCurrentPage'=>1)), ''));
										if(isset($tMenu[3])){
											switch ($tMenu[3]){
												case HtmlFoResponse::MENU_ESPACE_TEST_CONSOMMATEUR_FICHE:
													$iTestId = $gJCoord->request->params['iTestId'];
													jClasses::inc('espace~reghalal_testSrv');
													$oTest = reghalal_testSrv::chargeParId($iTestId);
													array_push($tFilAriane, new MenuItem($oTest->test_prenom, $oTest->test_prenom, '#', ''));
												break;
											}
										}
									break;
									case HtmlFoResponse::MENU_ESPACE_MES_INFOS:
										array_push($tFilAriane, new MenuItem('Mes infos', 'Mes infos', '#', ''));
									break;
								}
							}
						break;
						//produit reghalal 
						case HtmlFoResponse::MENU_PRODUIT_HALAL:
							array_push($tFilAriane, new MenuItem('Nos Produits Halal', 'Nos Produits Halal', jUrl::get('produit~produitFo:index'), ''));
							if (isset($tMenu[2])){
								switch ($tMenu[2]) {
									case HtmlFoResponse::MENU_PRODUIT_HALAL_VOLLAILE:
										array_push($tFilAriane, new MenuItem('Volaille Halal', 'Volaille Halal', '#', ''));
									break;
									case HtmlFoResponse::MENU_PRODUIT_HALAL_CHARCUTERIE:
										array_push($tFilAriane, new MenuItem('Charcuterie Halal', 'Charcuterie Halal', '#', ''));
									break;
									
									case HtmlFoResponse::MENU_PRODUIT_HALAL_TRAITEUR:
										array_push($tFilAriane, new MenuItem('Traiteur Halal', 'Traiteur Halal', '#', ''));
									break;
								}
							}
						break;
						//recette halal
						case HtmlFoResponse::MENU_RECETTE:
							array_push($tFilAriane, new MenuItem('Les recettes Réghalal', 'Les recettes Réghalal', jUrl::get('recette~recetteFo:index', array('iCurrentPage'=>1)), ''));
							if (isset($tMenu[2])){
								switch ($tMenu[2]) {
									case HtmlFoResponse::MENU_RECETTE_FICHE:
										$iRecetteId = $gJCoord->request->params['iRecetteId'];
										jClasses::inc('recette~recetteSrv');
										$oRecette = recetteSrv::chargeParId($iRecetteId);
										array_push($tFilAriane, new MenuItem($oRecette->recette_libelle, $oRecette->recette_libelle, '#', ''));
											
									break;
								}
							}
						break;
						//plan site
						case HtmlFoResponse::MENU_PLAN_SITE:
							array_push($tFilAriane, new MenuItem('Plan Site', 'Plan Site', '#', ''));
						break;
					}
				}
			break;    

			//menu 404
			case HtmlFoResponse::MENU_PAGE_404:
				array_push($tFilAriane, new MenuItem('Page non trouvée', 'Page non trouvée', '#', 'selected'));
			break;
		}
		return $tFilAriane;
	}

  /**
  * chargement du title de la page 
  * @param   array  $tTitle
  * @return	string  $zTitle
  **/	 	 
	static function chargerTitle ($tTitle = array()) {
		global $gJCoord;
		// fil d'Ariane
		switch ($tTitle[0]) {
			// menu accueil
			case HtmlFoResponse::MENU_ACCUEIL:
				$zTitle = "Réghalal";
				if (isset($tTitle[1])){
					switch ($tTitle[1]) {
						case HtmlFoResponse::MENU_GARANTIE_HALAL:
							if (isset($tTitle[2])){
								switch ($tTitle[2]) {
									case HtmlFoResponse::MENU_LIEN_UTILE:
										 $zTitle .= " : Liens utiles";
									break;
									case HtmlFoResponse::MENU_QUESTION_FREQUENTE:
										 $zTitle .= " : Questions fréquentes";
									break;
								}
							}else{
								$zTitle .= " : Garantie Halal";
							}
						break;
						//espace reghalal
						case HtmlFoResponse::MENU_ESPACE:
							if (isset($tTitle[2])){
								switch ($tTitle[2]) {
									case HtmlFoResponse::MENU_OFFRE:
										$zTitle .= " : bons de réduction";
									break;
									case HtmlFoResponse::MENU_ESPACE_TEST_CONSOMMATEUR:
										$zTitle .= " : tests consommateur";
										if(isset($tTitle[3])){
											switch ($tTitle[3]){
												case HtmlFoResponse::MENU_ESPACE_TEST_CONSOMMATEUR_FICHE:
													$iTestId = $gJCoord->request->params['iTestId'];
													jClasses::inc('espace~reghalal_testSrv');
													$oTest = reghalal_testSrv::chargeParId($iTestId);
													$zTitle .= " : tests consommateur, ".$oTest->test_prenom;
												break;
											}
										}
									break;
									case HtmlFoResponse::MENU_ESPACE_MES_INFOS:
										$zTitle .= " : Mes infos";
									break;
								}
							}else{
								$zTitle .= " : Offres spéciales";
							}
						break;
						//produit reghalal 
						case HtmlFoResponse::MENU_PRODUIT_HALAL:
							if (isset($tTitle[2])){
								switch ($tTitle[2]) {
									case HtmlFoResponse::MENU_PRODUIT_HALAL_VOLLAILE:
										$zTitle .= " : Volailles Halal";
									break;
									case HtmlFoResponse::MENU_PRODUIT_HALAL_CHARCUTERIE:
										$zTitle .= " : Charcuterie Halal";
									break;
									case HtmlFoResponse::MENU_PRODUIT_HALAL_TRAITEUR:
										$zTitle .= " : Traiteur Halal";
									break;
								}
							}else{
								$zTitle .= " : Nos produits Halal";
							}
						break;
						//recette
						case HtmlFoResponse::MENU_RECETTE:
							if (isset($tTitle[2])){
								switch ($tTitle[2]) {
									case HtmlFoResponse::MENU_RECETTE_FICHE:
										$iRecetteId = $gJCoord->request->params['iRecetteId'];
										$iRecetteId = $gJCoord->request->params['iRecetteId'];
										jClasses::inc('recette~recetteSrv');
										$oRecette = recetteSrv::chargeParId($iRecetteId);
										$zTitle .= " : Les recettes Halal, {$oRecette->recette_libelle}";
									break;
								}
							}else{
								$zTitle .= " : Les recettes Halal";
							}
						break;

					}
				}
			break;  
			default:
				$zTitle = "Réghalal";
		}
		
		return $zTitle;
	}

	/**
	* fontion qui rempli les fichiers log
	* @param	string		$file
	* @param	string		$string
	*/
	static function remplireLog($file, $string){
        $handle = fopen($file, 'a');
        fwrite($handle, $string);
        fclose($handle);
    }

	 /**
	 * Récupérer la liste des mots vide pour les recherches
	 * @return array $tMotVide
	 */
	static function prendreMotVide(){
		$fp = fopen (JELIX_APP_WWW_PATH . "listeMotVide.txt", "r");
		$tMotVide = array();
		while (!feof($fp)) {
		    $buffer = fgets($fp, 1050000);
		    array_push($tMotVide,trim(strtoupper($buffer)));
		}
		fclose($fp);
		return $tMotVide;
	}

	 /**
	 * filtre les mots qui ne sont pas des mots dans le tableau des articles
	 *
	 * @param array $_tMot
	 * @param array $_tMotVide
	 * @return array $tMotFiltrer 
	 */
	static function filtrerMotNonVide($_tMot,$_tMotVide){
		$tMotFiltrer = array();
		for($i=0;$i<sizeof($_tMot);$i++){
			if(in_array(strtoupper ($_tMot[$i]),$_tMotVide) == false){
				array_push($tMotFiltrer,$_tMot[$i]);
			}
		}
		return $tMotFiltrer;
	}

}
?>