<?php
/**
* @package   jelix_calendar
* @subpackage admin
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class utilisateursCtrl extends jController {
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_UTILISATEURS) ;
        $oResp->body->assignZone('zContent', 'utilisateurs~BoUtilisateursListe') ;
        return $oResp ;
    }
	function edit() {
		$toParams = $this->params() ;
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_UTILISATEURS) ;
        $oResp->body->assignZone('zContent', 'utilisateurs~BoUtilisateursEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ; 
        jClasses::inc('utilisateurs~utilisateursSrv');
        
        $oUtilisateurs = utilisateursSrv::save($toParams) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'admin~utilisateurs:index' ;
        return $oResp ;
    }
	function delete() {
        jClasses::inc('utilisateurs~utilisateursSrv');
        utilisateursSrv::delete($this->intParam('iUtilisateurId', 0, true)) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'admin~utilisateurs:index' ;
        return $oResp ;
    }
}

