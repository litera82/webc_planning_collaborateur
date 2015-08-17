<?php
/**
* @package   jelix_calendar
* @subpackage evenement
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class FoEvenementCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function add() {
		global $gJConfig ;

        $oRep = $this->getResponse('FoHtml');
		
		$oRep->bodyTpl = "evenement~FoEditEvenement" ;

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/addEvenement.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$zDate = $this->param('zDate', '', true);
		$iTime = $this->param('iTime', 0, true);
		$iEvenementId = $this->param('iEvenementId', 0, true);
		$iAffichage = $this->param('iAffichage', 1, true);

		$prec = $this->param('prec', 0, true);
		$debut = $this->param('debut', "", true);
		$fin = $this->param('fin', "", true);

		$x = $this->param('x', 0, true);
		$tEvent = array ();
		if (isset ($_SESSION['tEvent'])){
			$tEvent = $_SESSION['tEvent']; 
			unset($_SESSION['tEvent']);
		}		

		$oRep->body->assignZone('oZoneLegend', 'commun~FoLegende', array());
		$oRep->body->assignZone('oZoneEditEvenement', 'evenement~FoEditEvenement', array('iEvenementId'=> $iEvenementId, 'zDate'=>$zDate, 'iTime'=>$iTime, 'iAffichage'=>$iAffichage, 'tEvent'=>$tEvent, 'x'=>$x, 'prec'=>$prec, 'debut'=>$debut, 'fin'=>$fin));
		return $oRep;
    }

	function testEventExist(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$zDate = $this->param('zDate', "", true); 
		$iTime = $this->param('iTime', "", true);
		
    	jClasses::inc('evenement~evenementSrv');
		$iNbreEvent = evenementSrv::testEventExist($zDate, $iTime);

		$oRep->datas = $iNbreEvent;

		return $oRep;
	}
	function getListeTypeEvenementUilisateur(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$user = $this->param('user', AUDIT_ID_CATRIONA, true);
		
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');

		$toResults = utilisateursSrv::getListeTypeEvenementUilisateur($user);
		if (sizeof($toResults) == 0){
			$toResult = typeEvenementsSrv::listCriteria();	
			$toResults = $toResult['toListes'] ;
		}
		$oRep->datas = $toResults;

		return $oRep;
	}	
	function testEventExistEditionIsTypeEventDisponible(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$zDateTime = $this->param('zDateTime', "", true); 
		$iEvenementId = $this->param('iEvenementId', 0, true);
		
    	jClasses::inc('evenement~evenementSrv');
		$iNbreEvent = evenementSrv::testEventExistEditionIsTypeEventDisponible($zDateTime, $iEvenementId);

		$oRep->datas = $iNbreEvent;

		return $oRep;
	}	
	function desactiverEventDispo(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$zDateTime = $this->param('zDateTime', "", true); 
		$iEvenementId = $this->param('iEvenementId', 0, true);
		
    	jClasses::inc('evenement~evenementSrv');
		$oEvent = evenementSrv::desactiverEventDispo($zDateTime, $iEvenementId);

		$oRep->datas = $oEvent;

		return $oRep;
	}	
	function testEventExistEdition(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$zDateTime = $this->param('zDateTime', "", true); 
		$iEvenementId = $this->param('iEvenementId', 0, true);
		
    	jClasses::inc('evenement~evenementSrv');
		$iNbreEvent = evenementSrv::testEventExistEdition($zDateTime, $iEvenementId);

		$oRep->datas = $iNbreEvent;

		return $oRep;
	}	

	function saveEventRapid (){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$oUser = jAuth::getUserSession();
		$zDate = $this->param('zDate', 0, true); 
		$iTime = $this->param('iTime', 0, true); 
		$tDate = explode ("/", $zDate) ;
		$zDescription = $this->param('zDescription', "", true) ;

	    jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

		$oEvent = new StdClass () ;
		$oEvent->evenement_iTypeEvenementId = $this->param('iTypeEvenementId', 0, true); 
		$oTypeEvenements = typeEvenementsSrv::getById ($oEvent->evenement_iTypeEvenementId) ;
		$oEvent->evenement_zLibelle = $oTypeEvenements->typeevenements_zLibelle ;
		if ($zDescription != ""){
			$oEvent->evenement_zDescription = $zDescription ;
		}else{
			$oEvent->evenement_zDescription = $oTypeEvenements->typeevenements_zLibelle ;
		}
		$oEvent->evenement_iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oEvent->evenement_iStagiaire = $this->param('iStagiaire', 0, true); 
		$oEvent->evenement_zDateHeureDebut = $tDate[2] . '-' . $tDate[1] . '-' . $tDate[0] . ' ' . $iTime . ':00' ;
		$oEvent->evenement_zDateHeureSaisie = date("Y-m-d H:i:s") ;
		$oEvent->evenement_iStatut = 1 ;
    	jClasses::inc('evenement~evenementSrv');
		$iRes = evenementSrv::saveEventRapid($oEvent);
		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iRes)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;

		return $oRep;
	}
	function collerEvent (){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$oUser = jAuth::getUserSession();
		$iEventToCopy = $_SESSION['EVENT_TO_COPY']; 
		$zDate = $this->param('zDate', date("Y-m-d"), true); 
		$zTime = $this->param('zTime', date("H:i:s"), true); 
		
    	jClasses::inc('evenement~evenementSrv');

		$iRes = 0 ;
		if ($iEventToCopy > 0){
			$oEvent = evenementSrv::getById($iEventToCopy);
			$oNewEvent = new StdClass ();

			$oNewEvent->evenement_iTypeEvenementId = $oEvent->evenement_iTypeEvenementId;
			$oNewEvent->evenement_iUtilisateurId = $oEvent->evenement_iUtilisateurId;
			$oNewEvent->evenement_zLibelle = $oEvent->evenement_zLibelle;
			$oNewEvent->evenement_zDescription = $oEvent->evenement_zDescription;
			$oNewEvent->evenement_iStagiaire = $oEvent->evenement_iStagiaire;
			$oNewEvent->evenement_zContactTel = $oEvent->evenement_zContactTel;
			$oNewEvent->evenement_zDateHeureDebut = $zDate ." ". $zTime ; 
			$oNewEvent->evenement_zDateHeureSaisie = date("Y-m-d H:i:s");
			$oNewEvent->evenement_iDuree = $oEvent->evenement_iDuree;
			$oNewEvent->evenement_iDureeTypeId = $oEvent->evenement_iDureeTypeId;
			$oNewEvent->evenement_iPriorite = $oEvent->evenement_iPriorite;
			$oNewEvent->evenement_iRappel = $oEvent->evenement_iRappel;
			$oNewEvent->evenement_iTypeRappelId = $oEvent->evenement_iTypeRappelId;
			$oNewEvent->evenement_iStatut = 1;
			$oNewEvent->evenement_origine = $oEvent->evenement_origine;
			$iRes = evenementSrv::collerEvent($oNewEvent); 
			if (isset($_SESSION['EVENT_TO_COPY_TYPE']) && $_SESSION['EVENT_TO_COPY_TYPE'] == 2){
				if (isset($_SESSION['EVENT_TO_COPY']) && $_SESSION['EVENT_TO_COPY'] > 0){
					$iEventToCopy = $_SESSION['EVENT_TO_COPY'];
					evenementSrv::delete($iEventToCopy) ;
					unset($_SESSION['EVENT_TO_COPY']) ;
					unset($_SESSION['EVENT_TO_COPY_TYPE']) ;
				}
			}
		}

		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iRes)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;

		return $oRep;
	}
	function deleteEventRapid(){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$iEventId = $this->param('iEventId', 0, true); 
		if ($iEventId > 0){
			jClasses::inc('evenement~evenementSrv');
			evenementSrv::delete($iEventId);		
		}
		$oRep->content = ' ' ;

		return $oRep;
	}
	function copierEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$iEventId = $this->param('iEventId', 0, true); 
		$zDate = $this->param('date', '', true); 

		if (isset ($_SESSION['EVENT_TO_COPY'])){
			unset($_SESSION['EVENT_TO_COPY']) ;	
		}
		$_SESSION['EVENT_TO_COPY'] = $iEventId;
		$_SESSION['EVENT_TO_COPY_TYPE'] = 1;

		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iEventId)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;
		return $oRep;
	}
	function saveDescEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$iEventId = $this->param('iEventId', 0, true); 
		$zDesc = $this->param('desc', '', true); 
        jClasses::inc('evenement~evenementSrv');
		evenementSrv::saveDescEvent($iEventId, $zDesc) ;
		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iEventId)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;
		return $oRep;
	}
	function checkEvetToCopyExist (){
		$oRep = $this->getResponse('encodedJson');
		$iRes = 0 ;
		if (isset ($_SESSION['EVENT_TO_COPY_TYPE'])){
			if ($_SESSION['EVENT_TO_COPY_TYPE'] > 0){
				$iRes = 1 ;	
			}
		}
		$oRep->datas = $iRes;
		return $oRep;
	}
	function couperEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$iEventId = $this->param('iEventId', 0, true); 

		if (isset ($_SESSION['EVENT_TO_COPY_TYPE'])){
			unset($_SESSION['EVENT_TO_COPY_TYPE']) ;	
		}
		
		if (isset ($_SESSION['EVENT_TO_COPY'])){
			unset($_SESSION['EVENT_TO_COPY']) ;	
		}
		$_SESSION['EVENT_TO_COPY'] = $iEventId;
		$_SESSION['EVENT_TO_COPY_TYPE'] = 2;

		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iEventId)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;
		
		return $oRep;
	}

	function save() {
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
        jClasses::inc('evenement~evenementSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		
		$prec = $this->param('prec', 0, true);
		$debut = $this->param('debut', "", true);
		$fin = $this->param('fin', "", true);

		$toParams = $this->params() ;

		$toParams['evenement_zDateHeureDebut'] = $toParams['dtcm_event_rdv'];

		$toParams['x'] = $toParams['x'];
		$toParams['evenement_iUtilisateurId'] = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		if ($toParams['evenement_iRappel'] == 1){
			if (isset($toParams['evenement_iRappelJour']) && $toParams['evenement_iRappelJour'] > 0){
				$toParams['evenement_iTypeRappelId'] = 1; 
			}elseif (isset($toParams['evenement_iRappelHeure']) && $toParams['evenement_iRappelHeure'] > 0){
				$toParams['evenement_iTypeRappelId'] = 2; 
			}else{
				$toParams['evenement_iTypeRappelId'] = 3; 
			}
		}else{
			$toParams['evenement_iTypeRappelId'] = NULL; 
		}
		if ($toParams['evenement_iDuree'] && ($toParams['evenement_iDuree'] != '' || !is_null($toParams['evenement_iDuree']))){
			$tzEvenement_iDuree = explode(' ', $toParams['evenement_iDuree']);
			$toParams['evenement_iDuree']		= $tzEvenement_iDuree [0]; 
			if ($tzEvenement_iDuree[1] == 'minutes'){
				$toParams['evenement_iDureeTypeId'] = 2; 
			}else{
				$toParams['evenement_iDureeTypeId'] = 1; 
			}
		}else{
			$toParams['evenement_iDuree'] = 0; 
			$toParams['evenement_iDureeTypeId'] = 1; 
		}

		$oNewEvenement = evenementSrv::save($toParams) ;

		if (isset ($toParams['evenement_iDupliquer']) && $toParams['evenement_iDupliquer'] == 1){
			$tDateFinal = array ();
			if (isset($toParams['choixperiode'])){
				if ($toParams['choixperiode'] == 1){//Quotidienne
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
					$tzHeureDureeRendezVous = explode(' ', $toParams['evenement_heureDureeRendezVous']);
					$toParams['evenement_iDuree']		= $tzHeureDureeRendezVous[0]; 
					if ($tzHeureDureeRendezVous[1] == 'minutes'){
						$toParams['evenement_iDureeTypeId'] = 2; 
					}else{
						$toParams['evenement_iDureeTypeId'] = 1; 
					}

					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){//par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateNombreOccurence($toParams['evenement_periodiciteQuotidienne'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut']);
						if (sizeof ($tDateFinal) > 0){
							evenementSrv::saveMultipleQuotidienneParOccurence ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin
						if (isset ($toParams['dtcm_event_rdv_periodiciteFin']) && ($toParams['dtcm_event_rdv_periodiciteFin'] != '' || !is_null($toParams['dtcm_event_rdv_periodiciteFin']))){
							$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']);
							$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']);
							$tDates = toolDate::getDatesBetween($zDateDebut, $zDateFin);
							$tDateFinal = toolDate::periodiciteQuotidienneGetDateParDateDeFin($toParams['evenement_periodiciteQuotidienne'], $tDates, $zDateDebut); 
							if (sizeof ($tDateFinal) > 0){
								evenementSrv::saveMultipleQuotidienneParDateDefin ($tDateFinal, $toParams, $oNewEvenement) ;
							}
						}
					}
				}elseif ($toParams['choixperiode'] == 2){//Hebdomadaire
					$toParams['evenement_iLundi'] = isset($toParams['evenement_iLundi']) ? 1 : 0;
					$toParams['evenement_iMardi'] = isset($toParams['evenement_iMardi']) ? 1 : 0;
					$toParams['evenement_iMercredi'] = isset($toParams['evenement_iMercredi']) ? 1 : 0;
					$toParams['evenement_iJeudi'] = isset($toParams['evenement_iJeudi']) ? 1 : 0;
					$toParams['evenement_iVendredi'] = isset($toParams['evenement_iVendredi']) ? 1 : 0;
					$toParams['evenement_iSamedi'] = isset($toParams['evenement_iSamedi']) ? 1 : 0;
					$toParams['evenement_iDimanche'] = isset($toParams['evenement_iDimanche']) ? 1 : 0;
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';


					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParOccurence($toParams['evenement_periodiciteHebdomadaire'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut'], $toParams);
						if (sizeof ($tDateFinal) > 0){
							evenementSrv::saveMultipleHebdomadaireParOccurence ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin 
						$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$tDates = toolDate::getDatesBetween (toolDate::getDateFormatYYYYMMDD($zDateDebut), toolDate::getDateFormatYYYYMMDD($zDateFin));

						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParDateDeFin($toParams['evenement_periodiciteHebdomadaire'], $tDates, toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams);
						if (sizeof ($tDateFinal) > 0){
							evenementSrv::saveMultipleHebdomadaireParDateDeFin ($tDateFinal, $toParams, $oNewEvenement) ;
						}						
					}

				}else{//Mensuelle
					if (isset($toParams['evenement_periodiciteMensuel1'])){
						$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';

						if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									evenementSrv::saveMultipleMensuelleParOccurence ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									evenementSrv::saveMultipleMensuelleParOccurence ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}else{//Par date de fin 
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									evenementSrv::saveMultipleMensuelleParDateDeFin ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									evenementSrv::saveMultipleMensuelleParDateDeFin ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}
					}
				}
			}
		}

		if (isset($toParams['prec']) && $toParams['prec'] == 1){
			$oResp->action = 'evenement~FoEvenement:getEventListing' ;
			$oResp->params = array ('dtcm_event_rdv'=>$toParams['debut'], 'dtcm_event_rdv1'=> $toParams['fin']);	
		}elseif (isset($toParams['prec']) && $toParams['prec'] == 2){
			$oResp->action = 'evenement~FoEvenement:getEventListingDispo' ;
			$oResp->params = array ('dtcm_event_rdv'=>$toParams['debut'], 'dtcm_event_rdv1'=> $toParams['fin']);	
		}else{
			$oResp->action = 'jelix_calendar~FoCalendar:index' ;
			$oResp->params = array ('date'=>$toParams['zDate'], 'iAffichage'=> $toParams['iAffichage']);	
        }

		return $oResp ;
    }

	function saveAffectation() {
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
        jClasses::inc('evenement~evenementSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		
		$toParams = $this->params() ;

		$toParams['evenement_zDateHeureDebut'] = $toParams['dtcm_event_rdv'];
		$toParams['x'] = $toParams['x'];
		$toParams['evenement_iUtilisateurId'] = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		if ($toParams['evenement_iRappel'] == 1){
			if (isset($toParams['evenement_iRappelJour']) && $toParams['evenement_iRappelJour'] > 0){
				$toParams['evenement_iTypeRappelId'] = 1; 
			}elseif (isset($toParams['evenement_iRappelHeure']) && $toParams['evenement_iRappelHeure'] > 0){
				$toParams['evenement_iTypeRappelId'] = 2; 
			}else{
				$toParams['evenement_iTypeRappelId'] = 3; 
			}
		}else{
			$toParams['evenement_iTypeRappelId'] = NULL; 
		}
		if ($toParams['evenement_iDuree'] && ($toParams['evenement_iDuree'] != '' || !is_null($toParams['evenement_iDuree']))){
			$tzEvenement_iDuree = explode(' ', $toParams['evenement_iDuree']);
			$toParams['evenement_iDuree']		= $tzEvenement_iDuree [0]; 
			if ($tzEvenement_iDuree[1] == 'minutes'){
				$toParams['evenement_iDureeTypeId'] = 2; 
			}else{
				$toParams['evenement_iDureeTypeId'] = 1; 
			}
		}else{
			$toParams['evenement_iDuree'] = 0; 
			$toParams['evenement_iDureeTypeId'] = 1; 
		}

		$oNewEvenement = evenementSrv::save($toParams) ;

		if (isset ($toParams['evenement_iDupliquer']) && $toParams['evenement_iDupliquer'] == 1){
			$tDateFinal = array ();
			if (isset($toParams['choixperiode'])){
				if ($toParams['choixperiode'] == 1){//Quotidienne
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
					$tzHeureDureeRendezVous = explode(' ', $toParams['evenement_heureDureeRendezVous']);
					$toParams['evenement_iDuree']		= $tzHeureDureeRendezVous[0]; 
					if ($tzHeureDureeRendezVous[1] == 'minutes'){
						$toParams['evenement_iDureeTypeId'] = 2; 
					}else{
						$toParams['evenement_iDureeTypeId'] = 1; 
					}

					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){//par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateNombreOccurence($toParams['evenement_periodiciteQuotidienne'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut']);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleQuotidienneParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin
						if (isset ($toParams['dtcm_event_rdv_periodiciteFin']) && ($toParams['dtcm_event_rdv_periodiciteFin'] != '' || !is_null($toParams['dtcm_event_rdv_periodiciteFin']))){
							$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']);
							$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']);
							$tDates = toolDate::getDatesBetween($zDateDebut, $zDateFin);
							$tDateFinal = toolDate::periodiciteQuotidienneGetDateParDateDeFin($toParams['evenement_periodiciteQuotidienne'], $tDates, $zDateDebut); 
							if (sizeof ($tDateFinal) > 0){
								$tEventNonCreer = evenementSrv::saveMultipleQuotidienneParDateDefinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
							}
						}
					}
				}elseif ($toParams['choixperiode'] == 2){//Hebdomadaire
					$toParams['evenement_iLundi'] = isset($toParams['evenement_iLundi']) ? 1 : 0;
					$toParams['evenement_iMardi'] = isset($toParams['evenement_iMardi']) ? 1 : 0;
					$toParams['evenement_iMercredi'] = isset($toParams['evenement_iMercredi']) ? 1 : 0;
					$toParams['evenement_iJeudi'] = isset($toParams['evenement_iJeudi']) ? 1 : 0;
					$toParams['evenement_iVendredi'] = isset($toParams['evenement_iVendredi']) ? 1 : 0;
					$toParams['evenement_iSamedi'] = isset($toParams['evenement_iSamedi']) ? 1 : 0;
					$toParams['evenement_iDimanche'] = isset($toParams['evenement_iDimanche']) ? 1 : 0;
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';


					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParOccurence($toParams['evenement_periodiciteHebdomadaire'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut'], $toParams);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleHebdomadaireParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin 
						$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$tDates = toolDate::getDatesBetween (toolDate::getDateFormatYYYYMMDD($zDateDebut), toolDate::getDateFormatYYYYMMDD($zDateFin));

						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParDateDeFin($toParams['evenement_periodiciteHebdomadaire'], $tDates, toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleHebdomadaireParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}						
					}

				}else{//Mensuelle
					if (isset($toParams['evenement_periodiciteMensuel1'])){
						$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';

						if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}else{//Par date de fin 
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}
					}
				}
			}
		}
		$oResp->action = 'evenement~FoEvenement:getEventListingDispo' ;
		$oResp->params = array ('dtcm_event_rdv'=> $toParams['criteria_datedebut'], 'bAffectation'=>1, 'dtcm_event_rdv1'=>$toParams['criteria_datefin']);	
        return $oResp ;
    }

	function eventListing (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->bodyTpl = "evenement~FoEventListing" ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvenement					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvenement) && sizeof ($toTypeEvenement) > 0){
			$oTypeEvenement = array();
			$oTypeEvenement['iResTotal'] = sizeof ($toTypeEvenement) ;
			$oTypeEvenement['toListes']  = $toTypeEvenement ;
		}else{
			$oTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

 		//$toTypeEvenement = typeEvenementsSrv::listCriteria($oParamsTypeevent);

		//Date Année Header 
		$iAnnee = date('Y');
		$tiAnnee = array ();
		for ($i=$iAnnee-10; $i<=$iAnnee+20; $i++){
			array_push ($tiAnnee, $i);
		}

		$toParamsClient[0] = new stdClass();
		$toParamsClient[0]->statut = 1;
		$toStagiaire = clientSrv::listCriteria($toParamsClient);

		$oRep->body->assign('now', date('d/m/Y'));
		$oRep->body->assign('tiAnnee', $tiAnnee);
		$oRep->body->assign('toTypeEvenement', $oTypeEvenement['toListes']);
		$oRep->body->assign('toStagiaire', $toStagiaire['toListes']);

		return $oRep;
	}
	function getEventListing (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');
		$oRep->bodyTpl = "evenement~FoEventListingResult" ;

    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');
    	jClasses::inc('client~PostSrv');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$toParams[0] = new stdClass();
		$toParams[0]->statut = 1;
		
		/****************/
		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$zDatedebC      = toolDate::toDateFr(toolDate::toDateSQL($date)); // Date du jour
		$zDatefinC      = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), '7 DAY')) ;
		/****************/
		
		$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
		$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);
		if ($toParams[0]->zDateFin == 0){
			$toParams[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams[0]->zDateDebut);
		}

		$toParams[0]->iTypeEvenement = $this->param('evenement_iTypeEvenementId', 0, true);
		$toParams[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		$toParams[0]->iCheckDate = $this->param('iCheckDate', 0, true);
		$toParams[0]->evenement_zSociete = $this->param('evenement_zSociete', "", true);
		$toParams[0]->evenement_zStagiaire = $this->param('evenement_zStagiaire', "", true);
		$toParams[0]->evenement_stagiaire = $toParams[0]->iStagiaire;

 		$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');

		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));

			$oEvenement->oWpPost = new StdClass (); 
			if (isset($oEvenement->evenement_iStagiaire) && $oEvenement->evenement_iStagiaire > 0){
				//get wp_post
				$oEvenement->oWpPost = PostSrv::chargePosteParIdPostBlog($oEvenement->evenement_iStagiaire); 
			}
		}
		
       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement = typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  
		
		/***PRINT***/
		$toParams1[0] = new stdClass();
		$toParams1[0]->statut = 1;
		$toParams1[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
		$toParams1[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);
		if ($toParams1[0]->zDateFin == 0){
			$toParams1[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams1[0]->zDateDebut);
		}
		$toParams1[0]->iTypeEvenement = $this->param('evenement_iTypeEvenementId', 0, true);
		$toParams1[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams1[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams1[0]->iUtilisateur = $iUtilisateurId; 
		$toParams1[0]->iCheckboxeAutoplanification = 0;
		$toParams1[0]->iCheckDate = $this->param('iCheckDate', 0, true);
		$toParams1[0]->evenement_zSociete = $this->param('evenement_zSociete', "", true);

 		$toEvenementPrint = evenementSrv::listCriteria($toParams1, 'evenement_zDateHeureDebut');
		foreach ($toEvenementPrint['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}
		/***PRINT***/

		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}

		$oRep->body->assign('toTypeEvenement', $toTypeEvenement['toListes']);
		$oRep->body->assign('oUtilisateur', $oUtilisateur);
		$oRep->body->assign('toEvenement', $toEvenement['toListes']);
		$oRep->body->assign('toEvenementPrint', $toEvenementPrint['toListes']);
		$oRep->body->assign('iResTotal', $toEvenement['iResTotal']);
		$oRep->body->assign('toParams', $toParams);
		$oRep->body->assign('toTypeEvenementSelected', $toTypeEvenementSelected);

		return $oRep;
	}	

	function getEventListingApprocheListe (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-1.5.1.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-ui-1.8.10.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery.loader-min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/script.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/affecter.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/light/css/redmond/jquery-ui-1.8.10.custom.css');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/timepicker.js');

    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$toParams[0] = new stdClass();
		$toParams[0]->statut = 1;

		$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', date('d/m/Y'), true);
		$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', 0, true);
		if ($toParams[0]->zDateFin == 0){
			$toParams[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams[0]->zDateDebut);
		}

		if ($iUtilisateurId == AUDIT_ID_CATRIONA){
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_DISPONIBLE;
		}else{
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE;
		}
		$toParams[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		$toParams[0]->iCheckDate = $this->param('iCheckDate', 0, true);

 		$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');
		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}
		
       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

		$oRep->body->assign('toTypeEvenement', $toTypeEvenement['toListes']);
		$oRep->body->assign('oUtilisateur', $oUtilisateur);
		$oRep->body->assign('toEvenement', $toEvenement['toListes']);
		$oRep->body->assign('iResTotal', $toEvenement['iResTotal']);
		$oRep->body->assign('toParams', $toParams);
		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}
		$oRep->body->assign('toTypeEvenementSelected', $toTypeEvenementSelected);

		$tEventNonCreer = $this->param('tEventNonCreer', array(), true) ;
		$bAffectation = $this->param('bAffectation', 1, true) ;
		if ($bAffectation > 0){
			if (sizeof($tEventNonCreer)){
				$zEvenementId = "";
				foreach ($tEventNonCreer as $oEventNonCreer){
					if ($zEvenementId == ""){
						$zEvenementId = $oEventNonCreer->evenement_id;
					}else{
						$zEvenementId .= ",".$oEventNonCreer->evenement_id;
					}
				}
				if ($zEvenementId != ""){
					$tResult = evenementSrv::findEventByListEventId ($zEvenementId) ;
					$oRep->body->assign('tResult', $tResult);
				}
			} 
		}
		$oRep->body->assign('bAffectation', $bAffectation);
		
		$oRep->bodyTpl = "evenement~FoEventListingResultCourDisponiblePlannifie" ;
		return $oRep;
	}

	function suppressionMultipleEvent (){
		global $gJConfig ;
		$oResp = $this->getResponse('redirect') ;
    	
		jClasses::inc('evenement~evenementSrv');
		$zListeEvenementId = $this->param('zListeEvenementId', 0, true);
		$iTypeEvenement = $this->param('iTypeEvenement', 0, true);
		$zDateDebut = $this->param('zDateDebut', '', true);
		$zDateFin = $this->param('zDateFin', '', true);
		$iStagiaire = $this->param('iStagiaire', '', true);


 		evenementSrv::suppressionMultipleEvent($zListeEvenementId);
		if ($zDateDebut == ''){
			$zDateDebut = date('d').'/'.date('m').'/'.date('Y') ;
		}
		if ($zDateFin == ''){
			$zDateFin = date('d').'/'.date('m').'/'.date('Y') ;
		}
		$tzDateDebut	= explode('/', $zDateDebut);
		$tzDateFin		= explode('/', $zDateFin);

		$oResp->action = 'evenement~FoEvenement:getEventListing' ;
		$oResp->params = array ('dtcm_event_rdv'=> $zDateDebut, 
								'dtcm_event_rdv1'=>$zDateFin, 
								'evenement_stagiaire'=>$iStagiaire,
								'evenement_iTypeEvenementId'=>$iTypeEvenement
								);	
        return $oResp ;
	}

	function deleteEvent (){
		global $gJConfig ;
		$oResp = $this->getResponse('redirect') ;
    	
		jClasses::inc('evenement~evenementSrv');
        jClasses::inc('commun~toolDate');

		$iEvenementId = $this->param('iEvenementId', 0, true);
		$iAffichage = $this->param('iAffichage', 1, true);
		$date = $this->param('date', date('Y-m-d'), true);

		$iOption = $this->param('iOption', 0, true);
		$iTypeEvenement = $this->param('iTypeEvenement', 0, true);
		$iStagiaire = $this->param('iStagiaire', 0, true);
		$zDateDebut = $this->param('zDateDebut', '', true);
		$zDateFin = $this->param('zDateFin', '', true);

		if ($date != "" && $iAffichage < 3){
			$tDate = explode ('-', $date);//11-04-2011
			$zDate = $tDate[2] . '-' . $tDate[1] . '-' . $tDate[0];
		}else{
			$zDate = $date ;
		}
		if ($iEvenementId > 0){
			evenementSrv::delete($iEvenementId);		
		}

		if ($iOption == 1){

			if ($zDateDebut == ''){
				$zDateDebut = date('d').'/'.date('m').'/'.date('Y') ;
			}
			if ($zDateFin == ''){
				$zDateFin = date('d').'/'.date('m').'/'.date('Y') ;
			}
			$tzDateDebut	= explode('/', $zDateDebut);
			$tzDateFin		= explode('/', $zDateFin);

			$oResp->action = 'evenement~FoEvenement:getEventListing' ;
			$oResp->params = array ('dtcm_event_rdv'=> $zDateDebut, 
									'dtcm_event_rdv1'=>$zDateFin, 
									'evenement_stagiaire'=>$iStagiaire,
									'evenement_iTypeEvenementId'=>$iTypeEvenement
									);	
		}else{
			$oResp->action = 'jelix_calendar~FoCalendar:index' ;
			$oResp->params = array ('date'=> $zDate, 'iAffichage'=>$iAffichage);	
		}
        return $oResp ;
	}

	function chargeEvenementParId (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iEvenementId = $this->param('iEvenementId', 0, true); 
    	jClasses::inc('evenement~evenementSrv');
		$oEvenement = evenementSrv::getById($iEvenementId);

		$oRep->datas = $oEvenement;

		return $oRep;
	}

	function chargeTypeEvenementParEventId (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iEvenementId = $this->param('iEvenementId', 0, true); 
    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc('typeEvenement~typeEvenementsSrv');

		$oEvenement = evenementSrv::getById($iEvenementId);
		$oTypeEvenement = new stdClass () ;
		if ($oEvenement->evenement_iTypeEvenementId > 0){
			$oTypeEvenement = typeEvenementsSrv::getById($oEvenement->evenement_iTypeEvenementId);
		}
		$oRep->datas = $oTypeEvenement->typeevenements_zLibelle;

		return $oRep;
	}

	function calculDateDiff (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		jClasses::inc('evenement~evenementSrv');
        jClasses::inc('commun~toolDate');
		
		$zDebut = $this->param('zDebut', 0, true); 
		$zFin = $this->param('zFin', 0, true); 
		if ($zDebut == 0 || $zFin == 0){
			$oRep->datas = -1;
		}else{
			$iDiff = toolDate::date_diff (toolDate::toDateSQL($zDebut).' 00:00:00', toolDate::toDateSQL($zFin).' 00:00:00');
			$oRep->datas = $iDiff ;
		}
		return $oRep;
	}

	/*function autocompleteStagiaire(){
		$rep        = $this->getResponse('encodedJson');
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);

		$CritereNom = $this->param('q','',true);
		$tCritere = explode(' ', trim($CritereNom));
		//$zSql="SELECT * FROM clients WHERE 1=1 AND (client_iUtilisateurCreateurId = " . $iUtilisateurId . " OR client_iUtilisateurCreateurId IS NULL) AND (";
		//$zSql="SELECT * FROM clients WHERE 1=1 AND client_iUtilisateurCreateurId = " . $iUtilisateurId . " AND (";
		$zSql="SELECT * FROM clients INNER JOIN wp_postsclients ON clients.client_id = wp_postsclients.client_id
  INNER JOIN wp_posts_to_import ON wp_postsclients.post_id = wp_posts_to_import.ID WHERE 1=1 AND (";
		$t = array();
		foreach ($tCritere as $zCritere) {
			$t[] = sprintf(" client_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
			$t[] = sprintf(" client_zPrenom like '%%%s%%' ", trim(addslashes($zCritere)) );
		}
		$zSql .= implode(" OR ", $t);
		$zSql .= ") GROUP BY clients.client_id ORDER BY client_zNom ASC ";  

		$cnx        = jDb::getConnection();
		$oRes       = $cnx->query($zSql);
		$tPersonne = $oRes->fetchAll();
		/*if (sizeof($tPersonne) == 0 && $iUtilisateurId == AUDIT_ID_CATRIONA){
			$zSql="SELECT * FROM clients WHERE 1=1 AND ";
			$t = array();
			foreach ($tCritere as $zCritere) {
				$t[] = sprintf(" client_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
				$t[] = sprintf(" client_zPrenom like '%%%s%%' ", trim(addslashes($zCritere)) );
			}
			$zSql .= implode(" OR ", $t);
			$zSql .= " GROUP BY client_id ORDER BY client_zNom ASC ";  

			$cnx        = jDb::getConnection();
			$oRes       = $cnx->query($zSql);
			$tPersonne = $oRes->fetchAll();
		}
		$rep->datas  = $tPersonne;
		return $rep;
	}*/
	function autocompleteStagiaire(){
		$rep        = $this->getResponse('encodedJson');
		jClasses::inc('client~PostSrv');
		$CritereNom = $this->param('q','',true);
		$tCritere = explode(' ', trim($CritereNom));

		$oCritere = new stdClass ();
		$oCritere->zTitlePost = $tCritere;

		$tPersonne = PostSrv::listCriteriaPostBlog($oCritere);

		$rep->datas  = $tPersonne['toListes'];
		return $rep;
	}

	function autocompleteSociete(){
		$rep        = $this->getResponse('encodedJson');
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);

		$CritereNom = $this->param('q','',true);
		$tCritere = explode(' ', trim($CritereNom));
		$zSql="SELECT * FROM clients INNER JOIN societe ON client_iSociete = societe_id WHERE 1=1 ";
		if ($iUtilisateurId != ID_ADMINISTRATEUR_PP){
			$zSql .= " AND client_iUtilisateurCreateurId = " . $iUtilisateurId ;
		}
		$zSql .= " AND (";
		$t = array();
		foreach ($tCritere as $zCritere) {
			$t[] = sprintf(" societe_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
		}
		$zSql .= implode(" OR ", $t);
		$zSql .= ") GROUP BY societe_zNom ORDER BY societe_zNom ASC ";  

		$cnx        = jDb::getConnection();
		$oRes       = $cnx->query($zSql);
		$tSoc = $oRes->fetchAll();	
		$rep->datas  = $tSoc;
		return $rep;
	}

	function autocompleteStagiaireAffectation(){
		$rep        = $this->getResponse('encodedJson');
		/*jClasses::inc('evenement~evenementSrv');
		$CritereNom = $this->param('q','',true);
		$tCritere = explode(' ', trim($CritereNom));
		$zSql=sprintf("SELECT * FROM clients LEFT JOIN societe ON client_iSociete = societe_id WHERE 1=1 AND ");
		$t = array();
		foreach ($tCritere as $zCritere) {
			$t[] = sprintf(" client_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
			$t[] = sprintf(" client_zPrenom like '%%%s%%' ", trim(addslashes($zCritere)) );
		}
		$zSql .= implode(" OR ", $t);
		$zSql .= " GROUP BY client_id ORDER BY client_zNom ASC ";  
		$cnx        = jDb::getConnection();
		$oRes       = $cnx->query($zSql);
		$tPersonne = $oRes->fetchAll();*/
		
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);

		$CritereNom = $this->param('q','',true);
		$tCritere = explode(' ', trim($CritereNom));
		//$zSql="SELECT * FROM clients WHERE 1=1 AND (client_iUtilisateurCreateurId = " . $iUtilisateurId . " OR client_iUtilisateurCreateurId IS NULL) AND (";
		//$zSql="SELECT * FROM clients WHERE 1=1 AND client_iUtilisateurCreateurId = " . $iUtilisateurId . " AND (";
		$zSql="SELECT * FROM clients INNER JOIN wp_postsclients ON clients.client_id = wp_postsclients.client_id
  INNER JOIN wp_posts_to_import ON wp_postsclients.post_id = wp_posts_to_import.ID WHERE 1=1  AND (";
		$t = array();
		foreach ($tCritere as $zCritere) {
			$t[] = sprintf(" client_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
			$t[] = sprintf(" client_zPrenom like '%%%s%%' ", trim(addslashes($zCritere)) );
		}
		$zSql .= implode(" OR ", $t);
		$zSql .= ") GROUP BY clients.client_id ORDER BY client_zNom ASC ";  

		$cnx        = jDb::getConnection();
		$oRes       = $cnx->query($zSql);
		$tPersonne = $oRes->fetchAll();
		/*if (sizeof($tPersonne) == 0 && $iUtilisateurId == AUDIT_ID_CATRIONA){
			$zSql="SELECT * FROM clients WHERE 1=1 AND ";
			$t = array();
			foreach ($tCritere as $zCritere) {
				$t[] = sprintf(" client_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
				$t[] = sprintf(" client_zPrenom like '%%%s%%' ", trim(addslashes($zCritere)) );
			}
			$zSql .= implode(" OR ", $t);
			$zSql .= " GROUP BY client_id ORDER BY client_zNom ASC ";  

			$cnx        = jDb::getConnection();
			$oRes       = $cnx->query($zSql);
			$tPersonne = $oRes->fetchAll();
		}*/	

		$rep->datas  = $tPersonne;
		return $rep;
	}
	function getTypeEvenement (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iTypeEvenementId = $this->param('iTypeEvenementId', 0, true); 
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
		$oTypeEvenementSrv = typeEvenementsSrv::getById($iTypeEvenementId);
		$oRep->datas = $oTypeEvenementSrv;
		return $oRep;
	}


	
	function exportEventListing (){
		@ini_set ("memory_limit", "-1") ;
		global $gJConfig;
		$oRep = $this->getResponse('binary');
		$zExportsFileName = "exportEvenement_". date ("Ymd_His") . ".xls" ;
		$zExportsFullPath = JELIX_APP_WWW_PATH . "userFiles/xls/evenement/" . $zExportsFileName ;

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$toParams[0] = new stdClass ();
		$toParams[0]->zDateDebut = $this->param('zDateDebut','',true);
		$toParams[0]->zDateFin = $this->param('zDateFin','',true);
		$toParams[0]->iTypeEvenement = $this->param('iTypeEvenement','',true);
		$toParams[0]->iStagiaire = $this->param('iStagiaire','',true);
		$toParams[0]->evenement_origine = $this->param('evenement_origine','',true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;

 		$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');
		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}

       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;
		
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  
 		//$toTypeEvenement = typeEvenementsSrv::listCriteria($oParamsTypeevent);

		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		evenementSrv::exportEventListing($zExportsFullPath, $toEvenement, $toParams, $toTypeEvenement, $oUtilisateur, $toTypeEvenementSelected);
		if (is_file ($zExportsFullPath) ) {
			$oRep->fileName = $zExportsFullPath ;
			$oRep->outputFileName = $zExportsFileName ;
			$oRep->doDownload = true ;
		}else{
			die('Erreur lors de la création du fichier xls');
		}

		return $oRep;
	}

	function exportIcsEventListing (){
		@ini_set ("memory_limit", "-1") ;
		global $gJConfig;
		$oRep = $this->getResponse('binary');

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);
		if (isset ($oUtilisateur->utilisateur_id) && $oUtilisateur->utilisateur_id > 0){
			$zExportsFileName = "exportIcsEvenement_".$oUtilisateur->utilisateur_zPrenom."_". date ("Ymd_His") . ".ics" ;
			$zExportsFullPath = JELIX_APP_WWW_PATH . "userFiles/ics/evenement/" . $zExportsFileName ;

			$toParams[0] = new stdClass ();
			$toParams[0]->zDateDebut = $this->param('zDateDebut','',true);
			$toParams[0]->zDateFin = $this->param('zDateFin','',true);
			$toParams[0]->iTypeEvenement = $this->param('iTypeEvenement','',true);
			$toParams[0]->iStagiaire = $this->param('iStagiaire','',true);
			$toParams[0]->evenement_origine = $this->param('evenement_origine','',true);
			$toParams[0]->iCheckboxeAutoplanification = 0;
			$toParams[0]->iUtilisateur = $iUtilisateurId; 
			$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');

			foreach ($toEvenement['toListes'] as $oEvenement){
				$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
				$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
				$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
				$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
				$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
			}

			evenementSrv::exportIcsEventListing($zExportsFullPath, $toEvenement);
			if (is_file ($zExportsFullPath) ) {
				$oRep->fileName = $zExportsFullPath ;
				$oRep->outputFileName = $zExportsFileName ;
				$oRep->doDownload = true ;
			}else{
				die('Erreur lors de la création du fichier ics');
			}
		}else{
			die('Erreur lors de la création du fichier ics');
		}

		return $oRep;
	}

	function approcheParListeGetEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$id = $this->param('id', 0, true); 
		
    	jClasses::inc('evenement~evenementSrv');
		$oRep->datas = evenementSrv::getById($id);
		return $oRep;
	}	

	function approcheParListeGetPeriodicite(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		$toPeriodicite = array ('00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30');
		$oRep->datas = $toPeriodicite;
		return $oRep;

	}
	function approcheParListeGetDurePeriodicite(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		$toDurePeriodicite = array ('0 minutes', '5 minutes', '10 minutes', '15 minutes', '20 minutes', '25 minutes', '30 minutes', '35 minutes', '40 minutes', '45 minutes', '50 minutes', '55 minutes', '1 heures', '2 heures', '3 heures', '4 heures', '5 heures', '6 heures', '7 heures', '8 heures', '9 heures', '10 heures');
		$oRep->datas = $toDurePeriodicite;
		return $oRep;
	}
	function changeEtat (){
        $oRep = $this->getResponse('redirect');

		$date = $this->param('date', date('Y-m-d'), true);
		$iEvenementId = $this->param('iEvenementId', 0, true);
		$iTypeEtat = $this->param('typeetat', 0, true);
		$iAffichage = $this->param('iAffichage', 1, true);

		$oEtatEvenement = new StdClass () ;

		$oEtatEvenement->etat_iEvenementId = $iEvenementId ;
		$oEtatEvenement->etat_iTypeEtatId = $iTypeEtat ;
		$oEtatEvenement->etat_zCommentaire = "" ;
		$oEtatEvenement->etat_zDateSaisie = date("Y-m-d H:i:s") ;

    	jClasses::inc('evenement~etatEvenementSrv');
		$oRep->datas = etatEvenementSrv::save($oEtatEvenement);
		$oRep->action = 'jelix_calendar~FoCalendar:index' ;
		$oRep->params = array ('date'=>$date, 'iAffichage'=>$iAffichage);	
		return $oRep;
	}

	function eventListingEditEvent (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		jClasses::inc ('commun~toolDate') ;
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
    	jClasses::inc('client~societeSrv');

		$iEvenementId 					= $this->param('iEvenementId',0);  

		$bEdit 							= ($iEvenementId>0) ? true : false ;
        $oEvenement 					= ($iEvenementId>0) ? evenementSrv::getById($iEvenementId) : jDao::createRecord('commun~evenement') ;
        $oStagiaire 					= ($iEvenementId>0) ? clientSrv::getById($oEvenement->evenement_iStagiaire) : jDao::createRecord('commun~client') ;

		$oParamsTypeevent				= new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvenement					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvenement) && sizeof ($toTypeEvenement) > 0){
			$oTypeEvenement = array();
			$oTypeEvenement['iResTotal'] = sizeof ($toTypeEvenement) ;
			$oTypeEvenement['toListes']  = $toTypeEvenement ;
		}else{
			$oTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

		if ($bEdit && $oEvenement->evenement_zDateHeureDebut){
			$tzDateHeur = explode (' ', $oEvenement->evenement_zDateHeureDebut);
			$tzDate = explode ('-', $tzDateHeur[0]); 
			$tzHeure = explode (':', $tzDateHeur[1]); 
			$oEvenement->evenement_zDateDebut = $tzDate[2] . '/' . $tzDate[1] . '/' . $tzDate[0];
			$oEvenement->evenement_zHeureDebut = $tzHeure[0] . ':' . $tzHeure[1];
		}
		if ($oEvenement->evenement_iStagiaire){
			$toParams = array();
			$toParams[0] = new stdClass();
			$toParams[0]->id = $oEvenement->evenement_iStagiaire;
			$toStagiaire = clientSrv::listCriteria($toParams);
			$oEvenement->evenement_zStagiaire = $toStagiaire['toListes'][0]->client_zNom . ' ' .  $toStagiaire['toListes'][0]->client_zPrenom . '  [' .  $toStagiaire['toListes'][0]->client_zTel . ']  [' .  $toStagiaire['toListes'][0]->societe_zNom . ']  [' .  $toStagiaire['toListes'][0]->client_zVille . ']';
		}
		if ($oStagiaire->client_iSociete > 0){
			$oSociete = societeSrv::getById($oStagiaire->client_iSociete) ;
		}
		$oEvenement->evenement_zDateHeureDebutFr = '' ;
		if ($oEvenement->evenement_zDateHeureDebut != ""){
			$tzDate = explode (' ', $oEvenement->evenement_zDateHeureDebut);
			$tDate = explode ('-', $tzDate[0]);
			$tTime = explode (':', $tzDate[1]);
			$oEvenement->evenement_zDateHeureDebutFr = $tDate[2].'/'.$tDate[1].'/'.$tDate[0].' ' .$tTime[0].':'.$tTime[1];
		}

		if ($oEvenement->evenement_iTypeEvenementId > 0){
			$oTypeEvenementEvent = typeEvenementsSrv::getById ($oEvenement->evenement_iTypeEvenementId) ;
		}

       	$toParams['iEvenementId'] 		= $iEvenementId ;
       	$toParams['oEvenement'] 		= $oEvenement ;
       	$toParams['oStagiaire'] 		= $oStagiaire ;
		$toParams['toTypeEvenement'] 	= $oTypeEvenement['toListes'];
		$toParams['oSociete'] 			= $oSociete;
		$toParams['oTypeEvenement'] 	= $oTypeEvenementEvent;
//print_r($toParams) ; die;
		$oRep->datas = $toParams ;
		return $oRep;
	}

	function savePopEventListing(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		$iEventId 					= $this->param('iEventId',0);  
		$iTypeEventId 				= $this->param('iTypeEventId',0);  
		$zEventDesc 				= $this->param('zEventDesc',"");  
		$iEventStagiaireId 			= $this->param('iEventStagiaireId',0);  
		

		jClasses::inc ('evenement~evenementSrv') ;
		evenementSrv::savePopEventListing($iEventId, $iTypeEventId, $zEventDesc, $iEventStagiaireId) ;

		/*$oRep->action = 'evenement~FoEvenement:getEventListing' ;
		$oRep->params = array ('dtcm_event_rdv'=>$this->param('dtcm_event_rdv'), 
								'dtcm_event_rdv1'=>$this->param('dtcm_event_rdv1'), 
								'evenement_origine'=>$this->param('evenement_origine'), 
								'evenement_iTypeEvenementId'=>$this->param('evenement_iTypeEvenementId'),
								'evenement_stagiaire'=>$this->param('evenement_stagiaire')
								);	*/
		$oRep->datas = $iEventId ;
		return $oRep;
	}

	function eventListingDispo (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->bodyTpl = "evenement~FoEventListingDispo" ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvenement					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvenement) && sizeof ($toTypeEvenement) > 0){
			$oTypeEvenement = array();
			$oTypeEvenement['iResTotal'] = sizeof ($toTypeEvenement) ;
			$oTypeEvenement['toListes']  = $toTypeEvenement ;
		}else{
			$oTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

 		//$toTypeEvenement = typeEvenementsSrv::listCriteria($oParamsTypeevent);

		//Date Année Header 
		$iAnnee = date('Y');
		$tiAnnee = array ();
		for ($i=$iAnnee-10; $i<=$iAnnee+20; $i++){
			array_push ($tiAnnee, $i);
		}

		$toParamsClient[0] = new stdClass();
		$toParamsClient[0]->statut = 1;
		$toStagiaire = clientSrv::listCriteria($toParamsClient);

		$oRep->body->assign('now', date('d/m/Y'));
		$oRep->body->assign('tiAnnee', $tiAnnee);
		$oRep->body->assign('toTypeEvenement', $oTypeEvenement['toListes']);
		$oRep->body->assign('toStagiaire', $toStagiaire['toListes']);

		return $oRep;
	}

	function getEventListingDispo (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-1.5.1.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-ui-1.8.10.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery.loader-min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/script.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/affecter.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/light/css/redmond/jquery-ui-1.8.10.custom.css');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/timepicker.js');

    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		/****************
		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?7:$num_day)+1,$year);
		$zDatedebC      = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $premier_jour))); 

		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$dernier_jour = mktime(0,0,0, $month,7-(!$num_day?7:$num_day)+$day,$year);
		$zDatefinC      = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $dernier_jour)));
		****************/
		/****************/
		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$zDatedebC    = toolDate::toDateFr(toolDate::toDateSQL($date)); // Date du jour
		$zDatefinC    = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), '7 DAY')) ;
		/****************/


		$toParams[0] = new stdClass();
		$toParams[0]->statut = 1;

		$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
		$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);
		if ($toParams[0]->zDateFin == 0){
			$toParams[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams[0]->zDateDebut);
		}
		if ($iUtilisateurId == AUDIT_ID_CATRIONA){
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_DISPONIBLE;
		}else{
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE;
		}
		$toParams[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		$toParams[0]->iCheckDate = $this->param('iCheckDate', 0, true);
//print_r($toParams) ; die; 
 		$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');
		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}
		
       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

		$oRep->body->assign('toTypeEvenement', $toTypeEvenement['toListes']);
		$oRep->body->assign('oUtilisateur', $oUtilisateur);
		$oRep->body->assign('toEvenement', $toEvenement['toListes']);
		$oRep->body->assign('iResTotal', $toEvenement['iResTotal']);
		$oRep->body->assign('toParams', $toParams);
		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}
		$oRep->body->assign('toTypeEvenementSelected', $toTypeEvenementSelected);

		$tEventNonCreer = $this->param('tEventNonCreer', array(), true) ;
		$bAffectation = $this->param('bAffectation', 1, true) ;
		if ($bAffectation > 0){
			if (sizeof($tEventNonCreer)){
				$zEvenementId = "";
				foreach ($tEventNonCreer as $oEventNonCreer){
					if ($zEvenementId == ""){
						$zEvenementId = $oEventNonCreer->evenement_id;
					}else{
						$zEvenementId .= ",".$oEventNonCreer->evenement_id;
					}
				}
				if ($zEvenementId != ""){
					$tResult = evenementSrv::findEventByListEventId ($zEvenementId) ;
					$oRep->body->assign('tResult', $tResult);
				}
			} 
		}
		$oRep->body->assign('bAffectation', $bAffectation);
		
		$oRep->bodyTpl = "evenement~FoEventListingResultDispo" ;
		return $oRep;
	}

	function getDefaultTypeEvenement (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('utilisateurs~utilisateursSrv');

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oRep->datas = utilisateursSrv::getDefaultTypeEvenementUilisateur ($iUtilisateurId) ;
		return $oRep;
	}
}
?>