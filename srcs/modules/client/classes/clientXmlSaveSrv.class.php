<?php
/**
* @package		webcalendar
* @subpackage	client
* @version		1
* @author		IMCOS
*/

/**
* Fonctions utilitaires pour la gestion des flux clients
*
* @package		webcalendr
* @subpackage	client
* @autor	tahiry
*/
@ini_set ("memory_limit", -1) ;
@ini_set ("max_execution_time", 100000); 
class clientXmlSaveSrv {
	static function saveClient($zUrl){
		jClasses::inc('client~clientXmlSrv');
		$oRes		= new clientXmlSrv();
		$toXmlClient = $oRes->getClientXml ($zUrl) ;
		self::traitementData ($toXmlClient->toPortefeuilleProf) ;
		return 1003 ;
	}

	static function saveClientDepuisBddLogevent(){
		$zSql = "SELECT * FROM portefeuilleprof GROUP BY portefeuilleprof_id ORDER BY portefeuilleprof_id" ;	
		$oCnx = jDb::getConnection('logevent');
		$oRs  = $oCnx->query ($zSql);
		$toRecord = $oRs->fetchAll ();
		$toXmlClient = array () ;
		foreach($toRecord as $oRecord){
			$oPortefeuilleProf = new StdClass () ;
			$oPortefeuilleProf->client_iRefIndividu				= trim($oRecord->portefeuilleprof_refindividu) ;
			$oPortefeuilleProf->client_iNumIndividu				= trim($oRecord->portefeuilleprof_numerodossierstagiaire) ;
			$oPortefeuilleProf->client_zLogin					= trim($oRecord->portefeuilleprof_email) ;
			$oPortefeuilleProf->client_zPass					= trim($oRecord->portefeuilleprof_numerodossierstagiaire) ;
			$oPortefeuilleProf->client_zCivilite				= trim($oRecord->portefeuilleprof_civilite) ;
			$oPortefeuilleProf->client_zNom						= trim($oRecord->portefeuilleprof_nomfamille) ;
			$oPortefeuilleProf->client_zPrenom					= trim($oRecord->portefeuilleprof_prenom) ;
			$oPortefeuilleProf->client_zFonction				= trim($oRecord->portefeuilleprof_fonction) ;
			$oPortefeuilleProf->client_zTel						= trim($oRecord->portefeuilleprof_tel) ;
			$oPortefeuilleProf->client_zAdresse1				= trim($oRecord->portefeuilleprof_adresse1) ;
			$oPortefeuilleProf->client_zAdresse2				= trim($oRecord->portefeuilleprof_adresse2) ;
			$oPortefeuilleProf->client_zPortable				= trim($oRecord->portefeuilleprof_mobile) ;
			$oPortefeuilleProf->client_zCP						= trim($oRecord->portefeuilleprof_codepostal) ;
			$oPortefeuilleProf->client_zVille					= trim($oRecord->portefeuilleprof_ville) ;
			$oPortefeuilleProf->client_zSociete					= trim($oRecord->portefeuilleprof_societe) ;
			$oPortefeuilleProf->client_zUtilisateurCreateurId1	= trim($oRecord->portefeuilleprof_prof1) ;
			$oPortefeuilleProf->client_zUtilisateurCreateurId2	= trim($oRecord->portefeuilleprof_prof2) ;
			$oPortefeuilleProf->client_zMail					= trim($oRecord->portefeuilleprof_email) ;	

			array_push ($toXmlClient, $oPortefeuilleProf) ;
		}
		if (sizeof($toXmlClient) > 0){
			try{
				jLog::dump("/****************************************** DEBUT TRAITEMENT A ******************************************/");
				jLog::dump(date('Y-m-d H:i:s'));
				self::traitementData ($toXmlClient) ;
				self::viderTablePortefeuilleProf() ;
				self::updateClientCreateur() ;
				jLog::dump("/****************************************** FIN TRAITEMENT A ******************************************/");
				jLog::dump(date('Y-m-d H:i:s'));
				return 1003 ;
			}catch(Exception $e){
				jLog::dump("/****************************************** PORTEFEUILLEPROF ERREUR ******************************************/");
				jLog::dump(date('Y-m-d H:i:s'));
				jLog::dump($e->getMessage()) ;
				die($e->getMessage()) ;
				return 1001 ;
			}
		}else{
			jLog::dump("/****************************************** PORTEFEUILLEPROF AUCUNE DONNEE ******************************************/");
			jLog::dump(date('Y-m-d H:i:s'));
			return 1003 ;
		}
	}
	static function viderTablePortefeuilleProf(){
		$zSql =  "TRUNCATE TABLE portefeuilleprof";
 		$oProfil = jDb::getProfil('logevent'); 
		$bTestProfil = tools::testProfil($oProfil); 
		if (!$bTestProfil){tools::createConnector($oProfil);} 
		$oCnx = jDb::getConnection(); 
		$oCnx = jDb::getConnection('logevent');
		$oCnx->exec($zSql);
		return true;
	}
	static function updateClientCreateur (){
		jClasses::inc('commun~tools');
		$zSql =  "UPDATE clients SET clients.client_iUtilisateurCreateurId = ".AUDIT_ID_CATRIONA." WHERE client_iUtilisateurCreateurId IS NULL";
		$oProfil = jDb::getProfil(); 
		$bTestProfil = tools::testProfil($oProfil); 
		if (!$bTestProfil){tools::createConnector($oProfil);} 
		$oCnx = jDb::getConnection(); 
		$oCnx->exec($zSql);
		return true ;	
	}
	static function getUtilisateurByNameProf($prof1, $prof2){
		$iProf = null ;
		$tzProf1 = explode (" ",trim(str_replace("-", " ", $prof1))) ;

 		$zSql  = "SELECT utilisateur_id FROM utilisateurs WHERE 1 = 1 " ; // utilisateur_zNom = '".trim($prof1)."'";
		if (sizeof($tzProf1) == 1){
			$zSql  .= " AND utilisateur_zNom = '".trim($prof1)."'" ; 
		}else{
			for ($i=0; $i<sizeof($tzProf1); $i++){
				if ($i == 0){
					$zSql .= " AND (utilisateur_zNom LIKE '%".addslashes($tzProf1[$i])."%'" ;
				}elseif ($i == sizeof($tzProf1)-1){
					$zSql .= " OR utilisateur_zNom LIKE '%".addslashes($tzProf1[$i])."%') " ;
				}else{
					$zSql .= " OR utilisateur_zNom LIKE '%".addslashes($tzProf1[$i])."%' " ;
				}
			}
		}			
		$zSql  .= " GROUP BY utilisateur_id ORDER BY utilisateur_id ";
		$oProfil = jDb::getProfil(); 
		$bTestProfil = tools::testProfil($oProfil); 
		if (!$bTestProfil){tools::createConnector($oProfil);} 

		$oDBW		= jDb::getDbWidget() ;
		$oProf		= $oDBW->fetchFirst($zSql); 
	
		if (isset($oProf->utilisateur_id) && $oProf->utilisateur_id > 0){
			$iProf = $oProf->utilisateur_id; 
		}else{
			$tzProf2 = explode (" ",trim(str_replace("-", " ", $prof2))) ;
			$zSql1 = "SELECT utilisateur_id FROM utilisateurs WHERE 1=1 "; // utilisateur_zNom = '".trim($prof2)."'";
			if (sizeof($tzProf2) == 1){
				$zSql1  .= " AND utilisateur_zNom = '".trim($prof2)."'" ; 
			}else{
				for ($i=0; $i<sizeof($tzProf2); $i++){
					if ($i == 0){
						$zSql1 .= " AND (utilisateur_zNom LIKE '%".addslashes($tzProf2[$i])."%'" ;
					}elseif ($i == sizeof($tzProf2)-1){
						$zSql1 .= " OR utilisateur_zNom LIKE '%".addslashes($tzProf2[$i])."%') " ;
					}else{
						$zSql1 .= " OR utilisateur_zNom LIKE '%".addslashes($tzProf2[$i])."%' " ;
					}
				}
			}			
			$zSql1  .= " GROUP BY utilisateur_id ORDER BY utilisateur_id ";
			$oProfil = jDb::getProfil(); 
			$bTestProfil = tools::testProfil($oProfil); 
			if (!$bTestProfil){tools::createConnector($oProfil);} 
			$oCnx = jDb::getConnection(); 

			$oDBW1		= jDb::getDbWidget() ;
			$oProf1		= $oDBW1->fetchFirst($zSql1); 
			if (isset($oProf1->utilisateur_id) && $oProf1->utilisateur_id > 0){
				$iProf = $oProf1->utilisateur_id;
			}
		}
		if (IS_NULL($iProf)){
			$iProf = AUDIT_ID_CATRIONA ;
		}
		return $iProf ;
	}
	static function traitementData ($toXmlClient){
		jClasses::inc('client~clientXmlSrv');
		jClasses::inc('client~clientSrv');
		jClasses::inc('client~societeSrv');
		$iCpt = 0 ;
		foreach($toXmlClient as $oXmlClient){
			if (isset ($oXmlClient->client_iNumIndividu) && intval($oXmlClient->client_iNumIndividu) > 0){ // le client a un client_iNumIndividu
				$oClient = clientSrv::getClientByNumIndividu(intval($oXmlClient->client_iNumIndividu)); 
				//jLog::dump($oClient) ;
				if (isset($oClient->client_id) && $oClient->client_id > 0){ // le client existe, MAJ donnée client
					jLog::dump("le client existe, MAJ donnée client"); 
					jLog::dump($oClient->client_id) ;
					$toInfos = array ();
					$toInfos['client_id'] = $oClient->client_id ;
					$toInfos['client_iNumIndividu'] = $oClient->client_iNumIndividu ;
					if (isset($oXmlClient->client_zSociete) && trim($oXmlClient->client_zSociete) != ""){
						$_toParams = array ();
						$_toParams[0] = new StdClass();
						$_toParams[0]->societe_zNom = trim($oXmlClient->client_zSociete) ;
						$toResults = societeSrv::listCriteria($_toParams) ;
						if ($toResults['iResTotal'] > 0){ // societe existant
							$toInfos['client_iSociete'] = $toResults['toListes'][0]->societe_id ;
						}else{ // On creer la société
							$toInfosSociete = array(); 
							$toInfosSociete['societe_zNom'] = trim($oXmlClient->client_zSociete) ;
							$toInfosSociete['societe_iStatut'] = STATUT_PUBLIE ;
							$oNewSoc = societeSrv::save($toInfosSociete) ;
							$toInfos['client_iSociete'] = $oNewSoc->societe_id ;
						}
					}else{
						$toInfos['client_iSociete'] = $oClient->client_iSociete ;
					}
					jLog::dump("le client existe, MAJ donnée client > societe > ");
					jLog::dump($toInfos['client_iSociete']) ;
					if (isset ($oXmlClient->client_zCivilite) && trim($oXmlClient->client_zCivilite) != ""){
						if (strtoupper(trim($oXmlClient->client_zCivilite)) == "MME"){
							$toInfos['client_iCivilite'] = CIVILITE_FEMME ;
						}elseif (strtoupper(trim($oXmlClient->client_zCivilite)) == "MR"){
							$toInfos['client_iCivilite'] = CIVILITE_HOMME ;
						}else{
							$toInfos['client_iCivilite'] = CIVILITE_MADEMOISELLE ;
						}
					}else{ 
						if (isset($oClient->client_iCivilite) && $oClient->client_iCivilite > 0){
							$toInfos['client_iCivilite'] = $oClient->client_iCivilite ;
						}else{
							$toInfos['client_iCivilite'] = CIVILITE_HOMME ;
						}
					}
					$toInfos['client_iUtilisateurCreateurId'] = self::getUtilisateurByNameProf ($oXmlClient->client_zUtilisateurCreateurId1, $oXmlClient->client_zUtilisateurCreateurId2) ;
					if (isset ($oXmlClient->client_zNom) && trim($oXmlClient->client_zNom) != ""){
						$toInfos['client_zNom'] = $oXmlClient->client_zNom ;
					}else{
						$toInfos['client_zNom'] = $oClient->client_zNom ;
					}	
					if (isset ($oXmlClient->client_zPrenom) && trim($oXmlClient->client_zPrenom) != ""){
						$toInfos['client_zPrenom'] = $oXmlClient->client_zPrenom ;
					}else{
						$toInfos['client_zPrenom'] = $oClient->client_zPrenom ;
					}	
					if (isset ($oXmlClient->client_zFonction) && trim($oXmlClient->client_zFonction) != ""){
						$toInfos['client_zFonction'] = $oXmlClient->client_zFonction ;
					}else{
						$toInfos['client_zFonction'] = $oClient->client_zFonction ;
					}	
					if (isset ($oXmlClient->client_zMail) && trim($oXmlClient->client_zMail) != ""){
						$toInfos['client_zMail'] = $oXmlClient->client_zMail ;
						$toInfos['client_zLogin'] = $oXmlClient->client_zMail ;
					}else{
						$toInfos['client_zMail'] = $oClient->client_zMail ;
						$toInfos['client_zLogin'] = $oClient->client_zMail ;
					}	
					if (isset ($oXmlClient->client_zPass) && intval($oXmlClient->client_zPass) > 0){
						$toInfos['client_zPass'] = $oXmlClient->client_zPass ;
					}else{
						$toInfos['client_zPass'] = $oClient->client_zPass ;
					}	
					if (isset ($oXmlClient->client_zTel) && trim($oXmlClient->client_zTel) != ""){
						$toInfos['client_zTel'] = $oXmlClient->client_zTel ;
					}else{
						$toInfos['client_zTel'] = $oClient->client_zTel ;
					}	
					if (isset ($oXmlClient->client_zPortable) && trim($oXmlClient->client_zPortable) != ""){
						$toInfos['client_zPortable'] = $oXmlClient->client_zPortable ;
					}else{
						$toInfos['client_zPortable'] = $oClient->client_zPortable ;
					}	
					if (isset ($oXmlClient->client_zAdresse1) || isset($oXmlClient->client_zAdresse2) != ""){
						$toInfos['client_zRue'] = $oXmlClient->client_zAdresse1 . " " . $oXmlClient->client_zAdresse2 ;
					}else{
						$toInfos['client_zRue'] = $oClient->client_zRue ;
					}
					if (isset ($oXmlClient->client_zVille) && trim($oXmlClient->client_zVille) != ""){
						$toInfos['client_zVille'] = $oXmlClient->client_zVille ;
					}else{
						$toInfos['client_zVille'] = $oClient->client_zVille ;
					}
					if (isset ($oXmlClient->client_zCP) && trim($oXmlClient->client_zCP) != ""){
						$toInfos['client_zCP'] = $oXmlClient->client_zCP ;
					}else{
						$toInfos['client_zCP'] = $oClient->client_zCP ;
					}
					if (isset ($oXmlClient->client_iRefIndividu) && intval($oXmlClient->client_iRefIndividu) > 0){
						$toInfos['client_iRefIndividu'] = $oXmlClient->client_iRefIndividu ;
					}else{
						$toInfos['client_iRefIndividu'] =  $oClient->client_iRefIndividu ;
					}
					$toInfos['client_iStatut'] = STATUT_PUBLIE ;
					clientSrv::save($toInfos); 

				}else{ // New client à inserer // FIN IF (ISSET($OCLIENT->CLIENT_ID) && $OCLIENT->CLIENT_ID > 0){
					jLog::dump("New client à inserer >   ");
					jLog::dump($oXmlClient->client_iNumIndividu) ;
					$toInfos = array ();
					$toInfos['client_iNumIndividu'] = $oXmlClient->client_iNumIndividu ;
					if (isset($oXmlClient->client_zSociete) && trim($oXmlClient->client_zSociete) != ""){
						$_toParams = array ();
						$_toParams[0] = new StdClass();
						$_toParams[0]->societe_zNom = trim($oXmlClient->client_zSociete) ;
						$toResults = societeSrv::listCriteria($_toParams) ;
						if ($toResults['iResTotal'] > 0){ // societe existant
							$toInfos['client_iSociete'] = $toResults['toListes'][0]->societe_id ;
						}else{ // On creer la société
							$toInfosSociete = array(); 
							$toInfosSociete['societe_zNom'] = trim($oXmlClient->client_zSociete) ;
							$toInfosSociete['societe_iStatut'] = STATUT_PUBLIE ;
							$oNewSoc = societeSrv::save($toInfosSociete) ;
							$toInfos['client_iSociete'] = $oNewSoc->societe_id ;
						}
					}else{
						$toInfos['client_iSociete'] = NULL ;
					}
					jLog::dump("New client à inserer > societe > ");
					jLog::dump($toInfos['client_iSociete']) ;

					if (isset ($oXmlClient->client_zCivilite) && trim($oXmlClient->client_zCivilite) != ""){
						if (strtoupper(trim($oXmlClient->client_zCivilite)) == "MME"){
							$toInfos['client_iCivilite'] = CIVILITE_FEMME ;
						}elseif (strtoupper(trim($oXmlClient->client_zCivilite)) == "MR"){
							$toInfos['client_iCivilite'] = CIVILITE_HOMME ;
						}else{
							$toInfos['client_iCivilite'] = CIVILITE_MADEMOISELLE ;
						}
					}else{ 
						$toInfos['client_iCivilite'] = CIVILITE_HOMME ;
					}		
					
					$toInfos['client_iUtilisateurCreateurId'] = self::getUtilisateurByNameProf ($oXmlClient->client_zUtilisateurCreateurId1, $oXmlClient->client_zUtilisateurCreateurId2) ;
					if (isset ($oXmlClient->client_zNom) && trim($oXmlClient->client_zNom) != ""){
						$toInfos['client_zNom'] = $oXmlClient->client_zNom ;
					}else{
						$toInfos['client_zNom'] = "" ;
					}	
					if (isset ($oXmlClient->client_zPrenom) && trim($oXmlClient->client_zPrenom) != ""){
						$toInfos['client_zPrenom'] = $oXmlClient->client_zPrenom ;
					}else{
						$toInfos['client_zPrenom'] = "" ;
					}	
					if (isset ($oXmlClient->client_zFonction) && trim($oXmlClient->client_zFonction) != ""){
						$toInfos['client_zFonction'] = $oXmlClient->client_zFonction ;
					}else{
						$toInfos['client_zFonction'] = "" ;
					}	
					if (isset ($oXmlClient->client_zMail) && trim($oXmlClient->client_zMail) != ""){
						$toInfos['client_zMail'] = $oXmlClient->client_zMail ;
						$toInfos['client_zLogin'] = $oXmlClient->client_zMail ;
					}else{
						$toInfos['client_zMail'] = "" ;
						$toInfos['client_zLogin'] = "" ;
					}	
					if (isset ($oXmlClient->client_zPass) && intval($oXmlClient->client_zPass) > 0){
						$toInfos['client_zPass'] = $oXmlClient->client_zPass ;
					}else{
						$toInfos['client_zPass'] = "" ;
					}	
					if (isset ($oXmlClient->client_zTel) && trim($oXmlClient->client_zTel) != ""){
						$toInfos['client_zTel'] = $oXmlClient->client_zTel ;
					}else{
						$toInfos['client_zTel'] = "" ;
					}	
					if (isset ($oXmlClient->client_zPortable) && trim($oXmlClient->client_zPortable) != ""){
						$toInfos['client_zPortable'] = $oXmlClient->client_zPortable ;
					}else{
						$toInfos['client_zPortable'] = "" ;
					}	
					if (isset ($oXmlClient->client_zAdresse1) || isset($oXmlClient->client_zAdresse2) != ""){
						$toInfos['client_zRue'] = $oXmlClient->client_zAdresse1 . " " . $oXmlClient->client_zAdresse2 ;
					}else{
						$toInfos['client_zRue'] = "" ;
					}
					if (isset ($oXmlClient->client_zVille) && trim($oXmlClient->client_zVille) != ""){
						$toInfos['client_zVille'] = $oXmlClient->client_zVille ;
					}else{
						$toInfos['client_zVille'] = "" ;
					}
					if (isset ($oXmlClient->client_zCP) && trim($oXmlClient->client_zCP) != ""){
						$toInfos['client_zCP'] = $oXmlClient->client_zCP ;
					}else{
						$toInfos['client_zCP'] = "" ;
					}
					if (isset ($oXmlClient->client_iRefIndividu) && intval($oXmlClient->client_iRefIndividu) > 0){
						$toInfos['client_iRefIndividu'] = $oXmlClient->client_iRefIndividu ;
					}else{
						$toInfos['client_iRefIndividu'] = null ;
					}
					$toInfos['client_iStatut'] = STATUT_PUBLIE ;
					clientSrv::save($toInfos); 

				} // FIN // NEW CLIENT À INSERER // FIN IF (ISSET($OCLIENT->CLIENT_ID) && $OCLIENT->CLIENT_ID > 0){
			}else{ // pas de client_iNumIndividu recherche par nom, prenom, email
				jLog::dump("pas de client_iNumIndividu recherche par nom, prenom, email") ;

				$toClientBase = clientSrv::getByNomPrenomEmail($oXmlClient->client_zNom, $oXmlClient->client_zPrenom, $oXmlClient->client_zMail) ;
				if (isset($toClientBase['iResTotal']) && $toClientBase['iResTotal'] > 0){
					$oClient = $toClientBase['toListes'][0];
					$toInfos = array ();
					$toInfos['client_id'] = $oClient->client_id ;
					$toInfos['client_iNumIndividu'] = $oClient->client_iNumIndividu ;
					if (isset($oXmlClient->client_zSociete) && trim($oXmlClient->client_zSociete) != ""){
						$_toParams = array ();
						$_toParams[0] = new StdClass();
						$_toParams[0]->societe_zNom = trim($oXmlClient->client_zSociete) ;
						$toResults = societeSrv::listCriteria($_toParams) ;
						if ($toResults['iResTotal'] > 0){ // societe existant
							$toInfos['client_iSociete'] = $toResults['toListes'][0]->societe_id ;
						}else{ // On creer la société
							$toInfosSociete = array(); 
							$toInfosSociete['societe_zNom'] = trim($oXmlClient->client_zSociete) ;
							$toInfosSociete['societe_iStatut'] = STATUT_PUBLIE ;
							$oNewSoc = societeSrv::save($toInfosSociete) ;
							$toInfos['client_iSociete'] = $oNewSoc->societe_id ;
						}
					}else{
						$toInfos['client_iSociete'] = $oClient->client_iSociete ;
					}
					jLog::dump("pas de client_iNumIndividu recherche par nom, prenom, email > societe > ");
					jLog::dump($toInfos['client_iSociete']) ;
					if (isset ($oXmlClient->client_zCivilite) && trim($oXmlClient->client_zCivilite) != ""){
						if (strtoupper(trim($oXmlClient->client_zCivilite)) == "MME"){
							$toInfos['client_iCivilite'] = CIVILITE_FEMME ;
						}elseif (strtoupper(trim($oXmlClient->client_zCivilite)) == "MR"){
							$toInfos['client_iCivilite'] = CIVILITE_HOMME ;
						}else{
							$toInfos['client_iCivilite'] = CIVILITE_MADEMOISELLE ;
						}
					}else{ 
						if (isset($oClient->client_iCivilite) && $oClient->client_iCivilite > 0){
							$toInfos['client_iCivilite'] = $oClient->client_iCivilite ;
						}else{
							$toInfos['client_iCivilite'] = CIVILITE_HOMME ;
						}
					}
					
					$toInfos['client_iUtilisateurCreateurId'] = self::getUtilisateurByNameProf ($oXmlClient->client_zUtilisateurCreateurId1, $oXmlClient->client_zUtilisateurCreateurId2) ;
					if (isset ($oXmlClient->client_zNom) && trim($oXmlClient->client_zNom) != ""){
						$toInfos['client_zNom'] = $oXmlClient->client_zNom ;
					}else{
						$toInfos['client_zNom'] = $oClient->client_zNom ;
					}	
					if (isset ($oXmlClient->client_zPrenom) && trim($oXmlClient->client_zPrenom) != ""){
						$toInfos['client_zPrenom'] = $oXmlClient->client_zPrenom ;
					}else{
						$toInfos['client_zPrenom'] = $oClient->client_zPrenom ;
					}	
					if (isset ($oXmlClient->client_zFonction) && trim($oXmlClient->client_zFonction) != ""){
						$toInfos['client_zFonction'] = $oXmlClient->client_zFonction ;
					}else{
						$toInfos['client_zFonction'] = $oClient->client_zFonction ;
					}	
					if (isset ($oXmlClient->client_zMail) && trim($oXmlClient->client_zMail) != ""){
						$toInfos['client_zMail'] = $oXmlClient->client_zMail ;
						$toInfos['client_zLogin'] = $oXmlClient->client_zMail ;
					}else{
						$toInfos['client_zMail'] = $oClient->client_zMail ;
						$toInfos['client_zLogin'] = $oClient->client_zMail ;
					}	
					if (isset ($oXmlClient->client_zPass) && intval($oXmlClient->client_zPass) > 0){
						$toInfos['client_zPass'] = $oXmlClient->client_zPass ;
					}else{
						$toInfos['client_zPass'] = $oClient->client_zPass ;
					}	
					if (isset ($oXmlClient->client_zTel) && trim($oXmlClient->client_zTel) != ""){
						$toInfos['client_zTel'] = $oXmlClient->client_zTel ;
					}else{
						$toInfos['client_zTel'] = $oClient->client_zTel ;
					}	
					if (isset ($oXmlClient->client_zPortable) && trim($oXmlClient->client_zPortable) != ""){
						$toInfos['client_zPortable'] = $oXmlClient->client_zPortable ;
					}else{
						$toInfos['client_zPortable'] = $oClient->client_zPortable ;
					}	
					if (isset ($oXmlClient->client_zAdresse1) || isset($oXmlClient->client_zAdresse2) != ""){
						$toInfos['client_zRue'] = $oXmlClient->client_zAdresse1 . " " . $oXmlClient->client_zAdresse2 ;
					}else{
						$toInfos['client_zRue'] = $oClient->client_zRue ;
					}
					if (isset ($oXmlClient->client_zVille) && trim($oXmlClient->client_zVille) != ""){
						$toInfos['client_zVille'] = $oXmlClient->client_zVille ;
					}else{
						$toInfos['client_zVille'] = $oClient->client_zVille ;
					}
					if (isset ($oXmlClient->client_zCP) && trim($oXmlClient->client_zCP) != ""){
						$toInfos['client_zCP'] = $oXmlClient->client_zCP ;
					}else{
						$toInfos['client_zCP'] = $oClient->client_zCP ;
					}
					if (isset ($oXmlClient->client_iRefIndividu) && intval($oXmlClient->client_iRefIndividu) > 0){
						$toInfos['client_iRefIndividu'] = $oXmlClient->client_iRefIndividu ;
					}else{
						$toInfos['client_iRefIndividu'] =  $oClient->client_iRefIndividu ;
					}
					$toInfos['client_iStatut'] = STATUT_PUBLIE ;
					clientSrv::save($toInfos); 
				}else{ // IF (ISSET($TOCLIENTBASE['IRESTOTAL']) && $TOCLIENTBASE['IRESTOTAL'] > 0){
					$toInfos = array ();
					$toInfos['client_iNumIndividu'] = $oXmlClient->client_iNumIndividu ;
					if (isset($oXmlClient->client_zSociete) && trim($oXmlClient->client_zSociete) != ""){
						$_toParams = array ();
						$_toParams[0] = new StdClass();
						$_toParams[0]->societe_zNom = trim($oXmlClient->client_zSociete) ;
						$toResults = societeSrv::listCriteria($_toParams) ;
						if ($toResults['iResTotal'] > 0){ // societe existant
							$toInfos['client_iSociete'] = $toResults['toListes'][0]->societe_id ;
						}else{ // On creer la société
							$toInfosSociete = array(); 
							$toInfosSociete['societe_zNom'] = trim($oXmlClient->client_zSociete) ;
							$toInfosSociete['societe_iStatut'] = STATUT_PUBLIE ;
							$oNewSoc = societeSrv::save($toInfosSociete) ;
							$toInfos['client_iSociete'] = $oNewSoc->societe_id ;
						}
					}else{
						$toInfos['client_iSociete'] = NULL ;
					}
					if (isset ($oXmlClient->client_zCivilite) && trim($oXmlClient->client_zCivilite) != ""){
						if (strtoupper(trim($oXmlClient->client_zCivilite)) == "MME"){
							$toInfos['client_iCivilite'] = CIVILITE_FEMME ;
						}elseif (strtoupper(trim($oXmlClient->client_zCivilite)) == "MR"){
							$toInfos['client_iCivilite'] = CIVILITE_HOMME ;
						}else{
							$toInfos['client_iCivilite'] = CIVILITE_MADEMOISELLE ;
						}
					}else{ 
						$toInfos['client_iCivilite'] = CIVILITE_HOMME ;
					}		
					
					$toInfos['client_iUtilisateurCreateurId'] = self::getUtilisateurByNameProf ($oXmlClient->client_zUtilisateurCreateurId1, $oXmlClient->client_zUtilisateurCreateurId2) ;
					if (isset ($oXmlClient->client_zNom) && trim($oXmlClient->client_zNom) != ""){
						$toInfos['client_zNom'] = $oXmlClient->client_zNom ;
					}else{
						$toInfos['client_zNom'] = "" ;
					}	
					if (isset ($oXmlClient->client_zPrenom) && trim($oXmlClient->client_zPrenom) != ""){
						$toInfos['client_zPrenom'] = $oXmlClient->client_zPrenom ;
					}else{
						$toInfos['client_zPrenom'] = "" ;
					}	
					if (isset ($oXmlClient->client_zFonction) && trim($oXmlClient->client_zFonction) != ""){
						$toInfos['client_zFonction'] = $oXmlClient->client_zFonction ;
					}else{
						$toInfos['client_zFonction'] = "" ;
					}	
					if (isset ($oXmlClient->client_zMail) && trim($oXmlClient->client_zMail) != ""){
						$toInfos['client_zMail'] = $oXmlClient->client_zMail ;
						$toInfos['client_zLogin'] = $oXmlClient->client_zMail ;
					}else{
						$toInfos['client_zMail'] = "" ;
						$toInfos['client_zLogin'] = "" ;
					}	
					if (isset ($oXmlClient->client_zPass) && intval($oXmlClient->client_zPass) > 0){
						$toInfos['client_zPass'] = $oXmlClient->client_zPass ;
					}else{
						$toInfos['client_zPass'] = "" ;
					}	
					if (isset ($oXmlClient->client_zTel) && trim($oXmlClient->client_zTel) != ""){
						$toInfos['client_zTel'] = $oXmlClient->client_zTel ;
					}else{
						$toInfos['client_zTel'] = "" ;
					}	
					if (isset ($oXmlClient->client_zPortable) && trim($oXmlClient->client_zPortable) != ""){
						$toInfos['client_zPortable'] = $oXmlClient->client_zPortable ;
					}else{
						$toInfos['client_zPortable'] = "" ;
					}	
					if (isset ($oXmlClient->client_zAdresse1) || isset($oXmlClient->client_zAdresse2) != ""){
						$toInfos['client_zRue'] = $oXmlClient->client_zAdresse1 . " " . $oXmlClient->client_zAdresse2 ;
					}else{
						$toInfos['client_zRue'] = "" ;
					}
					if (isset ($oXmlClient->client_zVille) && trim($oXmlClient->client_zVille) != ""){
						$toInfos['client_zVille'] = $oXmlClient->client_zVille ;
					}else{
						$toInfos['client_zVille'] = "" ;
					}
					if (isset ($oXmlClient->client_zCP) && trim($oXmlClient->client_zCP) != ""){
						$toInfos['client_zCP'] = $oXmlClient->client_zCP ;
					}else{
						$toInfos['client_zCP'] = "" ;
					}
					if (isset ($oXmlClient->client_iRefIndividu) && intval($oXmlClient->client_iRefIndividu) > 0){
						$toInfos['client_iRefIndividu'] = $oXmlClient->client_iRefIndividu ;
					}else{
						$toInfos['client_iRefIndividu'] = null ;
					}
					$toInfos['client_iStatut'] = STATUT_PUBLIE ;
					clientSrv::save($toInfos); 				
				}
			}
		}	
	}
}
?>