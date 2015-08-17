<?php 
/**
* @package      jelix_calendar
* @subpackage   Evenementistrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un Evenementistrateur
*/
class BoPageDedoublonnerPostZone extends jZone
{
    protected $_tplname = 'posts~BoPageDedoublonnerPost.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
		$res = $this->getParam('res',0);
		
		$toParams['res']		 			= $res;
		$this->_tpl->assign($toParams);
	}

}