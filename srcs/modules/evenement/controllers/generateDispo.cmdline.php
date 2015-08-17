<?php
/**
* @package concours
* @subpackage projet
* @version  1
* @author NEOV
*/

/**
* Controleur pour les taches CRON
* @package concours
* @subpackage projet
*/

class generateDispoCtrl extends jControllerCmdLine {

    /**
    * Exportation donnée evenements 
	*/
	function generateDispo() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		//$oRep = $this->getResponse('cmdline');
		$oRep = $this->getResponse('cmdline');
		// Charger la liste des PROF

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('evenement~evenementDispoSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');

		$toParams['utilisateur_statut'] = 1 ;
        $toProf = utilisateursSrv::listCriteria($toParams) ;
		// Date de debut
		$toParams[0] = new stdClass ();

		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?5:$num_day)+1,$year);
		$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $premier_jour))); 

		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$dernier_jour = mktime(0,0,0, $month,5-(!$num_day?5:$num_day)+$day,$year);
		$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $dernier_jour)));	
		$toParams[0]->iTypeEvenement = 0 ;
		$toParams[0]->iCheckboxeAutoplanification = 0 ;
		// Date de fin 

		foreach ($toProf["toListes"] as $oProf){
			switch ($oProf->utilisateur_plageHoraireId){
				case 2: // 30 Minutes
					$tTimeListe = array('07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30' , '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30'); 
					$iDure = 30 ;
					$iDureeTypeId = 2 ;
				break;
				case 3: // 20 Minutes
					$tTimeListe = array('07:00', '07:20', '07:40', 
										 '08:00', '08:20', '08:40', 
										 '09:00', '09:20', '09:40', 
										 '10:00', '10:20', '10:40', 
										 '11:00', '11:20', '11:40', 
										 '12:00', '12:20', '12:40', 
										 '13:00', '13:20', '13:40', 
										 '14:00', '14:20', '14:40', 
										 '15:00', '15:20', '15:40', 
										 '16:00', '16:20', '16:40', 
										 '17:00', '17:20', '17:40', 
										 '18:00', '18:20', '18:40', 
										 '19:00', '19:20', '19:40', 
										 '20:00', '20:20', '20:40', 
										 '21:00', '21:20', '21:40', 
										 '22:00', '22:20', '22:40',
										 '23:00', '23:20', '23:40'
										); 
					$iDure = 20 ;
					$iDureeTypeId = 2 ;
				break;
				default:
					$tTimeListe = array('07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'); 
					$iDure = 1 ;
					$iDureeTypeId = 1 ;
			}
			$toDateListe = toolDate::getDatesBetween(toolDate::toDateSQL($toParams[0]->zDateDebut), toolDate::toDateSQL($toParams[0]->zDateFin)) ;
			//print_r($toParams);
			foreach($toDateListe as $oDateListe){
				foreach($tTimeListe as $oTimeListe){
					$zDayName = toolDate::getDayName ($oDateListe . " " . $oTimeListe . ":00");
					if ($zDayName != "SATURDAY" || $zDayName != "SUNDAY" ){
						$iNbre = evenementDispoSrv::getEventByDate($oDateListe . " " . $oTimeListe . ":00", $oProf->utilisateur_plageHoraireId, $oProf->utilisateur_id); 
						//echo "<br>".$oDateListe . " " . $oTimeListe . ":00" . " => " . $iNbre;
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
		return $oRep;
	}
	
	/**
	* cmdline.php?module=evenement&action=generateDispo:generateDisponibilite
	*
	*/
	function generateDisponibilite (){
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('cmdline');

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('evenement~evenementDispoSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
		jClasses::inc('utilisateurs~utilisateursdisponibiliteSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');

		$toParams['utilisateur_statut'] = 1 ;
        $toProf = utilisateursSrv::listCriteria($toParams) ;
		$toParams[0] = new stdClass ();
		$date = date('d-m-Y');	

		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?5:$num_day)+1,$year);
		$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $premier_jour))); 

		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$dernier_jour = mktime(0,0,0, $month,5-(!$num_day?5:$num_day)+$day,$year);
		$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $dernier_jour)));	
		$toParams[0]->iTypeEvenement = 0 ;
		$toParams[0]->iCheckboxeAutoplanification = 0 ;

		foreach ($toProf["toListes"] as $oProf){
			$toDateListe = toolDate::getDatesBetween(toolDate::toDateSQL($toParams[0]->zDateDebut), toolDate::toDateSQL($toParams[0]->zDateFin)) ;
			jLog::dump($oProf->utilisateur_id) ;
			switch ($oProf->utilisateur_plageHoraireId){
				case 2: // 30 Minutes
					//$iDure = 30 ;
					//$iDureeTypeId = 2 ;
					utilisateursdisponibiliteSrv::generateDispoIndispo ($oProf, $toDateListe, 'ztempplage2', 30, 2);
				break;
				case 3: // 20 Minutes
					//$iDure = 20 ;
					//$iDureeTypeId = 2 ;
					utilisateursdisponibiliteSrv::generateDispoIndispo($oProf, $toDateListe, 'ztempplage3', 20, 2);
				break;
				default:
					//$iDure = 1 ;
					//$iDureeTypeId = 1 ;
					utilisateursdisponibiliteSrv::generateDispoIndispo ($oProf, $toDateListe, 'ztempplage1', 1, 1);
			}
		}		
		return $oRep;	
	}
}
?>