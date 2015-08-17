<?php
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des administrateurs
*/
class BoClientListeZone extends jZone
{
    protected $_tplname = 'client~BoClientListe.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
        $toParams = array();
		$oCritere = $this->getParam("oCritere", "");
		$toParams[0] = $oCritere;
    	$this->_tpl->assign($toParams) 
		//print_r($oCritere); die;
			;
        $this->_tpl->assignZone('oListeAjax', 'client~BoPostListeAjax', array('oCritere'=>$oCritere)) ;
        $this->_tpl->assignZone('oListeCritereRecerche', 'client~BoClientRecherche', array('oCritere'=>$oCritere)) ;
	}

}