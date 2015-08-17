<?php 
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un administrateur
*/
class BoAdminEditZone extends jZone
{
    protected $_tplname = 'administrateurs~BoAdminEdit.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	
    	jClasses::inc('administrateurs~administrateursSrv');

        $iAdminId 				= $this->getParam('iAdminId',0);  
        $bEdit 					= ($iAdminId>0) ? true : false ;
        $oAdmin 				    = ($iAdminId>0) ? administrateursSrv::getById($iAdminId) : jDao::createRecord('commun~administrateurs') ;
       	$toParams['bEdit'] 		= $bEdit ;
       	$toParams['iAdminId'] 	= $iAdminId ;
       	$toParams['oAdmin'] 	= $oAdmin ;
        $this->_tpl->assign($toParams);
    }

}