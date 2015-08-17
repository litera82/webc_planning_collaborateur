<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class wppostsSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~wp_posts_to_import') ;
		return $oFac->get($_iId) ;
	}
	static function getByTitle($_zTitle, $_id) 
	{
		$zSql  = "SELECT ID FROM wp_posts_to_import WHERE wp_posts_to_import.post_title = '".addslashes($_zTitle)."' AND ID NOT IN (".$_id.")" ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResult = $oDBW->fetchAll($zSql) ;
		return $toResult ;	
	}
	
	static function getByClientId($_iClientId) 
	{
		$zSql = "SELECT
		wp_posts_to_import.*
		FROM wp_posts_to_import
		LEFT JOIN wp_postsclients
		ON wp_posts_to_import.ID = wp_postsclients.post_id
		WHERE wp_postsclients.client_id = " . $_iClientId;

		$oDBW	  = jDb::getDbWidget() ;
		$oResults = $oDBW->fetchFirst($zSql) ;

		return $oResults ;
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
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM wp_posts_to_import " ;
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
		    $oDaoFact = jDao::get('commun~wp_posts_to_import') ;
            $oRecord = null;
            $iId = isset($toInfos['id']) ? $toInfos['id'] : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~wp_posts_to_import') ;
				$toInfos['post_date'] = date("Y-m-d H:i:s");
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
            }
            $oRecord->post_author		= isset($toInfos['post_author']) ? $toInfos['post_author'] : $oRecord->post_author ;
			$oRecord->post_date			= isset($toInfos['post_date']) ? $toInfos['post_date'] : $oRecord->post_date ;
			$oRecord->post_date_gmt		= isset($toInfos['post_date_gmt']) ? $toInfos['post_date_gmt'] : $oRecord->post_date ;
			$oRecord->post_title		= isset($toInfos['post_title']) ? $toInfos['post_title'] : $oRecord->post_title ;
			$oRecord->post_status       = isset($toInfos['post_status']) ? $toInfos['post_status'] : $oRecord->post_status ;
			$oRecord->post_name			= isset($toInfos['post_name']) ? $toInfos['post_name'] : $oRecord->post_name ;
			$oRecord->guid				= isset($toInfos['guid']) ? $toInfos['guid'] : $oRecord->guid ;
			$oRecord->post_type			= isset($toInfos['post_type']) ? $toInfos['post_type'] : $oRecord->post_type ;
			$oRecord->post_content      = isset($toInfos['post_content']) ? $toInfos['post_content'] : $oRecord->post_type ;

			if($iId <= 0)
            {
            	$oDaoFact->insert($oRecord) ;
            } 
            if($iId > 0)
            {
                $oDaoFact->update($oRecord);
            }

			if (isset($toInfos['post_clientId']) && $toInfos['post_clientId'] > 0){
				$zSql1 = "DELETE FROM wp_postsclients WHERE post_id = " . $oRecord->ID ;
				$oCnx = jDb::getConnection();
				$oCnx->exec($zSql1);	

				$zSql2 = "INSERT INTO wp_postsclients(client_id, post_id) VALUES (".$toInfos['post_clientId'].",".$oRecord->ID.")" ;
				$oCnx = jDb::getConnection();
				$oCnx->exec($zSql2);	
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
		$oDaoFact 		    = jDao::get('commun~wp_posts_to_import') ;
        $oDaoFact->delete($_iId) ;
	}

	static function getListProjetDisponible() 
	{
		$zSql  = "SELECT *
		FROM clients
		WHERE clients.client_id NOT IN(SELECT
		client_id
		FROM wp_postsclients
		WHERE wp_postsclients.client_id IS NOT NULL)
		GROUP BY clients.client_id
		ORDER BY clients.client_zNom" ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;

		return $toResults ;
	}
	static function getPostsAndClient($postId) 
	{
		$zSql  = "SELECT *
		FROM wp_posts_to_import
		LEFT JOIN wp_postsclients ON wp_postsclients.post_id = wp_posts_to_import.ID
		LEFT JOIN clients ON wp_postsclients.client_id = clients.client_id
		WHERE wp_posts_to_import.ID= " . $postId;

		$oDBW	  = jDb::getDbWidget() ;
		$oResult = $oDBW->fetchFirst($zSql) ;
		return $oResult ;
	}
	static function getPostsNoClient() 
	{
		$zSql  = "SELECT *
		FROM wp_posts_to_import
		WHERE wp_posts_to_import.ID NOT IN(SELECT
		post_id
		FROM wp_postsclients
		GROUP BY post_id) ORDER BY wp_posts_to_import.ID" ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResult = $oDBW->fetchAll($zSql) ;
		return $toResult ;
	}
	static function getAllPosts() 
	{
		$zSql  = "SELECT * FROM wp_posts_to_import GROUP BY ID ORDER BY ID" ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResult = $oDBW->fetchAll($zSql) ;
		return $toResult ;
	}
	static function getAllPostsClients($_iSamePostId, $_iPostId) 
	{
		$zSql  = "SELECT * FROM wp_postsclients WHERE post_id IN (" . $_iSamePostId . "," . $_iPostId . ")";

		$oDBW	  = jDb::getDbWidget() ;
		$toResult = $oDBW->fetchAll($zSql) ;
		return $toResult ;
	}
	static function getPostsClients($_iPostId) 
	{
		$zSql  = "SELECT client_id FROM wp_postsclients WHERE post_id  = " . $_iPostId ;

		$oDBW	  = jDb::getDbWidget() ;
		$oResult = $oDBW->fetchFirst($zSql) ;
		return $oResult ;
	}

	static function updateEventAndDeleteClient($_iClientId, $_iClientIdToChange) 
	{
		$zSql1  = "UPDATE evenement SET evenement_iStagiaire = " . $_iClientId . " WHERE evenement_iStagiaire = " . $_iClientIdToChange;
		$oCnx = jDb::getConnection();
		$oCnx->exec($zSql1);	

		$zSql2  = "DELETE FROM clients WHERE client_id = " . $_iClientIdToChange;
		$oCnx = jDb::getConnection();
		$oCnx->exec($zSql2);	
	}

	static function updatePostClientId($postId, $clientId)
	{
		$zSql1 = "DELETE FROM wp_postsclients WHERE post_id = " . $postId;
		$oCnx = jDb::getConnection();
		$oCnx->exec($zSql1);	

		$zSql2 = "INSERT INTO wp_postsclients(client_id, post_id) VALUES (".$clientId.",".$postId.")" ;
		$oCnx = jDb::getConnection();
		$oCnx->exec($zSql2);	
	}

	static function deletePostClientByPostId($postId)
	{
		$zSql1 = "DELETE FROM wp_postsclients WHERE post_id = " . $postId;
		$oCnx = jDb::getConnection();
		$oCnx->exec($zSql1);	
	}

	/*static function deleteOtherPosts (){
		$zSql1 = "SELECT * FROM wp_posts_to_import
					WHERE ID NOT IN(SELECT
									  ID
									FROM wp_posts_to_import
									WHERE post_type = 'post'
										AND post_status NOT LIKE '%draft%'
									GROUP BY ID
									ORDER BY id)" ;
		$oDBW	  = jDb::getDbWidget() ;
		$toResult = $oDBW->fetchAll($zSql1) ;
		foreach ($toResult as $oResult){
			if ($oResult->ID > 0){
				$zSql2 = "DELETE FROM wp_postsclients WHERE post_id = " . $oResult->ID ;
				$oCnx = jDb::getConnection();
				$oCnx->exec($zSql2);	

				$zSql3 = "DELETE FROM wp_posts_to_import WHERE ID = " . $oResult->ID ;
				$oCnx = jDb::getConnection();
				$oCnx->exec($zSql3);	
			}
		}

		return self::getAllPosts ();
	}*/
	static function deleteOtherPosts (){
		$zSql1 = "SELECT * FROM wp_posts
					WHERE ID NOT IN(SELECT
									  wp_posts.ID
									FROM wp_posts
									WHERE post_type = 'post'
										AND post_status NOT LIKE '%draft%'
									GROUP BY wp_posts.ID
									ORDER BY wp_posts.ID)" ;
		$oDBW	  = jDb::getDbWidget() ;
		$toResult = $oDBW->fetchAll($zSql1) ;
		foreach ($toResult as $oResult){
			if ($oResult->ID > 0){
				$zSql3 = "DELETE FROM wp_posts WHERE ID = " . $oResult->ID ;
				$oCnx = jDb::getConnection();
				$oCnx->exec($zSql3);	
			}
		}

		$zSql1 = "SELECT *
					FROM wp_posts
					WHERE wp_posts.ID NOT IN(SELECT
														   wp_posts_to_import.ID
														 FROM wp_posts_to_import
														 GROUP BY wp_posts_to_import.ID
														 ORDER BY wp_posts_to_import.ID)
					GROUP BY wp_posts.ID
					ORDER BY wp_posts.ID" ;
		$oDBW	  = jDb::getDbWidget() ;
		$toResult = $oDBW->fetchAll($zSql1) ;
		foreach ($toResult as $oResult){
			if ($oResult->ID > 0){
				$ins = "INSERT INTO wp_posts_to_import
							(ID, 
							 post_author,
							 post_date,
							 post_date_gmt,
							 post_title,
							 post_status,
							 post_name,
							 guid,
							 post_type)
				VALUES (".$oResult->ID.",
				".$oResult->post_author.",
				'".$oResult->post_date."',
				'".$oResult->post_date_gmt."',
				'".addslashes($oResult->post_title)."',
				'".addslashes($oResult->post_status)."',
				'".addslashes($oResult->post_name)."',
				'".addslashes($oResult->guid)."',
				'".addslashes($oResult->post_type)."')";

				$oCnx = jDb::getConnection();
				$oCnx->exec($ins);	
			}
		}
		return self::getAllPosts ();
	}

	static function truncatewpposttoinsert(){
		$zSql3 = "TRUNCATE TABLE wp_posts";
		$oCnx = jDb::getConnection();
		$oCnx->exec($zSql3);	
	}

}