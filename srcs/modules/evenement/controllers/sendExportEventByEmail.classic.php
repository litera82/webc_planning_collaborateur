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

class sendExportEventByEmailCtrl extends jControllerCmdLine {
    /**
    * Exportation donnée evenements 
	*/	
	function sendExportEventByEmail ($toParams){
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');

	    $toProf = utilisateursSrv::listCriteria($toParams) ;
		// Date de debut
		$toParams[0] = new stdClass ();

		$date = date('d-m-Y');	
		switch ($toParams['utilisateur_frequenceSendExcel']){
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
		$toParams[0]->iTypeEvenement = 0 ;
		$toParams[0]->iCheckboxeAutoplanification = 0 ;

		// Date de fin 
		foreach ($toProf["toListes"] as $oProf){
			$toParams[0]->iUtilisateur = $oProf->utilisateur_id; 
			$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');
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
				}else{
					die('Erreur lors de la création du fichier xls');
				}
			}
		}
		return true ;
	}

    /**
    * Exportation donnée evenements Quotidien 
	*/
	function sendExportEventByEmailQuotidien() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('redirect');

		$toParams['utilisateur_statut'] = 1 ;
		$toParams['utilisateur_bSendExcel'] = 1 ;
		$toParams['utilisateur_frequenceSendExcel'] = 1 ;
        self::sendExportEventByEmail($toParams) ;
		$oRep->action = 'evenement~evenement:planingprofparemail' ;
		$oRep->params = array ('res'=> 1001);	// OK
		return $oRep;
	}

    /**
    * Exportation donnée evenements 1 semaine 
	*/
	function sendExportEventByEmailHebdomadaire() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('redirect');

		$toParams['utilisateur_statut'] = 1 ;
		$toParams['utilisateur_bSendExcel'] = 1 ;
		$toParams['utilisateur_frequenceSendExcel'] = 2 ;
        self::sendExportEventByEmail($toParams) ;
		$oRep->action = 'evenement~evenement:planingprofparemail' ;
		$oRep->params = array ('res'=> 1001);	// OK

		return $oRep;
	}
    /**
    * Exportation donnée evenements 2 semaines
	*/
	function sendExportEventByEmailTwoWeek() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('redirect');

		$toParams['utilisateur_statut'] = 1 ;
		$toParams['utilisateur_bSendExcel'] = 1 ;
		$toParams['utilisateur_frequenceSendExcel'] = 3 ;
        self::sendExportEventByEmail($toParams) ;
		$oRep->action = 'evenement~evenement:planingprofparemail' ;
		$oRep->params = array ('res'=> 1001);	// OK

		return $oRep;
	}
    /**
    * Exportation donnée evenements 4 semaines
	*/
	function sendExportEventByEmailMounth() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('redirect');

		$toParams['utilisateur_statut'] = 1 ;
		$toParams['utilisateur_bSendExcel'] = 1 ;
		$toParams['utilisateur_frequenceSendExcel'] = 4 ;
        self::sendExportEventByEmail($toParams) ;
		$oRep->action = 'evenement~evenement:planingprofparemail' ;
		$oRep->params = array ('res'=> 1001);	// OK
		return $oRep;
	}

}
?>