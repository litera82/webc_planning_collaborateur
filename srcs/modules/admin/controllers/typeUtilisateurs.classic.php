<?php
/**
* @package   jelix_calendar
* @subpackage admin
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class typeUtilisateursCtrl extends jController {
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_TYPE_UTILISATEURS) ;
        $oResp->body->assignZone('zContent', 'utilisateurs~BoTypeListe') ;
        return $oResp ;
    }
	function edit() {
		$toParams = $this->params() ;
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_TYPE_UTILISATEURS) ;
        $oResp->body->assignZone('zContent', 'utilisateurs~BoTypeEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ; 
        jClasses::inc('utilisateurs~typesSrv');
        
        $oType = typesSrv::save($toParams) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'admin~typeUtilisateurs:index' ;
        return $oResp ;
    }
	function delete() {
        jClasses::inc('utilisateurs~typesSrv');
        typesSrv::delete($this->intParam('iTypeId', 0, true)) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'admin~typeUtilisateurs:index' ;
        return $oResp ;
    }
}



