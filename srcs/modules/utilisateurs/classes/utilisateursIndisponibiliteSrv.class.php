<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage administrateurs
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class utilisateursIndisponibiliteSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~utilisateursindisponibilite') ;
		return $oFac->get($_iId) ;
	}

	static function listCriteria($_toParams, $_zSortedField = 'utilisateur_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM utilisateursindisponibilite " ;
		$zSql .= " WHERE 1 = 1 " ;

		if (isset($_toParams['utilisateur_id'])){
			$zSql .= " AND utilisateur_id = " .  $_toParams['utilisateur_id'];
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
		$oDaoFact = jDao::get('commun~utilisateursindisponibilite') ;
		$oRecord = null;
		$iId = isset($toInfos['id']) && $toInfos['id'] > 0 ? $toInfos['id'] : 0 ;
		if($iId <= 0){// nouveau
			$oRecord = jDao::createRecord('commun~utilisateursindisponibilite') ;
		}
		else{// update
			$oRecord = $oDaoFact->get($iId) ;
		}

		$oRecord->utilisateur_id = isset($toInfos['utilisateur_id']) ? $toInfos['utilisateur_id'] : $oRecord->utilisateur_id ;
		$oRecord->lundi_debut_matin = isset($toInfos['lundi_debut_matin']) ? $toInfos['lundi_debut_matin'] : $oRecord->lundi_debut_matin ;
		$oRecord->lundi_fin_matin = isset($toInfos['lundi_fin_matin']) ? $toInfos['lundi_fin_matin'] : $oRecord->lundi_fin_matin ;
		$oRecord->lundi_debut_apres_midi = isset($toInfos['lundi_debut_apres_midi']) ? $toInfos['lundi_debut_apres_midi'] : $oRecord->lundi_debut_apres_midi ;
		$oRecord->lundi_fin_soir = isset($toInfos['lundi_fin_soir']) ? $toInfos['lundi_fin_soir'] : $oRecord->lundi_fin_soir ;
		$oRecord->mardi_debut_matin = isset($toInfos['mardi_debut_matin']) ? $toInfos['mardi_debut_matin'] : $oRecord->mardi_debut_matin ;
		$oRecord->mardi_fin_matin = isset($toInfos['mardi_fin_matin']) ? $toInfos['mardi_fin_matin'] : $oRecord->mardi_fin_matin ;
		$oRecord->mardi_debut_apres_midi = isset($toInfos['mardi_debut_apres_midi']) ? $toInfos['mardi_debut_apres_midi'] : $oRecord->mardi_debut_apres_midi ;
		$oRecord->mardi_fin_soir = isset($toInfos['mardi_fin_soir']) ? $toInfos['mardi_fin_soir'] : $oRecord->mardi_fin_soir ;
		$oRecord->mercredi_debut_matin = isset($toInfos['mercredi_debut_matin']) ? $toInfos['mercredi_debut_matin'] : $oRecord->mercredi_debut_matin ;
		$oRecord->mercredi_fin_matin = isset($toInfos['mercredi_fin_matin']) ? $toInfos['mercredi_fin_matin'] : $oRecord->mercredi_fin_matin ;
		$oRecord->mercredi_debut_apres_midi = isset($toInfos['mercredi_debut_apres_midi']) ? $toInfos['mercredi_debut_apres_midi'] : $oRecord->mercredi_debut_apres_midi ;
		$oRecord->mercredi_fin_soir = isset($toInfos['mercredi_fin_soir']) ? $toInfos['mercredi_fin_soir'] : $oRecord->mercredi_fin_soir ;
		$oRecord->jeudi_debut_matin = isset($toInfos['jeudi_debut_matin']) ? $toInfos['jeudi_debut_matin'] : $oRecord->jeudi_debut_matin ;
		$oRecord->jeudi_fin_matin = isset($toInfos['jeudi_fin_matin']) ? $toInfos['jeudi_fin_matin'] : $oRecord->jeudi_fin_matin ;
		$oRecord->jeudi_debut_apres_midi = isset($toInfos['jeudi_debut_apres_midi']) ? $toInfos['jeudi_debut_apres_midi'] : $oRecord->jeudi_debut_apres_midi ;
		$oRecord->jeudi_fin_soir = isset($toInfos['jeudi_fin_soir']) ? $toInfos['jeudi_fin_soir'] : $oRecord->jeudi_fin_soir ;
		$oRecord->vendredi_debut_matin = isset($toInfos['vendredi_debut_matin']) ? $toInfos['vendredi_debut_matin'] : $oRecord->vendredi_debut_matin ;
		$oRecord->vendredi_fin_matin = isset($toInfos['vendredi_fin_matin']) ? $toInfos['vendredi_fin_matin'] : $oRecord->vendredi_fin_matin ;
		$oRecord->vendredi_debut_apres_midi = isset($toInfos['vendredi_debut_apres_midi']) ? $toInfos['vendredi_debut_apres_midi'] : $oRecord->vendredi_debut_apres_midi ;
		$oRecord->vendredi_fin_soir = isset($toInfos['vendredi_fin_soir']) ? $toInfos['vendredi_fin_soir'] : $oRecord->vendredi_fin_soir ;

		if($iId <= 0){
			$oDaoFact->insert($oRecord) ;
		} 
		if($iId > 0){
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
		$oDaoFact 		    = jDao::get('commun~utilisateursindisponibilite') ;
        $oDaoFact->delete($_iId) ;
	}

	static function generateDisponibilite ($oProf, $toDateListe, $toUI, $zTable='ztempplage3', $iDure = 20, $iDureeTypeId = 2){
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('evenement~evenementDispoSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
		jClasses::inc('utilisateurs~utilisateursIndisponibiliteSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');
		
		$tTimeListeLun = toolDate::gettTimeListTempPlage($toUI["toListes"][0]->lundi_debut_matin, $toUI["toListes"][0]->lundi_fin_matin, $toUI["toListes"][0]->lundi_debut_apres_midi, $toUI["toListes"][0]->lundi_fin_soir, $zTable);

		$tTimeListeMar = toolDate::gettTimeListTempPlage($toUI["toListes"][0]->mardi_debut_matin, $toUI["toListes"][0]->mardi_fin_matin, $toUI["toListes"][0]->mardi_debut_apres_midi, $toUI["toListes"][0]->mardi_fin_soir, $zTable);
		
		$tTimeListeMer = toolDate::gettTimeListTempPlage($toUI["toListes"][0]->mercredi_debut_matin, $toUI["toListes"][0]->mercredi_fin_matin, $toUI["toListes"][0]->mercredi_debut_apres_midi, $toUI["toListes"][0]->mercredi_fin_soir, $zTable);

		$tTimeListeJeu = toolDate::gettTimeListTempPlage($toUI["toListes"][0]->jeudi_debut_matin, $toUI["toListes"][0]->jeudi_fin_matin, $toUI["toListes"][0]->jeudi_debut_apres_midi, $toUI["toListes"][0]->jeudi_fin_soir, $zTable);

		$tTimeListeVen = toolDate::gettTimeListTempPlage($toUI["toListes"][0]->vendredi_debut_matin, $toUI["toListes"][0]->vendredi_fin_matin, $toUI["toListes"][0]->vendredi_debut_apres_midi, $toUI["toListes"][0]->vendredi_fin_soir, $zTable);
		
		$tTimeListe = array ($tTimeListeLun, $tTimeListeMar, $tTimeListeMer, $tTimeListeJeu, $tTimeListeVen) ;

		try{
			for ($i=0; $i<sizeof($toDateListe); $i++){
				for ($j=0; $j<sizeof($tTimeListe); $j++){
					if ($i == $j){
						$oDateListe = $toDateListe[$i] ;
						foreach ($tTimeListe[$j] as $oTimeListe){

							$zDayName = toolDate::getDayName ($oDateListe . " " . $oTimeListe . ":00");
							if ($zDayName != "SATURDAY" || $zDayName != "SUNDAY" ){
								$iNbre = evenementDispoSrv::getEventByDate($oDateListe . " " . $oTimeListe . ":00", $oProf->utilisateur_plageHoraireId, $oProf->utilisateur_id); 
								if ($iNbre == 0){
									if ($oProf->utilisateur_id == AUTOPLANNIFICATION_ID_CATRIONA){
										$iTypeEvent = ID_TYPE_EVENEMENT_DISPONIBLE ;
									}else{
										$iTypeEvent = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ;
									}
									evenementDispoSrv::insertEventDispo($iTypeEvent, $oProf->utilisateur_id, $oDateListe . " " . $oTimeListe . ":00", $iDure, $iDureeTypeId) ;
								} 
							}					
						}
					}
				}
			}
		}
		catch(Exception $e){
			die($e->getMessage()) ;
		}
	}
}