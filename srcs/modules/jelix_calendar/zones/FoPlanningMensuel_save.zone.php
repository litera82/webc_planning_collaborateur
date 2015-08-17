<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoPlanningMensuelZone extends jZone 
{
 
    protected $_tplname		= 'jelix_calendar~FoPlanningMensuel.zone' ;
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
		if (isset($_GET['iAffichage'])){
			$iAffichage = $_GET['iAffichage'];
		}else{
			$iAffichage = 1;
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
		
		$tDateListe = toolDate::getListeDateSemaine($zDatedeb); 

		//Liste Date
		$tJourListe = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'); 
		$tzDateFr = toolDate::getTousLesJoursDuMois();

		//Evenement
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::getById($iUtilisateurId);

		if (isset($oUtilisateur->utilisateur_iTypeId) && $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR){
			$toEventUser = evenementSrv::getEventUser(NULL, $zDatedeb, $zDatefin, $iTypeEvenementId, $iUtilisateurId1);
		}else{		
			$toEventUser = evenementSrv::getEventUser($iUtilisateurId, $zDatedeb, $zDatefin);
		}

		foreach($toEventUser['toListes'] as $oEvent){
			$tzDate = explode(' ', $oEvent->evenement_zDateHeureDebut);
			$oEvent->evenement_date = $tzDate[0];
			$tEvenementDateFr = explode('-', $tzDate[0]);
			$oEvent->evenement_date_fr = $tEvenementDateFr[2] . '/' . $tEvenementDateFr[1] . '/' . $tEvenementDateFr[0];
			$oEvent->evenement_heure_fr = $tzDate[1];

			$iHeure = list($h, $m, $s) = explode(':', $tzDate[1]);
			$oEvent->evenement_heure = $iHeure[0]; 
			if ($oEvent->evenement_iDuree > 1 ){
				$j=0; 
				while($j<$oEvent->evenement_iDuree-1){
					$oEventDuplicate = evenementSrv::copyObjectEvent($oEvent);
					$oEventDuplicate->evenement_heure = $iHeure[0]+$j+1; 
					array_push ($toEventUser['toListes'], $oEventDuplicate);
					$j++; 
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
		$toTypeEvenement = typeEvenementsSrv::listCriteria($oParams);

		//Les utilisateurs
		$toParams = array();
		$toRessources = utilisateursSrv::listCriteria($toParams);

		//Utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$this->_tpl->assign('zIntervalsemaine', $zIntervalsemaine); 
		$this->_tpl->assign('toEventUser', $toEventUser['toListes']); 
		$this->_tpl->assign('tDateListe', $tDateListe); 
		$this->_tpl->assign('tJourListe', $tJourListe);
		$this->_tpl->assign('tzDateFr', $tzDateFr);
		$this->_tpl->assign('oNumSemaine', $oNumSemaine);
		$this->_tpl->assign('zDateDebSemainePrec', $zDateDebSemainePrec);
		$this->_tpl->assign('zDateDebSemaineSuiv', $zDateDebSemaineSuiv);
		$this->_tpl->assign('toDateListe', $toDateListe);
		$this->_tpl->assign('toTypeEvenement', $toTypeEvenement['toListes']); 
		$this->_tpl->assign('toRessources', $toRessources['toListes']); 
		$this->_tpl->assign('oUtilisateur', $oUtilisateur);
		$this->_tpl->assign('iTypeEvenementId', $iTypeEvenementId);
		$this->_tpl->assign('iUtilisateurId1', $iUtilisateurId1);

		$this->_tpl->assign('iAffichage', $iAffichage); 
		$this->_tpl->assignZone('oZoneLegend', 'commun~FoLegende', array());
	}
}
?>