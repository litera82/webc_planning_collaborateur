<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoFooterZone extends jZone 
{
 
    protected $_tplname		= 'commun~FoFooter.zone' ;
	protected $_useCache	= false ;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{
		$oUser = jAuth::getUserSession();
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);
		
		$this->_tpl->assign('oUtilisateur', $oUtilisateur);
	}
}
?>