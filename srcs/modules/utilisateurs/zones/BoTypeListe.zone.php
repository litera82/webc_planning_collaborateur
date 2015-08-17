<?php
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des types d'utilisateurs
*/
class BoTypeListeZone extends jZone
{
    protected $_tplname = 'utilisateurs~BoTypeListe.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	$toParams = array();
    	$this->_tpl->assign($toParams) ;
        $this->_tpl->assignZone('oListeAjax', 'utilisateurs~BoTypeListeAjax', $toParams) ;
    }

}