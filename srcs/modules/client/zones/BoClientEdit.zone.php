<?php 
/**
* @package      jelix_calendar
* @subpackage   Clientistrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un Clientistrateur
*/
class BoClientEditZone extends jZone
{
    protected $_tplname = 'client~BoClientEdit.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	jClasses::inc('client~clientSrv');
    	jClasses::inc('client~societeSrv');
    	jClasses::inc('client~paysSrv');
    	jClasses::inc('utilisateurs~utilisateursSrv');
  
		$iClientId 					= $this->getParam('iClientId',0);  
        $bEdit 						= ($iClientId>0) ? true : false ;
        $oClient 					= ($iClientId>0) ? ClientSrv::getById($iClientId) : jDao::createRecord('commun~client') ;
		$toParamsSociete = array ();
		$toParamsSociete[0] = new stdClass();
		$toParamsSociete[0]->statut = 1;
       	$toTmpSociete				= societeSrv::listCriteria($toParamsSociete);
		$_toParamsUtilisateur['utilisateur_statut'] = 1;
       	$toTmpUtilisateur			= utilisateursSrv::listCriteria($_toParamsUtilisateur);
		$toTmpPays					= paysSrv::chargerTous();
		$toParams['bEdit'] 			= $bEdit ;
       	$toParams['iClientId'] 		= $iClientId ;
       	$toParams['oClient'] 		= $oClient ;
		$toParams['toSociete'] 		= $toTmpSociete['toListes'];
		$toParams['toUtilisateur'] 		= $toTmpUtilisateur['toListes'];
		$toParams['toPays'] 		= $toTmpPays;

		$this->_tpl->assign($toParams);
	}

}