<?php 
/**
* @package      jelix_calendar
* @subpackage   Evenementistrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un Evenementistrateur
*/
class BoLogEventWithParamPageZone extends jZone
{
    protected $_tplname = 'logevent~BoLogEventWithParamPage.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
		$bSave = $this->getParam('bSave',0);
		$this->_tpl->assign('bSave', $bSave);
        $this->_tpl->assignZone('oCritereRecherche', 'logevent~BoLogEventWithParam', array()) ;
	}
}