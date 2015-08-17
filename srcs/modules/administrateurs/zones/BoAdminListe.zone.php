<?php
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des administrateurs
*/
class BoAdminListeZone extends jZone
{
    protected $_tplname = 'administrateurs~BoAdminListe.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
        $toParams = array();
    	$this->_tpl->assign($toParams) ;
		
        $this->_tpl->assignZone('oListeAjax', 'administrateurs~BoAdminListeAjax', $toParams) ;
    }

}