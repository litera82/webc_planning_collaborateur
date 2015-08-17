<?php
/**
* @package   jelix_calendar
* @subpackage admin
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class administrateursCtrl extends jController {
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_ADMINISTRATEURS) ;
        $oResp->body->assignZone('zContent', 'administrateurs~BoAdminListe') ;
		return $oResp ;
    }
	function edit() {
		$toParams = $this->params() ;
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_ADMINISTRATEURS) ;
        $oResp->body->assignZone('zContent', 'administrateurs~BoAdminEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ; 
        jClasses::inc('administrateurs~administrateursSrv');
        
        $oAdmin = administrateursSrv::save($toParams) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'admin~administrateurs:index' ;
        return $oResp ;
    }
	function delete() {
        jClasses::inc('administrateurs~administrateursSrv');
        administrateursSrv::delete($this->intParam('iAdminId', 0, true)) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'admin~administrateurs:index' ;
        return $oResp ;
    }
}

