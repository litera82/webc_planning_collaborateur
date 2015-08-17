<?php
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des administrateurs
*/
class BoPostListeZone extends jZone
{
    protected $_tplname = 'client~BoPostListe.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
        $toParams = array();
		$oCritere = $this->getParam("oCritere", "");
		$toParams[0] = $oCritere;
		//print_r($oCritere); die("BoPostListeZone");
    	$this->_tpl->assign($toParams) ;
        $this->_tpl->assignZone('oListeAjax', 'client~BoPostListeAjax', array('oCritere'=>$oCritere)) ;
        $this->_tpl->assignZone('oListeCritereRecerche', 'client~BoPostRecherche', array('oCritere'=>$oCritere)) ;
	}

}