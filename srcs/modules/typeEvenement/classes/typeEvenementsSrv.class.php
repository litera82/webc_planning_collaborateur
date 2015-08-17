<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class typeEvenementsSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~typeevenements') ;
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
	static function listCriteria($_oParams = NULL, $_zSortedField = 'typeevenements_iOrdre', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM typeevenements " ;
		$zSql .= " WHERE 1 = 1 " ;
		if (isset ($_oParams->typeevenements_iStatut)){
			$zSql .= " AND typeevenements_iStatut = " . $_oParams->typeevenements_iStatut;
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
	 * @param array $_toParams les parametre à modifier ou à insserer
	 * @return object
	 */
	static function save($toInfos) 
	{		
		    $oDaoFact = jDao::get('commun~typeevenements') ;
            $oRecord = null;
            $iId = isset($toInfos['typeevenements_id']) ? $toInfos['typeevenements_id'] : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~typeevenements') ;
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
            }
            $oRecord->typeevenements_zLibelle    = isset($toInfos['typeevenements_zLibelle']) ? $toInfos['typeevenements_zLibelle'] : $oRecord->typeevenements_zLibelle ;
			$typeevenements_iDure				 = isset($toInfos['typeevenements_iDure']) ? $toInfos['typeevenements_iDure'] : $oRecord->typeevenements_iDure ;
			$tzTypeevenementsiDure = explode (' ', $typeevenements_iDure);
			if (sizeof($tzTypeevenementsiDure) > 0){
				$oRecord->typeevenements_iDure        = $tzTypeevenementsiDure[0] ;

				if ($tzTypeevenementsiDure[1] == 'minutes'){
					$oRecord->typeevenements_iDureeTypeId        = 2 ;
				}else{
					$oRecord->typeevenements_iDureeTypeId        = 1 ;
				}
			}else{
				$oRecord->typeevenements_iDure        = 30 ;
				$oRecord->typeevenements_iDureeTypeId = 2 ;
			}
			$oRecord->typeevenements_iDure        = isset($toInfos['typeevenements_iDure']) ? $toInfos['typeevenements_iDure'] : $oRecord->typeevenements_iDure ;
			
			$oRecord->typeevenements_zCouleur     = isset($toInfos['typeevenements_zCouleur']) ? $toInfos['typeevenements_zCouleur'] : $oRecord->typeevenements_zCouleur ;
			$oRecord->typeevenements_iStatut       = isset($toInfos['typeevenements_iStatut']) ? $toInfos['typeevenements_iStatut'] : $oRecord->typeevenements_iStatut ;
			$oRecord->typeevenements_iStagiaireActif       = isset($toInfos['typeevenements_iStagiaireActif']) ? $toInfos['typeevenements_iStagiaireActif'] : $oRecord->typeevenements_iStagiaireActif ;


			if($iId <= 0)
            {
				$oRecord->typeevenements_iOrdre = intval(self::selectOrdreMax())+1;
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
		$oFac = jDao::create('commun~typeevenements') ;
		$oTypeEvent = $oFac->get($_iId) ;

		$oDaoFact = jDao::get('commun~typeevenements') ;
        $oDaoFact->delete($_iId) ;

		if (isset ($oTypeEvent->typeevenements_iOrdre)){
			$zSql = " UPDATE typeevenements SET typeevenements_iOrdre = typeevenements_iOrdre - 1 WHERE typeevenements_iOrdre > " . $oTypeEvent->typeevenements_iOrdre;
			$oCnx = jDb::getConnection();
			$oCnx->exec($zSql);	
		}
	}

	static function testSupprimable ($_iTypeEvenementId){
		$cnx  = jDb::getConnection();

		$zSql = " SELECT *";
		$zSql .= " FROM evenement";
		$zSql .= " WHERE evenement_iTypeEvenementId =" . $_iTypeEvenementId;
		$zSql .= " LIMIT 0 , 1";
		$oDBW		= jDb::getDbWidget();
		$toEvenement	=  $oDBW->fetchAll($zSql);
		if (sizeof($toEvenement) > 0){
			return 1;
		}else{
			return 0;
		}
	}

	static function chargeParOrdre ($_iOrdre){
		$cnx  = jDb::getConnection();

		$zSql = " SELECT *";
		$zSql .= " FROM typeevenements";
		$zSql .= " WHERE typeevenements_iOrdre = " . $_iOrdre;
		$zSql .= " LIMIT 0 , 1";

		$oDBW		= jDb::getDbWidget();
		$toRes = $oDBW->fetchAll($zSql);
		return $toRes[0];
	}
	static function selectOrdreMax (){
		$cnx  = jDb::getConnection();
		$zSql = " SELECT MAX(typeevenements.typeevenements_iOrdre) as ordre FROM typeevenements ";
		$oDBW		= jDb::getDbWidget();
		$toRes = $oDBW->fetchAll($zSql);
		return $toRes[0]->ordre;
	}
	static function permuter ($_iTypeEvenementId, $_iAction=0){
		if ($_iTypeEvenementId > 0){
			if ($_iAction==1) {//descendre
				$oNoeudADescendre = self::getById($_iTypeEvenementId);
				$oNoeudAMonter = self::chargeParOrdre ($oNoeudADescendre->typeevenements_iOrdre + 1);
			}else{//monter
				$oNoeudAMonter = self::getById($_iTypeEvenementId);
				$oNoeudADescendre = self::chargeParOrdre ($oNoeudAMonter->typeevenements_iOrdre - 1);
			}

			$oCnx = jDb::getConnection();
			
			//descendre
		    $oDaoFact = jDao::get('commun~typeevenements') ;
            $oRecord = null;
			$oRecord = $oDaoFact->get($oNoeudADescendre->typeevenements_id) ;
			$oRecord->typeevenements_iOrdre = $oNoeudADescendre->typeevenements_iOrdre + 1;
			$oDaoFact->update($oRecord);

		    $oDaoFact = jDao::get('commun~typeevenements') ;
            $oRecord = null;
			$oRecord = $oDaoFact->get($oNoeudAMonter->typeevenements_id) ;
			$oRecord->typeevenements_iOrdre = $oNoeudAMonter->typeevenements_iOrdre - 1;
			$oDaoFact->update($oRecord);
		}

		return true;
	}


}
?>