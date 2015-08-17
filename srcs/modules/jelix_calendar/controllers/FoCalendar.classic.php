<?php
/**
* @package   jelix_calendar
* @subpackage jelix_calendar
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class FoCalendarCtrl extends jController {

	public $pluginParams = array (	'*'=>array ('auth.required'=>true)) ;

    function planBoard() {
		global $gJConfig;
        $oRep = $this->getResponse('redirect');
		$oUser = jAuth::getUserSession();
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUtilisateur = utilisateursSrv::getById(utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password));

		if (isset ($oUtilisateur->utilisateur_id) && $oUtilisateur->utilisateur_id > 0 ){
			if (isset ($oUtilisateur->utilisateur_planBoard)){
				if ($oUtilisateur->utilisateur_planBoard == 1){
					$oRep->action = 'evenement~FoEvenement:getEventListing' ;
				}else{
					$oRep->action = 'jelix_calendar~FoCalendar:index';
				}
			}else{
				$oRep->action = 'evenement~FoEvenement:getEventListing' ;
			}
		}else{
			$oRep->action = 'evenement~FoEvenement:getEventListing' ;
		}
		$oRep->params = array ();	
		return $oRep;
	}
    /**
    *
    */
    function index() {
		global $gJConfig;
        $oRep = $this->getResponse('FoHtml');
		$oRep->bodyTpl = "jelix_calendar~index" ;
		//$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/webcalendar.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/commun/js/jquery.loader-min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$date = $this->param('date', date('Y-m-d'), true);
		$iTypeEvenementId = $this->param('iTypeEvenementId', 0, true);
		$iUtilisateurId1 = $this->param('iUtilisateurId1', 0, true);
		$iAffichage = $this->param('iAffichage', 1, true);
		$iGroupeId = $this->param('iGroupeId', 0, true);

		$oRep->body->assign('iAffichage', $iAffichage);
		switch ($iAffichage){
			case 2:
				$oRep->selectedDate = array($date, 2);
				$oRep->body->assignZone('oZonePlanning', 'jelix_calendar~FoPlanningJour', array('date'=>$date, 'iTypeEvenementId'=>$iTypeEvenementId, 'iUtilisateurId1'=>$iUtilisateurId1, 'iAffichage'=>$iAffichage, 'iGroupeId'=>$iGroupeId));
			break;
			case 3:
				$oRep->body->assignZone('oZonePlanning', 'jelix_calendar~FoPlanningMensuel', array('date'=>$date, 'iTypeEvenementId'=>$iTypeEvenementId, 'iUtilisateurId1'=>$iUtilisateurId1, 'iAffichage'=>$iAffichage, 'iGroupeId'=>$iGroupeId));
			break;
			default:
				$oRep->body->assignZone('oZonePlanning', 'jelix_calendar~FoPlanning', array('date'=>$date, 'iTypeEvenementId'=>$iTypeEvenementId, 'iUtilisateurId1'=>$iUtilisateurId1, 'iAffichage'=>$iAffichage, 'iGroupeId'=>$iGroupeId));
		}
		$oRep->body->assignZone('oZonePlanningSelection', 'jelix_calendar~FoPlanningSelection', array('date'=>$date, 'iTypeEvenementId'=>$iTypeEvenementId, 'iUtilisateurId1'=>$iUtilisateurId1, 'iAffichage'=>$iAffichage, 'iGroupeId'=>$iGroupeId));
		return $oRep;
    }

	function afficherChacherBlocSelection (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$val = $this->intParam('i', 0, true);	
		$_SESSION['AFFICHE_BLOC_SELECTION'] = $val;
		$oRep->datas = $_SESSION['AFFICHE_BLOC_SELECTION'];
		return $oRep;
	}
}
