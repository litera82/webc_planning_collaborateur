<?php
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des administrateurs
*/
class BoPostsListeZone extends jZone
{
    protected $_tplname = 'posts~BoPostsListe.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
        $toParams = array();
		$oCritere = $this->getParam("oCritere", "");
		$toParams[0] = $oCritere;
    	$this->_tpl->assign($toParams) ;
        $this->_tpl->assignZone('oListeAjax', 'posts~BoPostsListeAjax', array('oCritere'=>$oCritere)) ;
        $this->_tpl->assignZone('oListeCritereRecerche', 'posts~BoPostsRecherche', array('oCritere'=>$oCritere)) ;
	}
}