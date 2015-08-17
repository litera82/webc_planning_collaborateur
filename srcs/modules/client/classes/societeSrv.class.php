<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage societe
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class societeSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~societe') ;
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
	static function listCriteria($_toParams, $_zSortedField = 'societe_zNom', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM societe " ;
		$zSql .= " WHERE 1 " ;

		if (isset($_toParams[0]->statut) && $_toParams[0]->statut == 1){
			$zSql .= " AND societe_iStatut = 1";	
		}
		if (isset($_toParams[0]->statut) && $_toParams[0]->statut == 2){
			$zSql .= " AND societe_iStatut = 0";	
		}
		if (isset($_toParams[0]->societe_zNom) && $_toParams[0]->societe_zNom != ""){
			$zSql .= " AND societe_zNom = '".trim(addslashes($_toParams[0]->societe_zNom))."'";	
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
		    $oDaoFact = jDao::get('commun~societe') ;
            $oRecord = null;
            $iId = isset($toInfos['societe_id']) ? $toInfos['societe_id'] : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~societe') ;
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
            }

            $oRecord->societe_zNom    = isset($toInfos['societe_zNom']) ? $toInfos['societe_zNom'] : $oRecord->societe_zNom ;
			$oRecord->societe_iStatut     = isset($toInfos['societe_iStatut']) ? $toInfos['societe_iStatut'] : $oRecord->societe_iStatut ;

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
	
}