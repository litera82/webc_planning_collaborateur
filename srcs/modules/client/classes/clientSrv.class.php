<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage client
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
@ini_set ("memory_limit", -1) ;

class clientSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~client') ;
		return $oFac->get($_iId) ;
	}
	
	/**
	 * Creation d'un tableau d'objet selon critère
	 * @param array $_toParams tableau des parametres
	 * @param string $_zSortedField champ de trie (colone d'une table mysql)
	 * @param string $_zSortedDirection direction du trie
	 * @param int $_iStart premier enregistrement
	 * @param int $_iOffset nombre d'enregistrement affiché
	 *  @return array
	 */
	static function listCriteria($_toParams, $_zSortedField = 'client_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

		if (isset($_toParams[0]->fo) && $_toParams[0]->fo == 1){
			$oUser = jAuth::getUserSession();
			$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		}

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * " ;
		$zSql .= " FROM clients ";
		$zSql .= " LEFT JOIN pays ";
		$zSql .= " ON (client_iPays = pays_id) ";
		$zSql .= " LEFT JOIN societe ON (client_iSociete = societe_id) ";
		$zSql .= " LEFT JOIN utilisateurs ON (client_iUtilisateurCreateurId = utilisateur_id) ";
		$zSql .= " WHERE 1 ";
		if (isset($_toParams[0]->id) && $_toParams[0]->id != ""){
			$zSql .= " AND client_id = ".$_toParams[0]->id;	
		}
		if (isset($_toParams[0]->nom) && $_toParams[0]->nom != ""){
			$zSql .= " AND client_zNom LIKE '%".addslashes($_toParams[0]->nom)."%'";	
		}
		if (isset($_toParams[0]->prenom) && $_toParams[0]->prenom != ""){
			$zSql .= " AND client_zPrenom LIKE '%".addslashes($_toParams[0]->prenom)."%'";	
		}
		if (isset($_toParams[0]->client_iUtilisateurCreateurId) && $_toParams[0]->client_iUtilisateurCreateurId != 0){
			$zSql .= " AND client_iUtilisateurCreateurId = " . $_toParams[0]->client_iUtilisateurCreateurId;	
		}elseif (isset($_toParams[0]->client_iUtilisateurCreateurId) && $_toParams[0]->client_iUtilisateurCreateurId == 0 && isset($_toParams[0]->fo) && $_toParams[0]->fo == 1){
			$oUtilisateur = utilisateursSrv::getById ($iUtilisateurId) ;
			$zProf = $iUtilisateurId ;
			if (isset($oUtilisateur->utilisateur_bSuperviseur) && $oUtilisateur->utilisateur_bSuperviseur == UTILISATEUR_SUPERVISEUR){
				/*$toTmpProfesseur = utilisateursSrv::getUtilisateurBySuperviseurId($iUtilisateurId) ;
				foreach($toTmpProfesseur as $oProfesseur){
					$zProf .= ",".$oProfesseur->utilisateur_id ;					
				}*/
				$toParamsUtilisateur['utilisateur_statut'] = 1;
				$toParamsUtilisateur['notinutilisateur'] = $iUtilisateurId;
				$toTmpProfesseur = utilisateursSrv::listCriteria($toParamsUtilisateur, 'utilisateur_zPrenom');
				foreach($toTmpProfesseur['toListes'] as $oProfesseur){
					$zProf .= ",".$oProfesseur->utilisateur_id ;
				}
			}
			if ($zProf != ""){
				$zSql .= " AND client_iUtilisateurCreateurId IN (" . $zProf . ")";	
			}
		}
		if (isset($_toParams[0]->statut) && $_toParams[0]->statut != 3){
			$zSql .= " AND client_iStatut = " . $_toParams[0]->statut;	
		}

		if (isset($_toParams[0]->client_testDebut) && $_toParams[0]->client_testDebut != 2){
			$zSql .= " AND client_testDebut = " . $_toParams[0]->client_testDebut;	
		}

		if (isset($_toParams[0]->societe) && $_toParams[0]->societe != ""){
			$_zSociete = trim($_toParams[0]->societe); 
			$tzSociete = explode (" ", $_zSociete);
			if (sizeof($tzSociete) == 1){
				$zSql .= " AND societe_zNom LIKE '%" . $tzSociete[0] ."%' "; 
			}else{
				if (sizeof($tzSociete) > 0){
					$zSql .= " AND " ;
					for($i=0; $i<sizeof($tzSociete); $i++){
						if ($i == 0){
							$zSql .= "(societe_zNom LIKE '%" . $tzSociete[$i] ."%' "; 
						}elseif ($i == sizeof($tzSociete)-1){
							$zSql .= " OR societe_zNom LIKE '%" . $tzSociete[$i] ."%')"; 
						}else{
							$zSql .= " OR societe_zNom LIKE '%" . $tzSociete[$i] ."%' "; 
						}
					}
				}
			}
		}
		$zSql .= " GROUP BY client_id ";
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;

		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}
	static function getByNomPrenomEmail($zNom = "", $zPrenom = "", $zEMail = "") {
		jClasses::inc('commun~toolDate');

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * " ;
		$zSql .= " FROM clients ";
		$zSql .= " WHERE 1 ";
		if (isset($zNom) && $zNom != ""){
			$zSql .= " AND client_zNom = '".addslashes(trim($zNom))."'";	
		}
		if (isset($zPrenom) && $zPrenom != ""){
			$zSql .= " AND client_zPrenom = '".addslashes(trim($zPrenom))."'";	
		}
		if (isset($zEMail) && $zEMail != ""){
			$zSql .= " AND client_zMail = '".addslashes(trim($zEMail))."'";	
		}

		$zSql .= " GROUP BY client_id ";
		$zSql .= " ORDER BY client_id ASC ";  
		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}

	static function getListClientByNameFirstnameEmail($_zName, $_zFirstname, $_zEmail) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT * " ;
		$zSql .= " FROM clients ";
		$zSql .= " WHERE 1 ";
		if (isset($_zName) && $_zName != ""){
			$zSql .= " AND client_zNom = '".$_zName."'";	
		}
		if (isset($_zFirstname) && $_zFirstname != ""){
			$zSql .= " AND client_zPrenom = '".$_zFirstname."'";	
		}
		if (isset($_zEmail) && $_zEmail != ""){
			$zSql .= " AND client_zPrenom = '".$_zEmail."'";	
		}

		$zSql .= " GROUP BY client_id ";
		$zSql .= " ORDER BY client_id " ;  
		
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchFirst($zSql) ;

		return $toResults ;
	}
	/**
	 * Sauvegarde et modification
	 * @param array $_toParams les parametre à modifier ou à insserer
	 * @return object
	 */
	static function save($toInfos) 
	{		
		jClasses::inc('commun~tools');

		$oDaoFact = jDao::get('commun~client') ;
		$oRecord = null;
		$iId = isset($toInfos['client_id']) ? $toInfos['client_id'] : 0 ;
		if($iId <= 0) // nouveau
		{
			$oRecord = jDao::createRecord('commun~client') ;
		}
		else // update
		{
			$oRecord = $oDaoFact->get($iId) ;
		}

		$oRecord->client_iSociete		= isset($toInfos['client_iSociete']) ? $toInfos['client_iSociete'] : $oRecord->client_iSociete ;
		$oRecord->client_iSociete        = isset($toInfos['client_iSociete']) ? $toInfos['client_iSociete'] : $oRecord->client_iSociete ;
		$oRecord->client_iCivilite     = isset($toInfos['client_iCivilite']) ? $toInfos['client_iCivilite'] : $oRecord->client_iCivilite ;
		$oRecord->client_iUtilisateurCreateurId     = isset($toInfos['client_iUtilisateurCreateurId']) ? $toInfos['client_iUtilisateurCreateurId'] : $oRecord->client_iUtilisateurCreateurId ;
		$oRecord->client_zNom       = isset($toInfos['client_zNom']) ? $toInfos['client_zNom'] : $oRecord->client_zNom ;
		$oRecord->client_zPrenom      = isset($toInfos['client_zPrenom']) ? $toInfos['client_zPrenom'] : $oRecord->client_zPrenom ;
		$oRecord->client_zFonction        = isset($toInfos['client_zFonction']) ? $toInfos['client_zFonction'] : $oRecord->client_zFonction ;
		$oRecord->client_zMail     = isset($toInfos['client_zMail']) ? $toInfos['client_zMail'] : $oRecord->client_zMail ;
		$oRecord->client_zLogin     = isset($toInfos['client_zLogin']) ? $toInfos['client_zLogin'] : $oRecord->client_zLogin ;
		$oRecord->client_zPass     = isset($toInfos['client_zPass']) ? $toInfos['client_zPass'] : $oRecord->client_zPass ;
		$oRecord->client_zTel     = isset($toInfos['client_zTel']) ? $toInfos['client_zTel'] : $oRecord->client_zTel ;
		$oRecord->client_zPortable     = isset($toInfos['client_zPortable']) ? $toInfos['client_zPortable'] : $oRecord->client_zPortable ;
		$oRecord->client_zRue     = isset($toInfos['client_zRue']) ? $toInfos['client_zRue'] : $oRecord->client_zRue ;
		$oRecord->client_zVille     = isset($toInfos['client_zVille']) ? $toInfos['client_zVille'] : $oRecord->client_zVille ;
		$oRecord->client_zCP     = isset($toInfos['client_zCP']) ? $toInfos['client_zCP'] : $oRecord->client_zCP ;
		$oRecord->client_iPays     = isset($toInfos['client_iPays']) ? $toInfos['client_iPays'] : $oRecord->client_iPays ;
		$oRecord->client_iNumIndividu     = isset($toInfos['client_iNumIndividu']) ? $toInfos['client_iNumIndividu'] : $oRecord->client_iNumIndividu ;
		$oRecord->client_iRefIndividu     = isset($toInfos['client_iRefIndividu']) ? $toInfos['client_iRefIndividu'] : $oRecord->client_iRefIndividu ;
		$oRecord->client_zCryptedKey     = isset($toInfos['client_zCryptedKey']) ? $toInfos['client_zCryptedKey'] : $oRecord->client_zCryptedKey ;
		$oRecord->client_iStatut     = isset($toInfos['client_iStatut']) ? $toInfos['client_iStatut'] : $oRecord->client_iStatut ;
		$oRecord->client_testDebut     = isset($toInfos['client_testDebut']) ? $toInfos['client_testDebut'] : $oRecord->client_testDebut ;
		$oRecord->client_url     = isset($toInfos['client_url']) ? $toInfos['client_url'] : $oRecord->client_url ;
		
		if (is_null($oRecord->client_zPrenom) || $oRecord->client_zPrenom == ""){
			$oRecord->client_zPrenom = $oRecord->client_zNom ;
		}

		if($iId <= 0)
		{
			$oRecord->client_dateCreation = date('Y-m-d H:i:s');

			$oProfil = jDb::getProfil(); 
			$bTestProfil = tools::testProfil($oProfil); 
			if (!$bTestProfil){tools::createConnector($oProfil);} 
			$oCnx = jDb::getConnection(); 

			$oDaoFact->insert($oRecord) ;
		} 
		if($iId > 0)
		{
			if (isset($oRecord->client_dateCreation) && is_null($oRecord->client_dateCreation)){
				$oRecord->client_dateCreation = date('Y-m-d H:i:s');
			}
			$oRecord->client_dateMaj = date('Y-m-d H:i:s');
			$oProfil = jDb::getProfil(); 
			$bTestProfil = tools::testProfil($oProfil); 
			if (!$bTestProfil){tools::createConnector($oProfil);} 
			$oCnx = jDb::getConnection(); 

			$oDaoFact->update($oRecord);
		}

		if (isset($toInfos['sendMail']) && $toInfos['sendMail'] == 1){
			self::sendMailClient($oRecord);
		}
		return $oRecord ;
	}

	
	static function sendMailClient ($oClient){
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$oUtilisateur = utilisateursSrv::getById($oClient->client_iUtilisateurCreateurId); 
		$zUrlToIndex = jUrl::get('jelix_calendar~FoCalendar:index');

		$tplMail = new jTpl();
		$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
		$tplMail->assign ('oClient', $oClient) ;
		$tplMail->assign ('oUtilisateur', $oUtilisateur) ;
		$tplMail->assign ('zUrlToIndex', $zUrlToIndex) ;
		$tplMail->assign ('zUrlToIndexStagiaire', URL_TO_SITE . 'auto.php') ;

		$tpl = $tplMail->fetch ('client~corpsMailConfirmationCreationStagiaire') ;

		mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , MAIL_OBJECT_CONFIRMATION_CREATION_COMPTE, $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;
	}	

	/**
	 * Suppression d'un enregistrement
	 * @param int $_iId identifiant de l'objet
	 * @return boolean
	 */
	static function delete($_iId) 
	{
		$oDaoFact = jDao::get('commun~client') ;
        $oDaoFact->delete($_iId) ;
	}
	
	static function rechercherStagiaire ($_zStagiaire){
		jClasses::inc('commun~toolDate');
        $oCnx = jDb::getConnection () ;
        $toResults = array();
		$_zStagiaire = trim($_zStagiaire); 
		$tzStagiaire = explode (" ", $_zStagiaire);
		$zSql = " SELECT * FROM clients, societe " ;
		$zSql .= " WHERE client_iSociete = societe_id " ;
		$zSql .= " AND "; 
		$zCritere = "";
		if (sizeof($tzStagiaire) == 1){
			$zCritere .= "(client_zNom LIKE '%" . $tzStagiaire[0] ."%' OR client_zPrenom LIKE '%" . $tzStagiaire[0] ."%')"; 
		}else{
			for($i=0; $i<sizeof($tzStagiaire); $i++){
				if ($i == 0){
					$zCritere .= "(client_zNom LIKE '%" . $tzStagiaire[$i] ."%' OR client_zPrenom LIKE '%" . $tzStagiaire[$i] ."%'"; 
				}elseif ($i == sizeof($tzStagiaire)-1){
					$zCritere .= " OR client_zNom LIKE '%" . $tzStagiaire[$i] ."%' OR client_zPrenom LIKE '%" . $tzStagiaire[$i] ."%')"; 
				}else{
					$zCritere .= " OR client_zNom LIKE '%" . $tzStagiaire[$i] ."%' OR client_zPrenom LIKE '%" . $tzStagiaire[$i] ."%'"; 
				}
			}
		}
		if ($zCritere != ""){
		$zSql .= $zCritere;
		}
		$zSql .= " ORDER BY client_zNom ASC ";  

	    $Rs = $oCnx->query($zSql);

        while($oRecord = $Rs->fetch())
		{
			array_push($toResults,$oRecord);
		}
		return $toResults;
	}	
        
        
	/**
	 * Creation de l'objet en fonction de son zCryptedKey
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getByCryptedKey($_zCryptedKey) 
	{
		$oFac = jDao::create('commun~client') ;
                $oCond = jDao::createConditions() ;
                $oCond->addCondition('client_zCryptedKey', '=', $_zCryptedKey) ;
		return $oFac->findBy($oCond)->fetch() ;
	}
        
        
	/**
	 * Creation de l'objet en fonction de son numero individu
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getClientByNumIndividu($_iNumIndividu) 
	{
		jClasses::inc('commun~tools');

		$oFac = jDao::create('commun~client') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('client_iNumIndividu', '=', $_iNumIndividu) ;

		$oProfil = jDb::getProfil(); 
		$bTestProfil = tools::testProfil($oProfil); 
		if (!$bTestProfil){tools::createConnector($oProfil);} 
		$oCnx = jDb::getConnection(); 

		return $oFac->findBy($oCond)->fetch() ;
	}

	/**
	 * Creation de l'objet en fonction de son login et motde passe
	 * @param int $_zLogin 
	 * @param int $_zPwd
	 * @return object
	 */
	static function getClientByLoginPassword($_zLogin, $_zPwd) 
	{
		$zSql = "SELECT * FROM clients WHERE client_zLogin = '".trim(addslashes($_zLogin))."' AND  client_zPass = '".trim(addslashes($_zPwd))."'";
		$oDBW	  = jDb::getDbWidget() ;
		$oClient = $oDBW->fetchFirst($zSql) ;
		return $oClient; 
	}
}