<?php 
/**
* @package      jelix_calendar
* @subpackage   typeEvenement
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un administrateur
*/
class BoTypeEvenementEditZone extends jZone
{
    protected $_tplname = 'typeEvenement~BoTypeEvenementEdit.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	
    	jClasses::inc('typeEvenement~typeEvenementsSrv');

        $iTypeEvenementId 					= $this->getParam('iTypeEvenementId',0);  
        $bEdit 								= ($iTypeEvenementId>0) ? true : false ;
        $oTypeEvenement 				    = ($iTypeEvenementId>0) ? typeEvenementsSrv::getById($iTypeEvenementId) : jDao::createRecord('commun~typeevenements') ;
 		$toDure = array ('0 minutes', '5 minutes', '10 minutes', '15 minutes', '20 minutes', '25 minutes', '30 minutes', '35 minutes', '40 minutes', '45 minutes', '50 minutes', '55 minutes', '1 heures', '2 heures', '3 heures', '4 heures', '5 heures', '6 heures', '7 heures', '8 heures', '9 heures', '10 heures');

		$toParams['toDure'] 			= $toDure;
		$toParams['bEdit'] 				= $bEdit ;
       	$toParams['iTypeEvenementId'] 	= $iTypeEvenementId ;
       	$toParams['oTypeEvenement'] 	= $oTypeEvenement ;

		$this->_tpl->assign($toParams);
    }

}