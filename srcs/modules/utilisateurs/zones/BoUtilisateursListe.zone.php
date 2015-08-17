<?php
/**
* @package      jelix_calendar
* @subpackage   utilisateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des administrateurs
*/
class BoUtilisateursListeZone extends jZone
{
    protected $_tplname = 'utilisateurs~BoUtilisateursListe.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	$toParams = array();
    	$this->_tpl->assign($toParams) ;
        $this->_tpl->assignZone('oListeAjax', 'utilisateurs~BoUtilisateursListeAjax', $toParams) ;
    }

}