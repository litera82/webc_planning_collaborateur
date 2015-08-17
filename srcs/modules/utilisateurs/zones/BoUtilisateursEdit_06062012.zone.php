<?php 
/**
* @package      jelix_calendar
* @subpackage   utilisateur
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un utilisateur
*/
class BoUtilisateursEditZone extends jZone
{
    protected $_tplname = 'utilisateurs~BoUtilisateursEdit.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	
    	jClasses::inc('utilisateurs~utilisateursSrv');
    	jClasses::inc('utilisateurs~plageHoraireSrv');
		jClasses::inc('utilisateurs~typesSrv');
    	jClasses::inc('client~paysSrv');

        $iUtilisateurId 			= $this->getParam('iUtilisateurId',0);  
        $bEdit 						= ($iUtilisateurId>0) ? true : false ;
        $oUtilisateurs 				= ($iUtilisateurId>0) ? utilisateursSrv::getById($iUtilisateurId) : jDao::createRecord('commun~utilisateurs') ;
		$_toCriterias				=  array();
		$toResultsType				= typesSrv::listCriteria($_toCriterias, "type_zLibelle" , "ASC" , 0 , 0 ) ;
		$toTypes					= $toResultsType['toListes'];
       	$toTmpPays					= paysSrv::chargerTous();
       	$toPlageHoraire				= plageHoraireSrv::chargerTous();

       	$toParams['bEdit'] 			= $bEdit ;
       	$toParams['oUtilisateurs'] 	= $oUtilisateurs ;
		$toParams['toTypes'] 		= $toTypes ;
		$toParams['toPays'] 		= $toTmpPays;
		$toParams['toPlageHoraire'] = $toPlageHoraire;

        $this->_tpl->assign($toParams);
    }

}