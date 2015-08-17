<?php
/**
* @package   jelix_calendar
* @subpackage administrateurs
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        return $rep;
    }
	/**
	* http://localhost/webcalendar/srcs/www/index.php?module=client&action=default:importerPortefeuilleProf
	*/
	function importerPortefeuilleProf (){
		$oRep = $this->getResponse('text');
		jClasses::inc('client~clientSrv');
 		jClasses::inc('client~societeSrv');
        jClasses::inc('utilisateurs~utilisateursSrv');

		$xmlFile = XML_PATH_PORTEFEUILLE_PROF . XML_FILE_PORTEFEUILLE_PROF; 
		if (is_file($xmlFile) && file_exists($xmlFile)){
			$xml = simplexml_load_file($xmlFile);
			for ($i=0; $i<sizeof($xml->portefeuille_prof); $i++){
				//print_r($xml->portefeuille_prof[$i]); 
				$oClient = new StdClass ();
				$oClient->client_iRefIndividu = $xml->portefeuille_prof[$i]->RéfIndividu ;
				$oClient->client_iNumIndividu = $xml->portefeuille_prof[$i]->numero_dossier_stagiaire ;
				if (strtoupper($xml->portefeuille_prof[$i]->Civilité) == "MME"){
					$oClient->client_iCivilite = CIVILITE_FEMME ;
				}elseif (strtoupper($xml->portefeuille_prof[$i]->Civilité) == "MR"){
					$oClient->client_iCivilite = CIVILITE_HOMME ;
				}else{
					$oClient->client_iCivilite = CIVILITE_MADEMOISELLE ;
				}
				$oClient->client_zNom = $xml->portefeuille_prof[$i]->Nomfamille ;
				$oClient->client_zPrenom = $xml->portefeuille_prof[$i]->Prénom ;
				$oClient->client_zMail = $xml->portefeuille_prof[$i]->email ;
				$oClient->client_zFonction = $xml->portefeuille_prof[$i]->Fonction ;
				$oClient->client_zTel = $xml->portefeuille_prof[$i]->Tél ;
				$oClient->client_zRue = $xml->portefeuille_prof[$i]->Adresse1 . " " . $xml->portefeuille_prof[$i]->Adresse2 ;
				$oClient->client_zPortable = $xml->portefeuille_prof[$i]->Mobile ;
				$oClient->client_zCP = $xml->portefeuille_prof[$i]->CodePostal ;
				$oClient->client_zVille = $xml->portefeuille_prof[$i]->Ville ;
				$oClient->societe = $xml->portefeuille_prof[$i]->portefeuilleprof_export_Société ;
				$oClient->Prof1 = $xml->portefeuille_prof[$i]->Prof1 ;
				$oClient->Prof2 = $xml->portefeuille_prof[$i]->Prof2 ;

				$_toParams = array (); 
				$_toParams[0]->societe_zNom = $oClient->societe;
				$toSociete = societeSrv::listCriteria($_toParams);

				$toProf = utilisateursSrv::getUtilisateurByNameProf(trim($oClient->Prof1), trim($oClient->Prof2)); 
				if (sizeof($toProf) > 0){ 
					foreach ($toProf as $oProf){
						if (intval($oClient->client_iNumIndividu)>0){ // client_iNumIndividu exist
							$oClientBdd = clientSrv::getClientByNumIndividu($oClient->client_iNumIndividu); 	
							if (isset($oClientBdd->client_id) && $oClientBdd->client_id > 0){ // Stagiaire existant
								$tClientBddUpdate['client_id'] = $oClient->client_id ;
							} else { // Nouvaeu Stagiaire
								$tClientBddUpdate['client_id'] = 0 ;
							}	
							if ($toSociete['iResTotal'] > 0){
								$tClientBddUpdate['client_iSociete'] = $toSociete['toListes'][0]->client_iSociete ;
							}else{
								$tClientBddUpdate['client_iSociete'] = NULL ;
							}
							$tClientBddUpdate['client_iCivilite'] = $oClient->client_iCivilite ;
							$tClientBddUpdate['client_iUtilisateurCreateurId'] = $toProf->utilisateur_id ;
							$tClientBddUpdate['client_zNom'] = $oClientBdd->client_zNom ;
							$tClientBddUpdate['client_zPrenom'] = $oClientBdd->client_zPrenom ;
							$tClientBddUpdate['client_zFonction'] = $oClientBdd->client_zFonction ;
							$tClientBddUpdate['client_zTel'] = $oClientBdd->client_zTel ;
							$tClientBddUpdate['client_zPortable'] = $oClientBdd->client_zPortable ;
							$tClientBddUpdate['client_zRue'] = $oClientBdd->client_zRue ;
							$tClientBddUpdate['client_zVille'] = $oClientBdd->client_zVille ;
							$tClientBddUpdate['client_zCP'] = $oClientBdd->client_zCP ;
							$tClientBddUpdate['client_iRefIndividu'] = $oClientBdd->client_iRefIndividu ;

							clientSrv::save($tClientBddUpdate);
						}else{ // client_iNumIndividu n'exist pas
							$toClient = clientSrv::getListClientByNameFirstnameEmail ($oClient->client_zNom, $oClient->client_zPrenom, $oClient->client_zMail); 
							if (sizeof($toClient) > 0 && $toClient[0]->client_id > 0){
								$tClientBddAdd['client_id'] = $toClient[0]->client_id ;
							}else{
								$tClientBddAdd['client_id'] = 0 ;
							}
							if ($toSociete['iResTotal'] > 0){
								$tClientBddAdd['client_iSociete'] = $toSociete['toListes'][0]->client_iSociete ;
							}else{
								$tClientBddAdd['client_iSociete'] = NULL ;
							}
							$tClientBddAdd['client_iCivilite'] = $oClient->client_iCivilite ;
							$tClientBddAdd['client_iUtilisateurCreateurId'] = $toProf->utilisateur_id ;
							$tClientBddAdd['client_zNom'] = $oClient->client_zNom ;
							$tClientBddAdd['client_zPrenom'] = $oClient->client_zPrenom ;
							$tClientBddAdd['client_zFonction'] = $oClient->client_zFonction ;
							$tClientBddAdd['client_zTel'] = $oClient->client_zTel ;
							$tClientBddAdd['client_zPortable'] = $oClient->client_zPortable ;
							$tClientBddAdd['client_zRue'] = $oClient->client_zRue ;
							$tClientBddAdd['client_zVille'] = $oClient->client_zVille ;
							$tClientBddAdd['client_zCP'] = $oClient->client_zCP ;
							$tClientBddAdd['client_iRefIndividu'] = $oClient->client_iRefIndividu ;

							clientSrv::save($tClientBddAdd);
						}
					} // fin foreach ($toProf as $oProf){
				}else{ // pas de prof vou smettez catriona, ca evitera les errurs

				}
			}
			die();
		}else{
			die('impossible de parser le fichier xml ' . $xmlFile);
		}
		return $oRep;
	}
}

