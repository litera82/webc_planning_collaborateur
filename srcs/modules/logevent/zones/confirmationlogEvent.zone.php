<?php 
/**
* @package      jelix_calendar
* @subpackage   Evenementistrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un Evenementistrateur
*/
class confirmationlogEventZone extends jZone
{
    protected $_tplname = 'logevent~confirmationlogEvent.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
		$bSave = $this->getParam('bSave',0);
		$this->_tpl->assign('bSave', $bSave);
	}

}