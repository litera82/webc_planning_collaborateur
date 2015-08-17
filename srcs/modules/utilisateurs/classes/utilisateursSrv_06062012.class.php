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
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM utilisateurs " ;
		$zSql .= " WHERE 1 = 1 " ;
		if (isset($_toParams['utilisateur_statut'])){
			$zSql .= " ANd utilisateur_statut = " .  $_toParams['utilisateur_statut'];
		}
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
			$oRecord->utilisateur_plageHoraireId	= isset($toInfos['utilisateur_plageHoraireId']) ? $toInfos['utilisateur_plageHoraireId'] : $oRecord->utilisateur_plageHoraireId ;

			if($iId <= 0)
            {
            	$oDaoFact->insert($oRecord) ;
            } 
            if($iId > 0)
            {
                $oDaoFact->update($oRecord);
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
}