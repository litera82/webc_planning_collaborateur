<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoPlanningJourZone extends jZone 
{
 
    protected $_tplname		= 'jelix_calendar~FoPlanningJour.zone' ;
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

		if (isset($_GET['iAffichage'])){
			$iAffichage = $_GET['iAffichage'];
		}else{
			$iAffichage = 2;
		}
		if (isset($_GET['iGroupeId'])){
			$iGroupeId = $_GET['iGroupeId'];
		}else{
			$iGroupeId = 0;
		}
		if (isset($_GET['date'])){
			$zDate = $_GET['date'];
			$tDate = explode("-", $zDate);
			$date = $tDate[2] . '-' . $tDate[1] . '-' . $tDate[0];
		}else{
			$date = date('d-m-Y');	
		}

		$zDatePlanning = toolDate::toDateWebCalendarFin(toolDate::toDateSQL($date).' 00:00:00'); 
		$tzDateSuivPrec = toolDate::getDatePrecSuiv(toolDate::toDateSQL($date).' 00:00:00'); 
		
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

		$zDatedeb      = toolDate::toDateSQL($date); 
		$zDatefin      = toolDate::toDateSQL($date); 

		//Liste Heure
		$tTimeListe = array('07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'); 
		
		//Evenement
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::getById($iUtilisateurId);

		if (isset($oUtilisateur->utilisateur_iTypeId) && $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR){
			$toEventUser = evenementSrv::getEventUser(NULL, $zDatedeb, $zDatefin, $iTypeEvenementId, $iUtilisateurId1, 2, $iGroupeId);
		}else{		
			$toEventUser = evenementSrv::getEventUser($iUtilisateurId, $zDatedeb, $zDatefin, 0, $oUser->id, 2);
		}

		$toEventUserFinal = array();
		foreach($toEventUser['toListes'] as $oEvent){
			$tzDate = explode(' ', $oEvent->evenement_zDateHeureDebut);
			$oEvent->evenement_date = $tzDate[0];
			$tEvenementDateFr = explode('-', $tzDate[0]);
			$oEvent->evenement_date_fr = $tEvenementDateFr[2] . '/' . $tEvenementDateFr[1] . '/' . $tEvenementDateFr[0];
			$oEvent->evenement_heure_fr = $tzDate[1];

			$iHeure = list($h, $m, $s) = explode(':', $tzDate[1]);
			$oEvent->evenement_heure = $iHeure[0]; 
		}

		$toEventNewUser = array ();
		$iCptr=0;
		foreach ($toEventUser['toListes'] as $oEvent){
			$zUser = $oEvent->utilisateur_zNom . ' ' . $oEvent->utilisateur_zPrenom;
			if (isset ($toEventNewUser[$zUser]) && sizeof($toEventNewUser[$zUser]) == 0){
				$toEventNewUser[$zUser] = array ();
				array_push($toEventNewUser[$zUser], $oEvent); 
			}else{
				//$iCptr = sizeof($toEventNewUser[$zUser]); 
				$toEventNewUser[$zUser][$iCptr] = $oEvent;
			}
			$iCptr++;

			$oEvent->oWpPost = null ; 
			if (isset($oEvent->evenement_iStagiaire) && $oEvent->evenement_iStagiaire > 0){
				//get wp_post
				$oEvent->oWpPost = PostSrv::chargePosteParIdPostBlog($oEvent->evenement_iStagiaire); 
				
			}
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
		
		$tzCurrentdate = explode ('-', $date);
		$zCurrentdate = $tzCurrentdate[2].'-'.$tzCurrentdate[1].'-'.$tzCurrentdate[0];
		
		$this->_tpl->assign('date', toolDate::toDateSQL($date)); 
		$this->_tpl->assign('zCurrentdate', $zCurrentdate); 
		$this->_tpl->assign('zDatePlanning', $zDatePlanning); 
		$this->_tpl->assign('toEventUser', $toEventNewUser); 
		$this->_tpl->assign('tTimeListe', $tTimeListe);
		$this->_tpl->assign('tzDateSuivPrec', $tzDateSuivPrec);
		$this->_tpl->assign('toTypeEvenement', $toTypeEvenement['toListes']); 
		$this->_tpl->assign('toRessources', $toRessources['toListes']); 
		$this->_tpl->assign('oUtilisateur', $oUtilisateur);
		$this->_tpl->assign('iTypeEvenementId', $iTypeEvenementId);
		$this->_tpl->assign('iUtilisateurId1', $iUtilisateurId1);
		$this->_tpl->assign('date', $date); 
		$this->_tpl->assign('iAffichage', $iAffichage); 
		$this->_tpl->assignZone('oZoneLegend', 'commun~FoLegende', array());
	}
}
?>