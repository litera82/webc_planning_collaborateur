<?php
/**
* @package   jelix_calendar
* @subpackage evenement
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class evenementCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_EVENEMENT) ;
		$oCritere = new stdClass ();

		$oCritere->libelle = $this->param('libelle', '', true);
		$oCritere->statut = $this->param('statut', 3, true);
		$oCritere->zDateDebut = $this->param('zDateDebut', '', true);
		$oCritere->zDateFin = $this->param('zDateFin', '', true);

        $oResp->body->assignZone('zContent', 'evenement~BoEvenementListe', array('oCritere'=>$oCritere)) ;
	
		return $oResp ;
    }
	function edit() {
		$toParams = $this->params() ;
		$oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_EVENEMENT) ;
		$oResp->body->assignZone('zContent', 'evenement~BoEvenementEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ;
        jClasses::inc('evenement~evenementSrv');
        $oevenement = evenementSrv::save($toParams) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'evenement~evenement:index' ;
        return $oResp ;
    }
	function delete() {
        jClasses::inc('evenement~evenementSrv');
        evenementSrv::delete($this->param('ievenementId', 0, true)) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'evenement~evenement:index' ;
        return $oResp ;
    }

	function testImportCsv (){
		$row = 1;
		$toArray = array () ;
		$handle = fopen("userFiles/xml/vcalendar/test.csv", "r");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			array_push ($toArray, $data) ;
		}
		fclose($handle);		
		print_r($toArray);die () ;
	}

	function disponibilite() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_UTILISATEURS, BoHtmlResponse::MENU_DISPONIBILITE) ;
		$oCritere = new stdClass ();

		$oCritere->res = $this->param('res', '', true);

        $oResp->body->assignZone('zContent', 'evenement~BoEvenementDisponibilite', array('oCritere'=>$oCritere)) ;
	
		return $oResp ;
    }
/*
	function generateDisponibilite (){
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('redirect');
		// Charger la liste des PROF

		$toParams1 = $this->params() ;

        jClasses::inc('evenement~evenementSrv');

		$oResp = $this->getResponse('redirect') ;

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('evenement~evenementDispoSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
		jClasses::inc('utilisateurs~utilisateursIndisponibiliteSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');
    	$toParams['utilisateur_id'] = $this->param('utilisateur_id', 0, true) ;
    	$toParams['zDateDebut'] = $this->param('zDateDebut', '', true) ;
		$toParams['utilisateur_statut'] = 1 ;
        $toProf = utilisateursSrv::listCriteria($toParams) ;
		
		$toParams[0] = new stdClass ();

		if ($toParams["zDateDebut"] != ''){
			$date = str_replace("/", "-", $toParams["zDateDebut"]) ; 
		}else{
			$date = date('d-m-Y');	
		}

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
			if ($oProf->utilisateur_id > 0){
				$_toParams['utilisateur_id'] = $oProf->utilisateur_id ;
				$toUI = utilisateursIndisponibiliteSrv::listCriteria($_toParams) ;
			}
			$toDateListe = toolDate::getDatesBetween(toolDate::toDateSQL($toParams[0]->zDateDebut), toolDate::toDateSQL($toParams[0]->zDateFin)) ;

			switch ($oProf->utilisateur_plageHoraireId){
				case 2: // 30 Minutes
					//$iDure = 30 ;
					//$iDureeTypeId = 2 ;
					utilisateursIndisponibiliteSrv::generateDisponibilite ($oProf, $toDateListe, $toUI, 'ztempplage2', 30, 2);
				break;
				case 3: // 20 Minutes
					//$iDure = 20 ;
					//$iDureeTypeId = 2 ;
					utilisateursIndisponibiliteSrv::generateDisponibilite ($oProf, $toDateListe, $toUI, 'ztempplage3', 20, 2);
				break;
				default:
					//$iDure = 1 ;
					//$iDureeTypeId = 1 ;
					utilisateursIndisponibiliteSrv::generateDisponibilite ($oProf, $toDateListe, $toUI, 'ztempplage1', 1, 1);
			}
		}		
		
		$oRep->action = 'evenement~evenement:disponibilite' ;
		$oRep->params = array ('res'=>1);	
		return $oRep;	
	}
*/
/*	function generateDisponibilite1 (){
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('redirect');
		// Charger la liste des PROF

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('evenement~evenementDispoSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');
    	$toParams['utilisateur_id'] = $this->param('utilisateur_id', 0, true) ;
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
			foreach($toDateListe as $oDateListe){
				foreach($tTimeListe as $oTimeListe){
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
        $oRep->action = 'evenement~evenement:disponibilite' ;
		$oRep->params = array ('res'=>1);	

		return $oRep;
	}*/

	function generateDisponibilite (){
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('redirect');
		// Charger la liste des PROF

		$toParams1 = $this->params() ;

        jClasses::inc('evenement~evenementSrv');

		$oResp = $this->getResponse('redirect') ;

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('evenement~evenementDispoSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
		jClasses::inc('utilisateurs~utilisateursdisponibiliteSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');
    	$toParams['utilisateur_id'] = $this->param('utilisateur_id', 0, true) ;
    	$toParams['zDateDebut'] = $this->param('zDateDebut', '', true) ;
		$toParams['utilisateur_statut'] = 1 ;
        $toProf = utilisateursSrv::listCriteria($toParams) ;
		$toParams[0] = new stdClass ();

		if ($toParams["zDateDebut"] != ''){
			$date = str_replace("/", "-", $toParams["zDateDebut"]) ; 
		}else{
			$date = date('d-m-Y');	
		}

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
		
		$oRep->action = 'evenement~evenement:disponibilite' ;
		$oRep->params = array ('res'=>1);	
		return $oRep;	
	}

	function  planingprofparemail() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_UTILISATEURS, BoHtmlResponse::MENU_PLANINGPROFPAREMAIL) ;

		$res = $this->param('res', 0, true);

        $oResp->body->assignZone('zContent', 'evenement~BoEvenementPlaningProfParEmail', array('res'=>$res)) ;
	
		return $oResp ;
    }

	function generatePlaningProfParEmail (){
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$toParams1 = $this->params() ;

        jClasses::inc('evenement~evenementSrv');

		$oResp = $this->getResponse('redirect') ;

		/******************************/
		if ($toParams1["utilisateur_id"] > 0){
			// Charger la liste des PROF
			jClasses::inc('evenement~evenementSrv');
			jClasses::inc('utilisateurs~utilisateursSrv') ;
			jClasses::inc('commun~toolDate');
			jClasses::inc('commun~mailSrv');

			$toParams['utilisateur_statut'] = 1 ;
			$oProf = utilisateursSrv::getById($toParams1["utilisateur_id"]) ;
			$toProf = array($oProf) ;
			// Date de debut
			$toParams[0] = new stdClass ();

			if ($toParams1["zDateDebut"] != '' && $toParams1["zDateFin"] != '' ){
				$toParams[0]->zDateDebut = $toParams1["zDateDebut"] ; 
				$toParams[0]->zDateFin = $toParams1["zDateFin"] ;
			}else{
				$date = date('d-m-Y');	
				switch ($oProf->utilisateur_frequenceSendExcel){
					case 1: // quotidien
						$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
						$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "3 DAY"));	
					break;
					case 2: // Hebdomadaire
						$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
						$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "1 WEEK"));	
					break;
					case 3: // Tous les 2 semaine
						$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
						$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "2 WEEK"));	
					break;
					case 4: // Tous les mois
						$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
						$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "4 WEEK"));	
					break;
					default: // quotidien 1 
						$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
						$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "3 DAY"));	
				}
			}

			$toParams[0]->iTypeEvenement = 0 ;
			$toParams[0]->iCheckboxeAutoplanification = 0 ;
			
			foreach ($toProf as $oProf){
				$toParams[0]->iUtilisateur = $oProf->utilisateur_id; 
				$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');
				if (sizeof($toEvenement) > 0){
					foreach ($toEvenement['toListes'] as $oEvenement){
						$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
						$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
						$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
						$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
						$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
					}
					$zExportsFullPath = JELIX_APP_WWW_PATH . "userFiles/xls/eventToSendByMail/" . "exportEvenement_".$oProf->utilisateur_zPrenom . $oProf->utilisateur_zNom . "_". date ("Ymd_His") . ".xls" ;
					
					if (isset ($oProf->utilisateur_zMail) && $oProf->utilisateur_zMail != "" && !is_null($oProf->utilisateur_zMail) && isset($toEvenement['toListes']) && sizeof($toEvenement['toListes']) > 0){
						evenementSrv::exportEventListing($zExportsFullPath, $toEvenement, $toParams, array(), $oProf);
						if (is_file ($zExportsFullPath) && file_exists($zExportsFullPath)) {
							if (is_file ($zExportsFullPath) ) {
								@chmod ($zExportsFullPath, 0777) ;			
							}

							$tplMail = new jTpl();
							
							$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
							$tplMail->assign ('oProf', $oProf) ;
							$tplMail->assign ('zExportsFullPath', basename($zExportsFullPath)) ;

							$tpl = $tplMail->fetch ('evenement~corpsMailSendExportEventByEmail') ;
							$tzPathAttachements = array() ;
							array_push ($tzPathAttachements, $zExportsFullPath) ;
							mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oProf->utilisateur_zMail, $oProf->utilisateur_zPrenom .' '.$oProf->utilisateur_zNom , MAIL_OBJECT_SEND_EXPORT_EVENT_BY_EMAIL, $tpl,  NULL, NULL, true, NULL, $tzPathAttachements, $oProf->utilisateur_zSendExcelCopie, NULL) ;					
							$oResp->params = array ('res'=> 1001);	// OK						
						}else{
							$oResp->params = array ('res'=> 1002);	// NOT OK						
						}
					}else{
						$oResp->params = array ('res'=> 1003);	// NO DATA 						
					}			
				}else{
					$oResp->params = array ('res'=> 1003);	// NO DATA 						
				}
			}
		}
		/******************************/
		
		$oResp->action = 'evenement~evenement:planingprofparemail' ;
		return $oResp ;
	}
}