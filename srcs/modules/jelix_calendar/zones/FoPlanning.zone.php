<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoPlanningZone extends jZone 
{
 
    protected $_tplname		= 'jelix_calendar~FoPlanning.zone' ;
	protected $_useCache	= false ;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{
		jClasses::inc ('commun~toolDate') ;
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
    	jClasses::inc('client~paysSrv');
    	jClasses::inc('client~PostSrv');

		if (isset($_GET['iAffichage'])){
			$iAffichage = $_GET['iAffichage'];
		}else{
			$iAffichage = 1;
		}
		if (isset($_GET['iGroupeId'])){
			$iGroupeId = $_GET['iGroupeId'];
		}else{
			$iGroupeId = 0;
		}
		if (isset($_SESSION['EVENT_TO_COPY'])){
			$iEventToCopy = $_SESSION['EVENT_TO_COPY'];
		}else{
			$iEventToCopy = 0;
		}

		if (isset($_GET['date'])){
			$zDate = $_GET['date'];
			$tDate = explode("-", $zDate);
			$date = $tDate[2] . '-' . $tDate[1] . '-' . $tDate[0];
		}else{
			$date = date('d-m-Y');	
		}

		if (isset($_GET['iTypeEvenementId'])){
			$iTypeEvenementId = $_GET['iTypeEvenementId'];
		}else{
			$iTypeEvenementId = 0;
		}
		if (isset($_GET['iUtilisateurId1'])){
			$iUtilisateurId1 = $_GET['iUtilisateurId1'];
		}else{
			$iUtilisateurId1 = 0;
		}

		$zCurentDate = date('d-m-Y');

		list($d, $m, $y) = explode('-', $date); 
		$zIntervalsemaine = 'Du '.toolDate::debutsem($y,$m,$d).' au '.toolDate::finsem($y,$m,$d);
		//Numero de la semaine en cours
		$oNumSemaine = toolDate::selectNumeroSemaine($y.'-'.$m.'-'.$d);

		//Liste des jours de la semaine 
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?7:$num_day)+1,$year);
		$zDatedeb      = toolDate::toDateSQL(date('d-m-Y', $premier_jour)); 
		$zDateDebSemainePrec      = toolDate::selectDateDebutSemainePrecedente ($zDatedeb);

		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$dernier_jour = mktime(0,0,0, $month,7-(!$num_day?7:$num_day)+$day,$year);
		$zDatefin      = toolDate::toDateSQL(date('d-m-Y', $dernier_jour));
		$zDateDebSemaineSuiv      = toolDate::selectDateDebutSemaineSuivante ($zDatefin);
		
		//$tDateListe = toolDate::getListeDateSemaine($zDatedeb); 
		$tDateListe = toolDate::getListeDateSemaineSansWE($zDatedeb); 

		//Evenement
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::getById($iUtilisateurId);

		//Liste Heure
		$tTimeListe = array('07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');

		switch ($oUtilisateur->utilisateur_plageHoraireId){
			case 2: // 30 Minutes
				$tTimeListeDemiHeure = array('07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30' , '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30'); 
			break;
			case 3: // 20 Minutes
				$tTimeListeDemiHeure = array('07:00', '07:20', '07:40', 
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
			break;
			default:
				$tTimeListeDemiHeure = array('07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'); 
		}
		if (!isset($oUtilisateur->utilisateur_decalageHoraire) || is_null($oUtilisateur->utilisateur_decalageHoraire)){
			$oUtilisateur->utilisateur_decalageHoraire = 0 ;
			$oUtilisateur->utilisateur_iPays = ID_PAYS_FRANCE ;
		}

		$oPays = paysSrv::getById($oUtilisateur->utilisateur_iPays) ;
		$toTimeListeDemiHeureDecalage = toolDate::getTimeListeDecalageHoraire($tTimeListeDemiHeure, $oUtilisateur->utilisateur_decalageHoraire) ;

		if (isset($oUtilisateur->utilisateur_iTypeId) && $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR){
			$toEventUser = evenementSrv::getEventUser(NULL, $zDatedeb, $zDatefin, $iTypeEvenementId, $iUtilisateurId1, 1, $iGroupeId);
		}else{		
			$toEventUser = evenementSrv::getEventUser($iUtilisateurId, $zDatedeb, $zDatefin, 0, $oUser->id, 1);
		}
		/*foreach($toEventUser['toListes'] as $oEvent){
			$oEvent->original = 1 ;	
		}*/
		foreach($toEventUser['toListes'] as $oEvent){
			$oEvent->original = 1 ;	
			$tzDate = explode(' ', $oEvent->evenement_zDateHeureDebut);
			$oEvent->evenement_date = $tzDate[0];
			$tEvenementDateFr = explode('-', $tzDate[0]);
			$oEvent->evenement_date_fr = $tEvenementDateFr[2] . '/' . $tEvenementDateFr[1] . '/' . $tEvenementDateFr[0];
			$oEvent->evenement_heure_fr = $tzDate[1];

			$iHeure = list($h, $m, $s) = explode(':', $tzDate[1]);
			$oEvent->evenement_heure = $iHeure[0];
			$oEvent->evenement_minute = $iHeure[1];
			$oEvent->oWpPost = null ; 
			if (isset($oEvent->evenement_iStagiaire) && $oEvent->evenement_iStagiaire > 0){
				//get wp_post
				$oEvent->oWpPost = PostSrv::chargePosteParIdPostBlog($oEvent->evenement_iStagiaire); 
				
			}

			switch ($oUtilisateur->utilisateur_plageHoraireId){
				case 2: // 30 Minutes
					if ($iHeure[1] < 30){
						$oEvent->evenement_heures = $iHeure[0].':00';
					}else{
						$oEvent->evenement_heures = $iHeure[0].':30';
					}
					if ($oEvent->original == 1){
						if ($oEvent->evenement_iDureeTypeId == 1){ // durre heure
							$iNombreD = ($oEvent->evenement_iDuree * 60)/30; 
						}else{
							$iNombreD = $oEvent->evenement_iDuree/30; 
						}
						$oEvent->evenement_iDureeTypeId = 2 ;
						$oEvent->evenement_iDuree = 30 ;
						for ($i=0; $i<$iNombreD -1; $i++){
							if ($i == 0){
								$x = 30 ;
							}else{
								$x = 30*($i+1) ;
							}
							$oNewEvent = clone $oEvent;
							$oNewEvent->evenement_zDateHeureDebut = toolDate::dateAdd($oEvent->evenement_zDateHeureDebut, $x . ' MINUTE');
							// Verifier si la plage est libre 
							$iEventExist = evenementSrv::getNextEvent($oEvent->evenement_zDateHeureDebut);
							if ($iEventExist == 0){
								$oNewEvent->original = 0 ;
								$tzDate = explode(' ', $oNewEvent->evenement_zDateHeureDebut);
								$oNewEvent->evenement_date = $tzDate[0];
								$tEvenementDateFr = explode('-', $tzDate[0]);
								$oNewEvent->evenement_date_fr = $tEvenementDateFr[2] . '/' . $tEvenementDateFr[1] . '/' . $tEvenementDateFr[0];
								$oNewEvent->evenement_heure_fr = $tzDate[1];

								$iHeure = list($h, $m, $s) = explode(':', $tzDate[1]);
								$oNewEvent->evenement_heure = $iHeure[0];
								$oNewEvent->evenement_minute = $iHeure[1];
								if ($iHeure[1] < 30){
									$oNewEvent->evenement_heures = $iHeure[0].':00';
								}else{
									$oNewEvent->evenement_heures = $iHeure[0].':30';
								}
								array_push ($toEventUser['toListes'], $oNewEvent); 
							}
						}
					}
				break;
				case 3: // 20 Minutes
					if ($iHeure[1] < 20){
						$oEvent->evenement_heures = $iHeure[0].':00';
					}elseif ($iHeure[1] >= 20 && $iHeure[1] < 40){
						$oEvent->evenement_heures = $iHeure[0].':20';
					}else{
						$oEvent->evenement_heures = $iHeure[0].':40';					
					}
					if ($oEvent->original == 1){
						if ($oEvent->evenement_iDureeTypeId == 1){ // durre heure
							$iNombreD = ($oEvent->evenement_iDuree * 60)/20; 
						}else{
							$iNombreD = $oEvent->evenement_iDuree/20; 
						}
						$oEvent->evenement_iDureeTypeId = 2 ;
						$oEvent->evenement_iDuree = 20 ;

						for ($i=0; $i<$iNombreD -1; $i++){
							$oNewEvent = clone $oEvent;
							if ($i == 0){
								$x = 20 ;
							}else{
								$x = 20*($i+1) ;
							}
							$oNewEvent->evenement_zDateHeureDebut = toolDate::dateAdd($oEvent->evenement_zDateHeureDebut, $x . ' MINUTE');
							// Verifier si la plage est libre 
							$iEventExist = evenementSrv::getNextEvent($oEvent->evenement_zDateHeureDebut);
							if ($iEventExist == 0){
								$oNewEvent->original = 0 ;
								$tzDate = explode(' ', $oNewEvent->evenement_zDateHeureDebut);
								$oNewEvent->evenement_date = $tzDate[0];
								$tEvenementDateFr = explode('-', $tzDate[0]);
								$oNewEvent->evenement_date_fr = $tEvenementDateFr[2] . '/' . $tEvenementDateFr[1] . '/' . $tEvenementDateFr[0];
								$oNewEvent->evenement_heure_fr = $tzDate[1];

								$iHeure = list($h, $m, $s) = explode(':', $tzDate[1]);
								$oNewEvent->evenement_heure = $iHeure[0];
								$oNewEvent->evenement_minute = $iHeure[1];
								if ($iHeure[1] < 20){
									$oEvent->evenement_heures = $iHeure[0].':00';
								}elseif ($iHeure[1] >= 20 && $iHeure[1] < 40){
									$oNewEvent->evenement_heures = $iHeure[0].':20';
								}else{
									$oNewEvent->evenement_heures = $iHeure[0].':40';					
								}

								array_push ($toEventUser['toListes'], $oNewEvent); 
							}
						}
					}
				break;
				default:
					$oEvent->evenement_heures = $iHeure[0].':00';
					if ($oEvent->original == 1 && $oEvent->evenement_iDureeTypeId == 1){
						$iNombreD = $oEvent->evenement_iDuree; 
						$iNombreD = $oEvent->evenement_iDuree; 
						$oEvent->evenement_iDureeTypeId = 1 ;
						$oEvent->evenement_iDuree = 1 ;
						for ($i=0; $i<$iNombreD -1; $i++){
							if ($i == 0){
								$x = 60 ;
							}else{
								$x = 60*($i+1) ;
							}
							$oNewEvent = clone $oEvent;
							$oNewEvent->evenement_zDateHeureDebut = toolDate::dateAdd($oEvent->evenement_zDateHeureDebut, $x . ' MINUTE');
							// Verifier si la plage est libre 
							$iEventExist = evenementSrv::getNextEvent($oEvent->evenement_zDateHeureDebut);
							if ($iEventExist == 0){
								$oNewEvent->original = 0 ;
								$tzDate = explode(' ', $oNewEvent->evenement_zDateHeureDebut);
								$oNewEvent->evenement_date = $tzDate[0];
								$tEvenementDateFr = explode('-', $tzDate[0]);
								$oNewEvent->evenement_date_fr = $tEvenementDateFr[2] . '/' . $tEvenementDateFr[1] . '/' . $tEvenementDateFr[0];
								$oNewEvent->evenement_heure_fr = $tzDate[1];

								$iHeure = list($h, $m, $s) = explode(':', $tzDate[1]);
								$oNewEvent->evenement_heure = $iHeure[0];
								$oNewEvent->evenement_minute = $iHeure[1];
								$oNewEvent->evenement_heures = $iHeure[0].':00';
								array_push ($toEventUser['toListes'], $oNewEvent); 
							}
						}
					}
			}
		}
		$toDateListe = array();
		foreach ($tDateListe as $oDateListe){
			$oTmpDateListe = new stdClass ();
			$oTmpDateListe->iCanAddEvent = 0;
			$oTmpDateListe->zDate = $oDateListe;

			$datejour = date('Y-m-d');
			$datefin = $oTmpDateListe->zDate; 
			$dfin = explode("-", $datefin);
			$djour = explode("-", $datejour);
			$finab = $dfin[2].$dfin[1].$dfin[0];
			$auj = $djour[2].$djour[1].$djour[0];

			if ($auj>$finab) {
				$oTmpDateListe->iCanAddEvent = 0;
			}else{
				$oTmpDateListe->iCanAddEvent = 1;
			}
			array_push ($toDateListe, $oTmpDateListe);
		}
		//Liste des type d'evenement
		$oParams = new stdClass ();
		$oParams->typeevenements_iStatut = STATUT_PUBLIE;

		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement					= typeEvenementsSrv::listCriteria($oParams);
		}  

		//Les utilisateurs
		$toParams = array();
		$toRessources = utilisateursSrv::listCriteria($toParams);

		$this->_tpl->assign('zIntervalsemaine', $zIntervalsemaine); 
		$this->_tpl->assign('toEventUser', $toEventUser['toListes']); 
		$this->_tpl->assign('tDateListe', $tDateListe); 
		$this->_tpl->assign('tTimeListe', $tTimeListe);
		$this->_tpl->assign('tTimeListeDemiHeure', $tTimeListeDemiHeure);
		$this->_tpl->assign('toTimeListeDemiHeureDecalage', $toTimeListeDemiHeureDecalage);
		$this->_tpl->assign('oNumSemaine', $oNumSemaine);
		$this->_tpl->assign('zDateDebSemainePrec', $zDateDebSemainePrec);
		$this->_tpl->assign('zDateDebSemaineSuiv', $zDateDebSemaineSuiv);
		$this->_tpl->assign('toDateListe', $toDateListe);
		$this->_tpl->assign('toTypeEvenement', $toTypeEvenement['toListes']); 
		$this->_tpl->assign('toRessources', $toRessources['toListes']); 
		$this->_tpl->assign('oUtilisateur', $oUtilisateur);
		$this->_tpl->assign('iTypeEvenementId', $iTypeEvenementId);
		$this->_tpl->assign('iUtilisateurId1', $iUtilisateurId1);
		$this->_tpl->assign('userId', $iUtilisateurId);
		$this->_tpl->assign('iAffichage', $iAffichage); 
		$this->_tpl->assign('date', $date); 
		$this->_tpl->assign('oPays', $oPays); 
		$this->_tpl->assign('iEventToCopy', $iEventToCopy); 
		$this->_tpl->assign('iGroupeId', $iGroupeId); 

		$this->_tpl->assignZone('oZoneLegend', 'commun~FoLegende', array());
		//print_r($toEventUser['toListes']);
	}
}
?>