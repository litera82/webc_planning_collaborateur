<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoHeaderZone extends jZone 
{
 
    protected $_tplname		= 'commun~FoHeader.zone' ;
	protected $_useCache	= false ;
    protected $_cacheTimeout = 3600; 

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$tDate		= $this->getParam('tDate');  
		$iAffichage	= $this->getParam('iAffichage');  

		//Date Année Hrader 
		$iAnnee = date('Y');
		$iMounth = date('m');
		$iDay = date('d');

		$tiAnnee = array ();
		for ($i=$iAnnee-10; $i<=$iAnnee+20; $i++){
			array_push ($tiAnnee, $i);
		}
		$this->_tpl->assign('oUtilisateur', $oUtilisateur);
		$this->_tpl->assign('tiAnnee', $tiAnnee);
		$this->_tpl->assign('iAnnee', $iAnnee);
		$this->_tpl->assign('iMounth', $iMounth);
		$this->_tpl->assign('iDay', $iDay);
		$this->_tpl->assign('tDate', $tDate);
		$this->_tpl->assign('iAffichage', $iAffichage);


	}
}
?>