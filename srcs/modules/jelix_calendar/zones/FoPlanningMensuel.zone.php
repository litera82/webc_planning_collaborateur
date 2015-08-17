<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/
@ini_set ("memory_limit", -1) ;
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
    	jClasses::inc('client~PostSrv');

		$oUser=jAuth::getUserSession ();

		if (isset($_GET['iAffichage'])){
			$iAffichage = $_GET['iAffichage'];
		}else{
			$iAffichage = 3;
		}
		if (isset($_GET['iGroupeId'])){
			$iGroupeId = $_GET['iGroupeId'];
		}else{
			$iGroupeId = 0;
		}
		if (isset($_GET['date'])){
			$zDate = $_GET['date'];
			$tDate = explode("-", $zDate);
			$idate = mktime(0, 0, 0, $tDate[1], $tDate[2], $tDate[0]);
		}else{
			$zDate = date('d-m-Y');	
			$tDate = explode("-", $zDate);
			$idate = mktime(0, 0, 0, $tDate[1], 1, $tDate[2]);
		}
		$date = date('d-m-Y', $idate);

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
		$tJourListe = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'); 
		$tzDateFr = toolDate::getTousLesJoursDuMois();

		//Evenement
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::getById($iUtilisateurId);
		

		if (isset($oUtilisateur->utilisateur_iTypeId) && $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR){
			$toEventUser = evenementSrv::getEventUser(NULL, $zDatedeb, $zDatefin, $iTypeEvenementId, $iUtilisateurId1, 3, $iGroupeId);
		}else{		
			$toEventUser = evenementSrv::getEventUser($iUtilisateurId, $zDatedeb, $zDatefin, 0, $oUser->id, 3 );
		}
		foreach($toEventUser['toListes'] as $oEvent){
			$tzDate = explode(' ', $oEvent->evenement_zDateHeureDebut);
			$oEvent->evenement_date = $tzDate[0];
			$tEvenementDateFr = explode('-', $tzDate[0]);
			$oEvent->evenement_date_fr = $tEvenementDateFr[2] . '/' . $tEvenementDateFr[1] . '/' . $tEvenementDateFr[0];
			$oEvent->evenement_heure_fr = $tzDate[1];
			$tEvenementHeureFr1 = explode(':', $tzDate[1]);
			$oEvent->evenement_heure_fr1 = $tEvenementHeureFr1[0].':'.$tEvenementHeureFr1[1];

			$oEvent->evenement_date_jour = $tEvenementDateFr[2];
			$oEvent->evenement_date_mois = $tEvenementDateFr[1];
			$oEvent->evenement_date_annee = $tEvenementDateFr[0];

			$iHeure = list($h, $m, $s) = explode(':', $tzDate[1]);
			$oEvent->evenement_heure = $iHeure[0]; 

			$oEvent->oWpPost = null ; 
			if (isset($oEvent->evenement_iStagiaire) && $oEvent->evenement_iStagiaire > 0){
				//get wp_post
				$oEvent->oWpPost = PostSrv::chargePosteParIdPostBlog($oEvent->evenement_iStagiaire); 
				
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
		//$toTypeEvenement = typeEvenementsSrv::listCriteria($oParams);
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
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

		//Utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$anneTitre['jan'] = "Janvier";
		$anneTitre['jun'] = "Juin";
		$anneTitre['feb'] = "Février";
		$anneTitre['aug'] = "Aout";
		$anneTitre['mar'] = "Mars";
		$anneTitre['sep'] = "Septembre";
		$anneTitre['apr'] = "Avril";
		$anneTitre['oct'] = "Octobre";
		$anneTitre['may'] = "Mai";
		$anneTitre['nov'] = "Novembre";
		$anneTitre['jul'] = "Juillet";
		$anneTitre['dec'] = "Décembre";

		/*if (!isset($_REQUEST['date'])) {
			$date = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		} else {
			$date = $_REQUEST['date'];
		}*/
		$la = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

		$day = date('d', $idate);
		$month = date('m', $idate);
		$year = date('Y', $idate);

		$month_start = mktime(0, 0, 0, $month, 1, $year);
		$month_name = date('M', $month_start);
		$monthTitre = $anneTitre[strtolower($month_name)];
		$month_start_day = date('D', $month_start);

		$month_startPrec = mktime(0, 0, 0, $month-1, 1, $year);
		$month_namePrec = date('M', $month_startPrec);

		$month_startSuiv = mktime(0, 0, 0, $month+1, 1, $year);
		$month_nameSuiv = date('M', $month_startSuiv);

		switch ($month_start_day) {
			case "Sun": $offset = 0;
				break;
			case "Mon": $offset = 1;
				break;
			case "Tue": $offset = 2;
				break;
			case "Wed": $offset = 3;
				break;
			case "Thu": $offset = 4;
				break;
			case "Fri": $offset = 5;
				break;
			case "Sat": $offset = 6;
				break;
		}

		if ($month == 1) {
			$num_days_last = cal_days_in_month(0, 12, ($year - 1));
		} else {
			$num_days_last = cal_days_in_month(0, ($month - 1), $year);
		}

		$num_days_current = cal_days_in_month(0, $month, $year);

		for ($i = 1; $i <= $num_days_current; $i++) {
			$num_days_array[] = $i;
		}

		for ($i = 1; $i <= $num_days_last; $i++) {
			$num_days_last_array[] = $i;
		}

		if ($offset > 0) {
			$offset_correction = array_slice($num_days_last_array, -$offset, $offset);
			$new_count = array_merge($offset_correction, $num_days_array);
			$offset_count = count($offset_correction);
		} else {
			$offset_count = 0;
			$new_count = $num_days_array;
		}

		$current_num = count($new_count);


		if ($current_num > 35) {
			$num_weeks = 6;
			$outset = (42 - $current_num);
		} elseif ($current_num < 35) {
			$num_weeks = 5;
			$outset = (35 - $current_num);
		}
		if ($current_num == 35) {
			$num_weeks = 5;
			$outset = 0;
		}

		for ($i = 1; $i <= $outset; $i++) {
			$new_count[] = $i;
		}

		$weeks = array_chunk($new_count, 7);

		if ($month == 1) {
			$timestampP = mktime(0, 0, 0, 12, $day, ($year - 1));
		} else {
			$timestampP = mktime(0, 0, 0, ($month - 1), $day, $year);
		}
		$tDateP = explode('-', date('Y-m-d', $timestampP));
		$dateP = $tDateP[0].'-'.$tDateP[1].'-01'; 	
		$previous_link = jUrl::get('jelix_calendar~FoCalendar:index', array('date'=>$dateP, 'iAffichage'=>3));

		if ($month == 12) {
			$timestampN = mktime(0, 0, 0, 1, $day, ($year + 1));
		} else {
			$timestampN = mktime(0, 0, 0, ($month + 1), $day, $year);
		}
		$tDateN = explode('-', date('Y-m-d', $timestampN));
		$dateN = $tDateN[0].'-'.$tDateN[1].'-01'; 	
		$next_link = jUrl::get('jelix_calendar~FoCalendar:index', array('date'=>$dateN, 'iAffichage'=>3));

		$eventsHere = "";

		$this->_tpl->assign('weeks', $weeks);
		$this->_tpl->assign('offset_count', $offset_count);
		$this->_tpl->assign('num_weeks', $num_weeks);
		$this->_tpl->assign('outset', $outset);
		$this->_tpl->assign('year', $year);
		$this->_tpl->assign('monthTitre', $monthTitre);

		$this->_tpl->assign('month', $month);
		$this->_tpl->assign('eventsHere', $eventsHere);
		$this->_tpl->assign('previous_link', $previous_link);
		$this->_tpl->assign('next_link', $next_link);

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