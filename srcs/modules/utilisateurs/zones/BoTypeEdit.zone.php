<?php 
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un administrateur
*/
class BoTypeEditZone extends jZone
{
    protected $_tplname = 'utilisateurs~BoTypeEdit.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	jClasses::inc('utilisateurs~typesSrv');

        $iTypeId 				= $this->getParam('iTypeId',0);  
        $bEdit 					= ($iTypeId>0) ? true : false ;
        $oType 					= ($iTypeId>0) ? typesSrv::getById($iTypeId) : jDao::createRecord('commun~typeutilisateurs') ;
       	$toParams['bEdit'] 		= $bEdit ;
       	$toParams['iTypeId'] 	= $iTypeId ;
       	$toParams['oType'] 		= $oType ;
        $this->_tpl->assign($toParams);
    }

}