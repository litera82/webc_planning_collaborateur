<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class plageHoraireSrv 
{
	
	static function chargerTous() 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM plagehoraire " ;
		$zSql .= " WHERE 1 = 1 " ;
		$zSql .= " ORDER BY plagehoraire_id ASC" ;

		$oDBW	   = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;

		return $toResults ;
	}
}
?>