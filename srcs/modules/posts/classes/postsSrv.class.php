<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class postsSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~posts') ;
		return $oFac->get($_iId) ;
	}
	
	static function getByClientId($_iClientId) 
	{
		$oFac = jDao::create('commun~posts') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('post_clientId', '=', $_iClientId) ;
		return $oFac->findBy($oCond)->fetch() ;
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
	static function listCriteria($_toParams, $_zSortedField = 'id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM posts " ;
		$zSql .= " WHERE 1 = 1 " ;
		if (isset($_toParams[0]->post_title) && $_toParams[0]->post_title != ""){
			$zSql .= " AND post_title LIKE '%".addslashes($_toParams[0]->post_title)."%'";	
		}
		if (isset($_toParams[0]->post_status) && $_toParams[0]->post_status != ""){
			$zSql .= " AND post_status LIKE '%".addslashes($_toParams[0]->post_status)."%'";	
		}
		if (isset($_toParams[0]->post_name) && $_toParams[0]->post_name != ""){
			$zSql .= " AND post_name LIKE '%".addslashes($_toParams[0]->post_name)."%'";	
		}
		if (isset($_toParams[0]->post_type) && $_toParams[0]->post_type != ""){
			$zSql .= " AND post_type LIKE '%".addslashes($_toParams[0]->post_type)."%'";	
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
		    $oDaoFact = jDao::get('commun~posts') ;
            $oRecord = null;
            $iId = isset($toInfos['id']) ? $toInfos['id'] : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~posts') ;
				$toInfos['post_date'] = date("Y-m-d H:i:s");
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
            }
            $oRecord->post_clientId    = isset($toInfos['post_clientId']) ? $toInfos['post_clientId'] : $oRecord->post_clientId ;
            $oRecord->post_author    = isset($toInfos['post_author']) ? $toInfos['post_author'] : $oRecord->post_author ;
			$oRecord->post_date        = isset($toInfos['post_date']) ? $toInfos['post_date'] : $oRecord->post_date ;
			$oRecord->post_title     = isset($toInfos['post_title']) ? $toInfos['post_title'] : $oRecord->post_title ;
			$oRecord->post_status       = isset($toInfos['post_status']) ? $toInfos['post_status'] : $oRecord->post_status ;
			$oRecord->post_name      = isset($toInfos['post_name']) ? $toInfos['post_name'] : $oRecord->post_name ;
			$oRecord->guid       = isset($toInfos['guid']) ? $toInfos['guid'] : $oRecord->guid ;
			$oRecord->post_type        = isset($toInfos['post_type']) ? $toInfos['post_type'] : $oRecord->post_type ;

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
		$oDaoFact 		    = jDao::get('commun~posts') ;
        $oDaoFact->delete($_iId) ;
	}

	static function getListProjetDisponible() 
	{
		$zSql  = "SELECT *
		FROM clients
		WHERE clients.client_id NOT IN(SELECT
		post_clientId
		FROM posts
		WHERE posts.post_clientId IS NOT NULL)
		GROUP BY clients.client_id
		ORDER BY clients.client_zNom" ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;

		return $toResults ;
	}
	static function getPostsAndClient($postId) 
	{
		$zSql  = "SELECT *
		FROM posts
		LEFT JOIN clients
		ON posts.post_clientId = clients.client_id
		WHERE posts.id = " . $postId;

		$oDBW	  = jDb::getDbWidget() ;
		$oResult = $oDBW->fetchFirst($zSql) ;
		return $oResult ;
	}
	static function getPostsNoClient() 
	{
		$zSql  = "SELECT *
		FROM posts
		WHERE posts.post_clientId IS NULL " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResult = $oDBW->fetchAll($zSql) ;
		return $toResult ;
	}

	static function updatePostClientId($postId, $clientId)
	{
		$zSql = " UPDATE posts SET post_clientId = ".$clientId." WHERE id = " . $postId ;
		$oCnx = jDb::getConnection();
		$oCnx->exec($zSql);	
	}
}