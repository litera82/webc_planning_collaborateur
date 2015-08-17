<?php
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des administrateurs
*/
class BoEvenementListeZone extends jZone
{
    protected $_tplname = 'evenement~BoEvenementListe.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
        $toParams = array();
		$oCritere = $this->getParam("oCritere", "");
		$toParams[0] = $oCritere;
    	$this->_tpl->assign($toParams) ;
        $this->_tpl->assignZone('oListeAjax', 'evenement~BoEvenementListeAjax', array('oCritere'=>$oCritere)) ;
        $this->_tpl->assignZone('oListeCritereRecerche', 'evenement~BoEvenementRecherche', array('oCritere'=>$oCritere)) ;
	}

}