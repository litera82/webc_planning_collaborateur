<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class utilisateursSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~utilisateurs') ;
		return $oFac->get($_iId) ;
	}

	static function listCriteria($_toParams, $_zSortedField = 'utilisateur_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM utilisateurs ";
		$zSql .= " LEFT JOIN utilisateursgroup ON utilisateurs.utilisateur_id = utilisateursgroup.utilisateursgroup_utilisateurId ";
		$zSql .= " LEFT JOIN groupe ON utilisateursgroup.utilisateursgroup_groupId = groupe.groupe_id " ;
		$zSql .= " WHERE 1 = 1 " ;
		if (isset($_toParams['utilisateur_statut'])){
			$zSql .= " AND utilisateur_statut = " .  $_toParams['utilisateur_statut'];
		}

		if (isset($_toParams['utilisateur_bSuperviseur'])){
			$zSql .= " AND utilisateur_bSuperviseur = " .  $_toParams['utilisateur_bSuperviseur'];
		}
		
		if (isset($_toParams['notinutilisateur']) && $_toParams['notinutilisateur'] != 0){
			$zSql .= " AND utilisateur_id <> " .  $_toParams['notinutilisateur'];
		}

		if (isset($_toParams['utilisateur_id']) && $_toParams['utilisateur_id'] != 0){
			$zSql .= " AND utilisateur_id = " .  $_toParams['utilisateur_id'];
		}	
		if (isset($_toParams['utilisateur_bSendExcel']) && $_toParams['utilisateur_bSendExcel'] != 0){
			$zSql .= " AND utilisateur_bSendExcel = " .  $_toParams['utilisateur_bSendExcel'];
		}	
		if (isset($_toParams['groupe_id']) && $_toParams['groupe_id'] != 0){
			$zSql .= " AND groupe_id = " .  $_toParams['groupe_id'];
		}	
		if (isset($_toParams['utilisateur_frequenceSendExcel']) && $_toParams['utilisateur_frequenceSendExcel'] != 0){
			$zSql .= " AND utilisateur_frequenceSendExcel = " .  $_toParams['utilisateur_frequenceSendExcel'];
		}	
		if (isset($_toParams['utilisateur_iTypeId']) && $_toParams['utilisateur_iTypeId'] != 0){
			$zSql .= " AND utilisateur_iTypeId = " .  $_toParams['utilisateur_iTypeId'];
		}	
		if (isset($_toParams['utilisateur_bGenerateDispo']) && $_toParams['utilisateur_bGenerateDispo'] != 0){
			$zSql .= " AND utilisateur_bGenerateDispo = " .  $_toParams['utilisateur_bGenerateDispo'];
		}	

		$zSql .= " GROUP BY utilisateur_id ";
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ; 
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;
		
		return $toResults ;
	}
	/**
	 * Sauvegarde et modification
	 * @param array $toInfos les parametre à modifier ou à insserer
	 * @return object
	 */
	static function save($toInfos) 
	{		
		$oDaoFact = jDao::get('commun~utilisateurs') ;
		$oRecord = null;
		$iId = isset($toInfos['utilisateur_id']) ? $toInfos['utilisateur_id'] : 0 ;
		if($iId <= 0) // nouveau
		{
			$oRecord = jDao::createRecord('commun~utilisateurs') ;
		}
		else // update
		{
			$oRecord = $oDaoFact->get($iId) ;
		}
		$oRecord->utilisateur_zTel				= isset($toInfos['utilisateur_zTel']) ? $toInfos['utilisateur_zTel'] : $oRecord->utilisateur_zTel ;
		$oRecord->utilisateur_iTypeId			= isset($toInfos['utilisateur_iTypeId']) ? $toInfos['utilisateur_iTypeId'] : $oRecord->utilisateur_iTypeId ;
		$oRecord->utilisateur_iCivilite			= isset($toInfos['utilisateur_iCivilite']) ? $toInfos['utilisateur_iCivilite'] : $oRecord->utilisateur_iCivilite ;
		$oRecord->utilisateur_zNom				= isset($toInfos['utilisateur_zNom']) ? $toInfos['utilisateur_zNom'] : $oRecord->utilisateur_zNom ;
		$oRecord->utilisateur_zPrenom			= isset($toInfos['utilisateur_zPrenom']) ? $toInfos['utilisateur_zPrenom'] : $oRecord->utilisateur_zPrenom ;
		$oRecord->utilisateur_zMail				= isset($toInfos['utilisateur_zMail']) ? $toInfos['utilisateur_zMail'] : $oRecord->utilisateur_zMail ;
		$oRecord->utilisateur_zLogin			= isset($toInfos['utilisateur_zLogin']) ? $toInfos['utilisateur_zLogin'] : $oRecord->utilisateur_zLogin ;
		$oRecord->utilisateur_zPass				= isset($toInfos['utilisateur_zPass']) ? $toInfos['utilisateur_zPass'] : $oRecord->type_statut ;
		$oRecord->utilisateur_statut			= isset($toInfos['utilisateur_statut']) ? $toInfos['utilisateur_statut'] : $oRecord->utilisateur_statut ;
		$oRecord->utilisateur_iPays				= isset($toInfos['utilisateur_iPays']) ? $toInfos['utilisateur_iPays'] : $oRecord->utilisateur_iPays ;
		$oRecord->utilisateur_decalageHoraire	= isset($toInfos['utilisateur_decalageHoraire']) ? $toInfos['utilisateur_decalageHoraire'] : $oRecord->utilisateur_decalageHoraire ;
		$oRecord->utilisateur_plageHoraireId	= isset($toInfos['utilisateur_plageHoraireId']) ? $toInfos['utilisateur_plageHoraireId'] : $oRecord->utilisateur_plageHoraireId ;
		$oRecord->utilisateur_bSuperviseur		= isset($toInfos['utilisateur_bSuperviseur']) ? $toInfos['utilisateur_bSuperviseur'] : $oRecord->utilisateur_bSuperviseur ;
		$oRecord->utilisateur_bSendExcel		= isset($toInfos['utilisateur_bSendExcel']) ? $toInfos['utilisateur_bSendExcel'] : $oRecord->utilisateur_bSendExcel ;
		if ($oRecord->utilisateur_bSendExcel == 1){
			$oRecord->utilisateur_frequenceSendExcel	= isset($toInfos['utilisateur_frequenceSendExcel']) ? $toInfos['utilisateur_frequenceSendExcel'] : $oRecord->utilisateur_frequenceSendExcel ;
			$oRecord->utilisateur_zSendExcelCopie	= isset($toInfos['utilisateur_zSendExcelCopie']) ? $toInfos['utilisateur_zSendExcelCopie'] : $oRecord->utilisateur_zSendExcelCopie ;
		}else{
			$oRecord->utilisateur_frequenceSendExcel = 0 ;
			$oRecord->utilisateur_zSendExcelCopie = NULL ;
		}
		$oRecord->utilisateur_planBoard			= isset($toInfos['utilisateur_planBoard']) ? $toInfos['utilisateur_planBoard'] : $oRecord->utilisateur_planBoard ;

		if($iId <= 0)
		{
			$oDaoFact->insert($oRecord) ;
		} 
		if($iId > 0)
		{
			$oDaoFact->update($oRecord);
		}

		//Type d'evenement de l'utilisateur 
		$tListeTypeEvenementId = explode(",", $toInfos['listeTypeEvenement']);
		$toTypeevenementsutilisateur = array (); 
		foreach ($tListeTypeEvenementId as $iTypeEvenementId){
			$oTypeevenementsutilisateur = new StdClass (); 
			$oTypeevenementsutilisateur->utilisateur_id = $oRecord->utilisateur_id ;
			$oTypeevenementsutilisateur->typeevenements_id = $iTypeEvenementId ;
			array_push ($toTypeevenementsutilisateur, $oTypeevenementsutilisateur); 
		}
		if($iId > 0){
			$zQuery="DELETE FROM typeevenementsutilisateur WHERE utilisateur_id = " . $oRecord->utilisateur_id;
			$oCnx = jDb::getConnection();
			$oRes = $oCnx->exec($zQuery);	
		}
		$iOrder = 1 ;
		foreach ($toTypeevenementsutilisateur as $oTypeevenementsutilisateur){
			if (isset ($oTypeevenementsutilisateur->typeevenements_id) && $oTypeevenementsutilisateur->typeevenements_id > 0){
				$oDaoF = jDao::get('commun~typeevenementsutilisateur') ;
				$oRec = jDao::createRecord('commun~typeevenementsutilisateur') ;
				$oRec->utilisateur_id = $oTypeevenementsutilisateur->utilisateur_id ;
				$oRec->typeevenements_id = $oTypeevenementsutilisateur->typeevenements_id ;
				$oRec->ordre = $iOrder ;
				$oDaoF->insert($oRec) ;
				$iOrder++;
			}
		}

		//Groupe de l'utilisateur 
		$tListeGroupeId = explode(",", $toInfos['listeGroupe']);
		$toGroupeUtilisateur = array (); 
		foreach ($tListeGroupeId as $iGroupeId){
			$oGroupeUtilisateur = new StdClass (); 
			$oGroupeUtilisateur->utilisateursgroup_utilisateurId = $oRecord->utilisateur_id ;
			$oGroupeUtilisateur->utilisateursgroup_groupId = $iGroupeId ;
			array_push ($toGroupeUtilisateur, $oGroupeUtilisateur); 
		}
		if($iId > 0){
			$zQuery="DELETE FROM utilisateursgroup WHERE utilisateursgroup_utilisateurId = " . $oRecord->utilisateur_id;
			$oCnx = jDb::getConnection();
			$oRes = $oCnx->exec($zQuery);	
		}
		foreach ($toGroupeUtilisateur as $oGroupeUtilisateur){
			if (isset ($oGroupeUtilisateur->utilisateursgroup_groupId) && $oGroupeUtilisateur->utilisateursgroup_groupId > 0){
				$oDaoF = jDao::get('commun~utilisateursgroup') ;
				$oRec = jDao::createRecord('commun~utilisateursgroup') ;
				$oRec->utilisateursgroup_utilisateurId = $oGroupeUtilisateur->utilisateursgroup_utilisateurId;
				$oRec->utilisateursgroup_groupId = $oGroupeUtilisateur->utilisateursgroup_groupId ;
				$oDaoF->insert($oRec) ;
			}
		}


		//Blog de l'utilisateur 
		$tlisteBlogId = explode(",", $toInfos['listeBlog']);
		$toBlog = array (); 
		foreach ($tlisteBlogId as $iClientId){
			$oBlog = new StdClass (); 
			$oBlog->blog_utilisateurId = $oRecord->utilisateur_id ;
			$oBlog->blog_clientId = $iClientId ;
			array_push ($toBlog, $oBlog); 
		}
		if($iId > 0){
			jClasses::inc('utilisateurs~blogSrv');
			blogSrv::deleteByUtilisateurId ($iId);
		}
		foreach ($toBlog as $oBlog){
			$oDaoF = jDao::get('commun~blog') ;
			$oRec = jDao::createRecord('commun~utilisateursgroup') ;
			$oRec->blog_utilisateurId = $oBlog->blog_utilisateurId;
			$oRec->blog_clientId = $oBlog->blog_clientId ;
			$oDaoF->insert($oRec) ;
		}

		return $oRecord ;
	}
	
	/**
	 * Suppression d'un enregistrement
	 * @param int $_iId identifiant de l'objet
	 * @return boolean
	 */
	static function delete($_iId) 
	{
		$oDaoFact 		    = jDao::get('commun~utilisateurs') ;
        $oDaoFact->delete($_iId) ;
	}
	
	static function getUtilisateurConnecter ($_zLogin, $_zPwd){
		$oDao = jDao::get("commun~utilisateurs");
		
		$oConditions = jDao::createConditions();
		$oConditions->addCondition('utilisateur_zLogin','=',$_zLogin);
		$oConditions->addCondition('utilisateur_zPass','=',$_zPwd);

		$rs = $oDao->findBy($oConditions);
		
		$toUser = $rs->fetchAll();

		return $toUser[0]->utilisateur_id;
	}

	/**
	* Chargement d'un client existant dans la base
	*
	* @param int $_iUtilisateurId id de l'utilisateur à charger
	* @return object $oUtilisateur objets client
	*/
	static function chargeUnUtilisateur($_iUtilisateurId){

		$oDao = jDao::get("commun~utilisateurs");
		
		$oUtilisateur = $oDao->get($_iUtilisateurId);
		
		return $oUtilisateur;
	}

	static function listePaysClient (){
		$zSql  = "SELECT DISTINCT(pays_id), pays.* FROM utilisateurs
				INNER JOIN pays ON utilisateur_iPays = pays_id
				GROUP BY pays_id
				ORDER BY pays_zNom " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		
		return $toResults ;
	}
	static function getListeTypeEvenementUilisateur($_iUtilisateurId){
		$zSql  = "SELECT typeevenements.* FROM typeevenementsutilisateur 
				INNER JOIN utilisateurs ON typeevenementsutilisateur.utilisateur_id = utilisateurs.utilisateur_id
				INNER JOIN typeevenements ON typeevenementsutilisateur.typeevenements_id = typeevenements.typeevenements_id
				WHERE utilisateurs.utilisateur_id = ".$_iUtilisateurId."
				GROUP BY typeevenements_id
				ORDER BY typeevenementsutilisateur.ordre  ASC " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		
		return $toResults ;
	}

	static function getDefaultTypeEvenementUilisateur($_iUtilisateurId){
		$zSql  = "SELECT typeevenements.* FROM typeevenementsutilisateur 
				INNER JOIN utilisateurs ON typeevenementsutilisateur.utilisateur_id = utilisateurs.utilisateur_id
				INNER JOIN typeevenements ON typeevenementsutilisateur.typeevenements_id = typeevenements.typeevenements_id
				WHERE utilisateurs.utilisateur_id = ".$_iUtilisateurId."
				GROUP BY typeevenements_id
				ORDER BY typeevenementsutilisateur.ordre  ASC " ;

		$oDBW	  = jDb::getDbWidget() ;
		$oResults = $oDBW->fetchFirst($zSql) ;
		if($oResults != null && isset($oResults->typeevenements_id) && $oResults->typeevenements_id > 0){
			return $oResults->typeevenements_id; 
		}else{
			return ID_TYPE_EVENEMENT_COUR_TELEPHONE ;			
		}
	}
	static function getUtilisateurByNameProf($prof1, $prof2){
		$zSql  = "SELECT * FROM utilisateurs WHERE utilisateur_zNom = '".$prof1."'";
		if ($prof1 != $prof2){
			$zSql  .= " OR utilisateur_zNom = '".$prof2."'" ;
		}
		$zSql  .= " GROUP BY utilisateur_id ORDER BY utilisateur_id ";
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		return $toResults ;
	}

	static function getUtilisateurBySuperviseurId($iSuperviseurId){
		$zSql  = "SELECT * FROM utilisateurs WHERE utilisateur_iSuperviseurId = " . $iSuperviseurId ;
		$zSql  .= " GROUP BY utilisateur_id ORDER BY utilisateur_id ";
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		return $toResults ;
	}
	static function getListeGroupeUtilisateur($_iUtilisateurId){
		$zSql  = "SELECT groupe.* FROM utilisateursgroup
				INNER JOIN utilisateurs ON utilisateursgroup.utilisateursgroup_utilisateurId = utilisateurs.utilisateur_id
				INNER JOIN groupe ON utilisateursgroup.utilisateursgroup_groupId = groupe.groupe_id
				WHERE utilisateurs.utilisateur_id = ".$_iUtilisateurId."
				GROUP BY groupe_id
				ORDER BY groupe.groupe_libelle ASC " ;
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		
		return $toResults ;
	}
}