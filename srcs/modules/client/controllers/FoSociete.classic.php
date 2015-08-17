<?php
/**
* @package   jelix_calendar
* @subpackage client
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class FoSocieteCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function add() {
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/societe.js');

		$oRep->bodyTpl = "client~FoAjoutSociete" ;

		$oRep->body->assignZone('oZoneLegend', 'commun~FoLegende', array());
		$oRep->body->assignZone('oZoneAjoutSociete', 'client~FoAjoutSociete', array());
		return $oRep;
    }

	function save() {
    	$toParams = $this->params() ;
        jClasses::inc('client~societeSrv');

        $oSociete = societeSrv::save($toParams) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'client~FoClient:add' ;
        return $oResp ;
    }

	function saveAjax (){
        $oResp = $this->getResponse('encodedJson') ;
		$toParams = $this->params() ;
        jClasses::inc('client~societeSrv');
        societeSrv::save($toParams) ;
		$toParamsSociete = array ();
		$toParamsSociete[0] = new stdClass();
		$toParamsSociete[0]->statut = 1;
       	$toTmpSociete = societeSrv::listCriteria($toParamsSociete);
        $oResp->datas = $toTmpSociete['toListes'] ;
        return $oResp ;
	}
}