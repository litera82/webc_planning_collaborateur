<?php 
/**
* @package      jelix_calendar
* @subpackage   Evenementistrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un Evenementistrateur
*/
class BoEvenementPlaningProfParEmailZone extends jZone
{
    protected $_tplname = 'evenement~BoEvenementPlaningProfParEmail.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	jClasses::inc('evenement~evenementSrv');
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
		jClasses::inc('utilisateurs~utilisateursSrv');
 
		$res 					= $this->getParam('res',0);  


		$toCritere['utilisateur_statut']	= STATUT_PUBLIE;
		$toCritere['utilisateur_bSendExcel']= STATUT_PUBLIE;
       	$oUtilisateur						= utilisateursSrv::listCriteria($toCritere);
		$toParams['toUtilisateur'] 			= $oUtilisateur['toListes'];
		$toParams['res']		 			= $res;

		$this->_tpl->assign($toParams);
	}

}