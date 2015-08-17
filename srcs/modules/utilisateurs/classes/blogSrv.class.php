<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class blogSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~blog') ;
		return $oFac->get($_iId) ;
	}

	static function listCriteria($_toParams, $_zSortedField = 'blog_utilisateurId', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM blog " ;
		$zSql .= " INNER JOIN utilisateurs ON utilisateurs.utilisateur_id = blog.blog_utilisateurId " ;
		$zSql .= " INNER JOIN clients ON clients.client_id = blog.blog_clientId " ;
		$zSql .= " INNER JOIN societe ON clients.client_iSociete = societe.societe_id " ;
		$zSql .= " WHERE 1 = 1 " ;
		if (isset($_toParams[0]->blog_utilisateurId) && $_toParams[0]->blog_utilisateurId != 0){
			$zSql .= " AND blog_utilisateurId = ".$_toParams[0]->blog_utilisateurId;	
		}
		if (isset($_toParams[0]->blog_clientId) && $_toParams[0]->blog_clientId != 0){
			$zSql .= " AND blog_clientId = ".$_toParams[0]->blog_clientId;	
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
		    $oDaoFact = jDao::get('commun~blog') ;
            $oRecord = null;
			$oRecord = jDao::createRecord('commun~typeutilisateurs') ;
            $oRecord->blog_utilisateurId    = isset($toInfos['blog_utilisateurId']) ? $toInfos['blog_utilisateurId'] : $oRecord->blog_utilisateurId ;
			$oRecord->blog_clientId     = isset($toInfos['blog_clientId']) ? $toInfos['blog_clientId'] : $oRecord->blog_clientId ;
			$oDaoFact->insert($oRecord) ;
            return $oRecord ;
	}
	
	/**
	 * Suppression d'un enregistrement
	 * @param int $_iId identifiant de l'objet
	 * @return boolean
	 */
	static function deleteByUtilisateurId($_iId) 
	{
		$zQuery = " DELETE FROM blog WHERE blog_utilisateurId IN (".$_iId.")";
		$oCnx = jDb::getConnection();
		$oRes = $oCnx->exec($zQuery);
	}
	
}