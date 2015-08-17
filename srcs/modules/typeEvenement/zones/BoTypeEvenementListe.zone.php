<?php
/**
* @package      jelix_calendar
* @subpackage   typeEvenement
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des typeEvenement
*/
class BoTypeEvenementListeZone extends jZone
{
    protected $_tplname = 'typeEvenement~BoTypeEvenementListe.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
        $toParams = array();
    	$this->_tpl->assign($toParams) ;
        $this->_tpl->assignZone('oListeAjax', 'typeEvenement~BoTypeEvenementListeAjax', $toParams) ;
    }

}