<?php 
/**
* @package      jelix_calendar
* @subpackage   Evenementistrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un Evenementistrateur
*/
class BoEvenementEditZone extends jZone
{
    protected $_tplname = 'evenement~BoEvenementEdit.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	jClasses::inc('evenement~evenementSrv');
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
		jClasses::inc('utilisateurs~utilisateursSrv');
 
		$iEvenementId 					= $this->getParam('iEvenementId',0);  
		$iAffichage 					= $this->getParam('iAffichage',1);  
        $bEdit 							= ($iEvenementId>0) ? true : false ;
        $oEvenement 					= ($iEvenementId>0) ? evenementSrv::getById($iEvenementId) : jDao::createRecord('commun~evenement') ;

		$oUser = jAuth::getUserSession();
		//$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvenement					= utilisateursSrv::getListeTypeEvenementUilisateur (0);
		if (is_array($toTypeEvenement) && sizeof ($toTypeEvenement) > 0){
			$oTypeEvenement = array();
			$oTypeEvenement['iResTotal'] = sizeof ($toTypeEvenement) ;
			$oTypeEvenement['toListes']  = $toTypeEvenement ;
		}else{
			$oTypeEvenement					= typeEvenementsSrv::listCriteria();
		}  

		$toCritere['utilisateur_statut'] = STATUT_PUBLIE;
       	$oUtilisateur					= utilisateursSrv::listCriteria($toCritere);
		$toParamsClient[0] = new stdClass();
		$toParamsClient[0]->statut = 1;
		$toTmpStagiaire					= clientSrv::listCriteria($toParamsClient);
		$toParams['bEdit'] 				= $bEdit ;
       	$toParams['iEvenementId'] 		= $iEvenementId ;
       	$toParams['iAffichage'] 		= $iAffichage ;
       	$toParams['oEvenement'] 		= $oEvenement ;
		$toParams['toTypeEvenement'] 	= $oTypeEvenement['toListes'];
		$toParams['toStagiaire'] 		= $toTmpStagiaire['toListes'];
		$toParams['toUtilisateur'] 		= $oUtilisateur['toListes'];

		$this->_tpl->assign($toParams);
	}

}