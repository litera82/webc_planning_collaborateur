<?php
/**
* @package   jelix_calendar
* @subpackage client
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class FoBlogCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;

	function getBlog (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->bodyTpl = "client~FoBlogListing" ;
		
    	jClasses::inc('client~clientSrv');
    	jClasses::inc('utilisateurs~utilisateursSrv');
    	jClasses::inc('utilisateurs~blogSrv');
		$toParams = array () ; 
		$toParams[0] = new StdClass(); 
		$toParams[0]->blog_utilisateurId = $this->param('blog_utilisateurId', 0, true);
		$toUtilisateur = utilisateursSrv::listCriteria(array());
		$toBlog = blogSrv::listCriteria($toParams);

		$oRep->body->assign('toUtilisateur', $toUtilisateur['toListes']);
		$oRep->body->assign('toBlog', $toBlog['toListes']);
		$oRep->body->assign('toParams', $toParams);
		return $oRep;
	} 
}