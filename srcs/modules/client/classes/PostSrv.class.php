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

class PostSrv 
{
	static function chargePosteParId ($iPostId){
		$oDBW	  = jDb::getDbWidget() ;
		return $oDBW->fetchFirst("SELECT * FROM wp_posts WHERE wp_posts.ID = " . $iPostId) ;
	}
	static function chargePosteParIdPostBlog ($iPostId){
		$oDBW	  = jDb::getDbWidget('blog') ;
		return $oDBW->fetchFirst("SELECT * FROM wp_posts WHERE wp_posts.ID = " . $iPostId) ;
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
	static function listCriteria($_toParams, $_zSortedField = 'ID', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');

		/*if (isset($_toParams[0]->fo) && $_toParams[0]->fo == 1){
			$oUser = jAuth::getUserSession();
			$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		}*/

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM wp_posts " ;
		$zSql .= " WHERE 1=1 " ;
		if (isset($_toParams->zTitlePost) && is_array ($_toParams->zTitlePost)){
			$iCpt = 0;  
			foreach ($_toParams->zTitlePost as $zTitlePost){
				if ($iCpt == 0)
					$zSql .= " AND ("; 
				else
					$zSql .= " OR "; 
				
				$zSql .= " post_title LIKE '%" . $zTitlePost . "%'" ;
				$iCpt++; 
				if ($iCpt == sizeof ($_toParams->zTitlePost))
					$zSql .= " ) "; 
			}			
		}else{

			if (isset($_toParams[0]->zDatePost) && $_toParams[0]->zDatePost != ""){
				$zSql .= " AND post_date BETWEEN '".toolDate::toDateSQL($_toParams[0]->zDatePost)." 00:00:00' AND '".toolDate::toDateSQL($_toParams[0]->zDatePost)." 23:59:59' " ;
			}
			if (isset($_toParams[0]->zTypePost) && $_toParams[0]->zTypePost != 0){
				if ($_toParams[0]->zTypePost == 1){
					$zSql .= " AND post_type = 'revision'";
				}else{
					$zSql .= " AND post_type = 'post'";
				}
			}
			if (isset($_toParams[0]->zTitlePost) && $_toParams[0]->zTitlePost != ""){
				$zSql .= " AND post_title LIKE '%" . $_toParams[0]->zTitlePost . "%'" ;
			}
			if (isset($_toParams[0]->id) && $_toParams[0]->id != ""){
				$zSql .= " AND ID = " . $_toParams[0]->id;
			}
		}
		
		$zSql .= " GROUP BY " . $_zSortedField;
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;

		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}
	static function listCriteriaPostBlog($_toParams, $_zSortedField = 'ID', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');

		/*if (isset($_toParams[0]->fo) && $_toParams[0]->fo == 1){
			$oUser = jAuth::getUserSession();
			$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		}*/

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM wp_posts " ;
		$zSql .= " WHERE 1=1 " ;
		if (isset($_toParams->zTitlePost) && is_array ($_toParams->zTitlePost)){
			$iCpt = 0;  
			foreach ($_toParams->zTitlePost as $zTitlePost){
				if ($iCpt == 0)
					$zSql .= " AND ("; 
				else
					$zSql .= " OR "; 
				
				$zSql .= " post_title LIKE '%" . $zTitlePost . "%'" ;
				$iCpt++; 
				if ($iCpt == sizeof ($_toParams->zTitlePost))
					$zSql .= " ) "; 
			}			
		}else{

			if (isset($_toParams[0]->zDatePost) && $_toParams[0]->zDatePost != ""){
				$zSql .= " AND post_date BETWEEN '".toolDate::toDateSQL($_toParams[0]->zDatePost)." 00:00:00' AND '".toolDate::toDateSQL($_toParams[0]->zDatePost)." 23:59:59' " ;
			}
			if (isset($_toParams[0]->zTypePost) && $_toParams[0]->zTypePost != 0){
				if ($_toParams[0]->zTypePost == 1){
					$zSql .= " AND post_type = 'revision'";
				}else{
					$zSql .= " AND post_type = 'post'";
				}
			}
			if (isset($_toParams[0]->zTitlePost) && $_toParams[0]->zTitlePost != ""){
				$zSql .= " AND post_title LIKE '%" . $_toParams[0]->zTitlePost . "%'" ;
			}
			if (isset($_toParams[0]->id) && $_toParams[0]->id != ""){
				$zSql .= " AND ID = " . $_toParams[0]->id;
			}
		}
		
		$zSql .= " GROUP BY " . $_zSortedField;
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;

		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}
}