<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoPlanningAjaxZone extends jZone 
{
 
    protected $_tplname		= 'jelix_calendar~FoPlanningAjax.zone' ;
	protected $_useCache	= false ;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

		$iEventId = $this->getParam('iEventId',0);  

		$oEvent = evenementSrv::getEventById ($iEventId) ;
		$tzDate = explode(' ', $oEvent->evenement_zDateHeureDebut);
		$oEvent->evenement_date = $tzDate[0];
		$tEvenementDateFr = explode('-', $tzDate[0]);
		$oEvent->evenement_date_fr = $tEvenementDateFr[2] . '/' . $tEvenementDateFr[1] . '/' . $tEvenementDateFr[0];
		$oEvent->evenement_heure_fr = $tzDate[1];
		$tTime1 = explode(':', $oEvent->evenement_heure_fr);
		$oEvent->time1 = $tTime1[0].':'.$tTime1[1] ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);

		$this->_tpl->assign('userId', $iUtilisateurId); 
		$this->_tpl->assign('iEventToCopy', $iEventId); 
		$this->_tpl->assign('oEvent', $oEvent); 
	}
}
?>