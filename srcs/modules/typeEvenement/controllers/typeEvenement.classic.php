<?php
/**
* @package   jelix_calendar
* @subpackage TypeEvenement
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class typeEvenementCtrl extends jController {
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_TYPEEVENEMENT) ;
        $oResp->body->assignZone('zContent', 'typeEvenement~BoTypeEvenementListe') ;
        return $oResp ;
    }
	function edit() {
		global $gJConfig;
		$toParams = $this->params() ;
		$oResp = $this->getResponse('BoHtml') ;
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/typeEvenement.js');

        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_TYPEEVENEMENT) ;
		$oResp->body->assignZone('zContent', 'typeEvenement~BoTypeEvenementEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ;
        jClasses::inc('typeEvenement~typeEvenementsSrv');
        $oTypeEvenement = typeEvenementsSrv::save($toParams) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'typeEvenement~typeEvenement:index' ;
        return $oResp ;
    }
	function delete() {
        jClasses::inc('typeEvenement~typeEvenementsSrv');
        typeEvenementsSrv::delete($this->intParam('iTypeEvenementId', 0, true)) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'typeEvenement~typeEvenement:index' ;
        return $oResp ;
    }
	function testSupprimable(){
		$rep	 = $this->getResponse('encodedJson') ;
		$iTypeEvenementId = $this->intParam("iTypeEvenementId", 0, true) ; 
		if ($iTypeEvenementId > 0){
			jClasses::inc('typeEvenement~typeEvenementsSrv');
			$iResult = typeEvenementsSrv::testSupprimable($iTypeEvenementId) ;
		}
		$rep->datas = $iResult ;	
		
		return $rep;
	}
	function permuter(){
        $oResp = $this->getResponse('redirect') ;

		$iId			= $this->intParam("iId", 0, true);
		$iAction		= $this->intParam("iAction", 0, true);

		jClasses::inc('typeEvenement~typeEvenementsSrv');
		typeEvenementsSrv::permuter ($iId, $iAction);

        $oResp->action = 'typeEvenement~typeEvenement:index' ;

		return $oResp;
	}
}