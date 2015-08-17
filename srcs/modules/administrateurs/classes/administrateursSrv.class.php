<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class administrateursSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~administrateurs') ;
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
	static function listCriteria($_toParams, $_zSortedField = 'admin_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM administrateurs " ;
		$zSql .= " WHERE 1 = 1 " ;
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
		    $oDaoFact = jDao::get('commun~administrateurs') ;
            $oRecord = null;
            $iId = isset($toInfos['admin_id']) ? $toInfos['admin_id'] : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~administrateurs') ;
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
            }
            $oRecord->admin_civilite    = isset($toInfos['admin_civilite']) ? $toInfos['admin_civilite'] : $oRecord->admin_civilite ;
			$oRecord->admin_zNom        = isset($toInfos['admin_zNom']) ? $toInfos['admin_zNom'] : $oRecord->admin_zNom ;
			$oRecord->admin_zPrenom     = isset($toInfos['admin_zPrenom']) ? $toInfos['admin_zPrenom'] : $oRecord->admin_zPrenom ;
			$oRecord->admin_zMail       = isset($toInfos['admin_zMail']) ? $toInfos['admin_zMail'] : $oRecord->admin_zMail ;
			$oRecord->login      = isset($toInfos['admin_zLogin']) ? $toInfos['admin_zLogin'] : $oRecord->login ;
			$oRecord->password       = isset($toInfos['admin_zPass']) ? $toInfos['admin_zPass'] : $oRecord->password ;
			$oRecord->admin_zTel        = isset($toInfos['admin_zTel']) ? $toInfos['admin_zTel'] : $oRecord->admin_zTel ;
			$oRecord->admin_iStatut     = isset($toInfos['admin_iStatut']) ? $toInfos['admin_iStatut'] : $oRecord->admin_iStatut ;

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
		$oDaoFact 		    = jDao::get('commun~administrateurs') ;
        $oDaoFact->delete($_iId) ;
	}
	
}