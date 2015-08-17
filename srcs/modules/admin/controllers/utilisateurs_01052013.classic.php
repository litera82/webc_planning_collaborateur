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
		global $gJConfig ;
		$toParams = $this->params() ;
        $oResp = $this->getResponse('BoHtml') ;
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/jquery-1.9.1.min.js');

		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/jquery.multiselects.js');
		//$oResp->addCSSLink ('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/redmond/jquery-ui.css');
		//$oResp->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/back/css/jquery.ptTimeSelect.css');
		//$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/jquery.ptTimeSelect.js');

		$oResp->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/fullcalendar.css');
		//$oResp->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/fullcalendar.print.css');
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/jquery-ui-1.10.2.custom.min.js');
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/fullcalendar.js');

		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_UTILISATEURS) ;
        $oResp->body->assignZone('zContent', 'utilisateurs~BoUtilisateursEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ; 
        jClasses::inc('utilisateurs~utilisateursSrv');
        jClasses::inc('utilisateurs~utilisateursIndisponibiliteSrv');

		$oUtilisateurs = utilisateursSrv::save($toParams) ;
		// save utilisateursindisponibilite
//print_r($toParams) ;die;
		$toInfos['id']							=	$toParams['id'] ;
		$toInfos['utilisateur_id']				=	$oUtilisateurs->utilisateur_id ;
		$toInfos['lundi_debut_matin']			=	$toParams['lundi_debut_matin'] ;
		$toInfos['lundi_fin_matin']				=	$toParams['lundi_fin_matin'] ;
		$toInfos['lundi_debut_apres_midi']		=	$toParams['lundi_debut_apres_midi'] ; 
		$toInfos['lundi_fin_soir']				=	$toParams['lundi_fin_soir'] ; 
		$toInfos['mardi_debut_matin']			=	$toParams['mardi_debut_matin'] ; 
		$toInfos['mardi_fin_matin']				=	$toParams['mardi_fin_matin'] ; 
		$toInfos['mardi_debut_apres_midi']		=	$toParams['mardi_debut_apres_midi'] ; 
		$toInfos['mardi_fin_soir']				=	$toParams['mardi_fin_soir'] ; 
		$toInfos['mercredi_debut_matin']		=	$toParams['mercredi_debut_matin'] ; 
		$toInfos['mercredi_fin_matin']			=	$toParams['mercredi_fin_matin'] ; 
		$toInfos['mercredi_debut_apres_midi']	=	$toParams['mercredi_debut_apres_midi'] ; 
		$toInfos['mercredi_fin_soir']			=	$toParams['mercredi_fin_soir'] ; 
		$toInfos['jeudi_debut_matin']			=	$toParams['jeudi_debut_matin'] ; 
		$toInfos['jeudi_fin_matin']				=	$toParams['jeudi_fin_matin'] ; 
		$toInfos['jeudi_debut_apres_midi']		=	$toParams['jeudi_debut_apres_midi'] ; 
		$toInfos['jeudi_fin_soir']				=	$toParams['jeudi_fin_soir'] ; 
		$toInfos['vendredi_debut_matin']		=	$toParams['vendredi_debut_matin'] ; 
		$toInfos['vendredi_fin_matin']			=	$toParams['vendredi_fin_matin'] ; 
		$toInfos['vendredi_debut_apres_midi']	=	$toParams['vendredi_debut_apres_midi'] ; 
		$toInfos['vendredi_fin_soir']			=	$toParams['vendredi_fin_soir'] ;

		$oUtilisateursIndisponibilite = utilisateursIndisponibiliteSrv::save($toInfos) ;
		
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

