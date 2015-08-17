<?php
/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class utilisateursdisponibiliteSrv 
{
/**
* utilisateursdisponibilite_jour 
* 1=LUNDI
* 2=MARDI
* 3=MERCREDI
* 4=JEUDI
* 5=VENDREDI
* 
* utilisateursdisponibilite_type
* ID_TYPE_EVENEMENT_DISPONIBLE = 13
* ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE = 18
* ID_TYPE_EVENEMENT_INDISPONIBLE = 14
*/
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~utilisateursdisponibilite') ;
		return $oFac->get($_iId) ;
	}

	static function listCriteria($_toParams, $_zSortedField = 'utilisateursdisponibilite_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM utilisateursdisponibilite " ;
		$zSql .= " WHERE 1 = 1 " ;
		if (isset($_toParams['utilisateursdisponibilite_jour'])){
			$zSql .= " AND utilisateursdisponibilite_jour = " .  $_toParams['utilisateursdisponibilite_jour'];
		}
		if (isset($_toParams['utilisateursdisponibilite_utilisateur'])){
			$zSql .= " AND utilisateursdisponibilite_utilisateur = " .  $_toParams['utilisateursdisponibilite_utilisateur'];
		}
		if (isset($_toParams['utilisateursdisponibilite_type'])){
			$zSql .= " AND utilisateursdisponibilite_type = " .  $_toParams['utilisateursdisponibilite_type'];
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
	 * @param array $toInfos les parametre à modifier ou à inserer
	 * @return object
	 */
	static function save($toInfos) 
	{		
		$oDaoFact = jDao::get('commun~utilisateursdisponibilite') ;
		$oRecord = null;
		$iId = isset($toInfos['utilisateursdisponibilite_id']) ? $toInfos['utilisateursdisponibilite_id'] : 0 ;
		if($iId <= 0) // nouveau
		{
			$oRecord = jDao::createRecord('commun~utilisateursdisponibilite') ;
		}
		else // update
		{
			$oRecord = $oDaoFact->get($iId) ;
		}
		$oRecord->utilisateursdisponibilite_utilisateur = isset($toInfos['utilisateursdisponibilite_utilisateur']) ? $toInfos['utilisateursdisponibilite_utilisateur'] : $oRecord->utilisateursdisponibilite_utilisateur;

		$oRecord->utilisateursdisponibilite_jour = isset($toInfos['utilisateursdisponibilite_jour']) ? $toInfos['utilisateursdisponibilite_jour'] : $oRecord->utilisateursdisponibilite_jour ;
		
		$oRecord->utilisateursdisponibilite_type = isset($toInfos['utilisateursdisponibilite_type']) ? $toInfos['utilisateursdisponibilite_type'] : $oRecord->utilisateursdisponibilite_type ;
		
		$oRecord->utilisateursdisponibilite_debut = isset($toInfos['utilisateursdisponibilite_debut']) ? $toInfos['utilisateursdisponibilite_debut'] : $oRecord->utilisateursdisponibilite_debut ;
		
		$oRecord->utilisateursdisponibilite_fin = isset($toInfos['utilisateursdisponibilite_fin']) ? $toInfos['utilisateursdisponibilite_fin'] : $oRecord->utilisateursdisponibilite_fin ;

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
		$oDaoFact 		    = jDao::get('commun~utilisateursdisponibilite') ;
        $oDaoFact->delete($_iId) ;
	}
	/**
	 * Suppression de tous les disponibilite utilisateurs
	 * @param int $_iUtilisateurId identifiant de l'utilisateur
	 * @param int $iType type dispo ou indispo si =0, on supprime les 2
	 * @return toResults
	 */
	static function deleteDisponibiliteUtilisateur($iUtilisateurId, $iType = 0){
		$zSql  = "DELETE FROM utilisateursdisponibilite WHERE utilisateursdisponibilite_utilisateur = " . $iUtilisateurId ;
		if (isset($iType) && $iType > 0){
			$zSql  .= " utilisateursdisponibilite_type = " . $iType ;			
		}
		$oCnx = jDb::getConnection();
		$oRes = $oCnx->exec($zSql);	
	}
	
	/**
	*
	*
	*
	*
	*/
	static function generateDispoIndispo($oProf, $toDateListe, $zTable, $iDure, $iDureeTypeId){
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('evenement~evenementDispoSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
		jClasses::inc('utilisateurs~utilisateursIndisponibiliteSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');

		$toParams1['utilisateursdisponibilite_utilisateur'] = $oProf->utilisateur_id;
		$toEvent = self::listCriteria ($toParams1);
		if (isset($toEvent['toListes']) && sizeof($toEvent['toListes']) > 0){
			$zSql = "";
			for($i=0; $i<sizeof($toDateListe); $i++){
				foreach ($toEvent['toListes'] as $oDispo){		
					if ($oDispo->utilisateursdisponibilite_jour == $i+1){
						$toD1 = toolDate::gettTimeListTempPlage1($oDispo->utilisateursdisponibilite_debut, $oDispo->utilisateursdisponibilite_fin, $zTable);
						foreach ($toD1 as $oD){
							$iNbre = evenementDispoSrv::getEventByDate($toDateListe[$i] . " " . $oD . ":00", $oProf->utilisateur_plageHoraireId, $oProf->utilisateur_id); 
							if ($iNbre == 0){
								if ($oDispo->utilisateursdisponibilite_type == ID_TYPE_EVENEMENT_INDISPONIBLE){
										$iTypeEvent = ID_TYPE_EVENEMENT_INDISPONIBLE ;
								}else{
									if ($oProf->utilisateur_id == AUTOPLANNIFICATION_ID_CATRIONA){
										$iTypeEvent = ID_TYPE_EVENEMENT_DISPONIBLE ;
									}else{
										$iTypeEvent = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ;
									}
								}
								$zQueryRes = evenementDispoSrv::generateEventDispoIndispo($iTypeEvent, $oProf->utilisateur_id, $toDateListe[$i] . " " . $oD . ":00", $iDure, $iDureeTypeId) ;
								//$zSql .= " " . $zQueryRes ;
								evenementDispoSrv::insertEventDispoIndispo($zQueryRes);
							}
						}
					}
				}
			}
		}
		/*if ($zSql != ''){
			evenementDispoSrv::insertEventDispoIndispo($zSql);
		}*/
	}

}