<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Tahiry RANDRIAMBOLA <t.randriambola@gmail.com>
*/
require_once(JELIX_APP_MODULE_PATH.'commun/classes/calendar.class.php');

class FoPlanningSelectionZone extends jZone 
{
 
    protected $_tplname		= 'jelix_calendar~FoPlanningSelection.zone' ;
	protected $_useCache	= false ;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{
        jClasses::inc('utilisateurs~typesSrv');
        jClasses::inc('utilisateurs~groupeSrv');
        jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc ('commun~toolDate') ;
		//Calendrier
		if (isset($_GET['date'])){
			$date = $_GET['date'] ;
		}else{
			$date = date("Y-m-d");
		}
		if (isset($_GET['iUtilisateurId1'])){
			$iUtilisateurId1 = $_GET['iUtilisateurId1'] ;
		}else{
			$iUtilisateurId1 = 0;
		}
		$iAffichage = $this->getParam('iAffichage',1);  
		$iGroupeId = $this->getParam('iGroupeId',0);
		
		$calendar = new Calendar($date, NULL, NULL, $iAffichage, $iGroupeId);
		setlocale(LC_ALL, 'fr_FR'); 
		$calendar->week_start = 1;
		$first_day = $calendar->year . "-" . $calendar->month . "-01";
		$previous_year = date("Y", strtotime("-1 month", strtotime($first_day)));
		$previous_month = date("m", strtotime("-1 month", strtotime($first_day)));
		$month = date("m", strtotime("0 month", strtotime($first_day)));
		$next_year = date("Y", strtotime("+1 month", strtotime($first_day)));
		$next_month = date("m", strtotime("+1 month", strtotime($first_day)));
		
		if ($next_month == '12'){
			$next_year_next = $next_year+1;	
			$zNextDate = $next_year_next.'-01-01';
		}else{
			$zNextDate = $next_year.'-'.$next_month . '-01';
		}

		if ($previous_month == '01'){
			$previous_year_prev = $previous_year-1;	
			$zPrevDate = $previous_year_prev.'-12-01';
		}else{
			$zPrevDate = $previous_year.'-'.$previous_month . '-01';
		}

		//Profil 
		$toParams = array();
		$toProfils = typesSrv::listCriteria($toParams);

		//Ressources 
		$toParams = array();
		$toParams['groupe_id'] = $iGroupeId ;
		$toRessources = utilisateursSrv::listCriteria($toParams);

		//Utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		//Pays 
		$toPays = utilisateursSrv::listePaysClient();

		//Groupe
		$toGroupe = groupeSrv::listCriteria(array());

		$previous_month_fr	= toolDate::getMoisEnTouteLettre($previous_month);
		$month_fr			= toolDate::getMoisEnTouteLettre($month);
		$next_month_fr		= toolDate::getMoisEnTouteLettre($next_month);

		/**
		* Affichercacher le bloc selection
		**/
		if (isset ($_SESSION['AFFICHE_BLOC_SELECTION'])){
			$val = $_SESSION['AFFICHE_BLOC_SELECTION'] ;	
		}else{
			$val = 1 ;
		}
		$_SESSION['AFFICHE_BLOC_SELECTION'] = $val;


		$this->_tpl->assign('calendar', $calendar);
		$this->_tpl->assign('previous_year', $previous_year);
		$this->_tpl->assign('previous_month', $previous_month);
		$this->_tpl->assign('previous_month_fr', $previous_month_fr);

		$this->_tpl->assign('next_year', $next_year);
		$this->_tpl->assign('next_month', $next_month);
		$this->_tpl->assign('next_month_fr', $next_month_fr);
		
		$this->_tpl->assign('month', $month);
		$this->_tpl->assign('month_fr', $month_fr);
		$this->_tpl->assign('iGroupeId', $iGroupeId);

		$this->_tpl->assign('zNextDate', $zNextDate);
		$this->_tpl->assign('zPrevDate', $zPrevDate);
		$this->_tpl->assign('toProfils', $toProfils['toListes']); 
		$this->_tpl->assign('toRessources', $toRessources['toListes']); 
		$this->_tpl->assign('toGroupe', $toGroupe['toListes']); 
		$this->_tpl->assign('oUtilisateur', $oUtilisateur); 
		$this->_tpl->assign('toPays', $toPays); 
		$this->_tpl->assign('date', $date); 
		$this->_tpl->assign('iUtilisateurId1', $iUtilisateurId1); 

		$this->_tpl->assign('afficheBlocSelection', $_SESSION['AFFICHE_BLOC_SELECTION']); 
	}
}
?>