<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoLegendeZone extends jZone 
{
 
    protected $_tplname		= 'commun~FoLegende.zone' ;
	protected $_useCache	= true ;
    protected $_cacheTimeout = 3600; 

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{
        jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

		//Type d'evenement (Légende)
       	$oParamsTypeevent				= new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvenement					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvenement) && sizeof ($toTypeEvenement) > 0){
			$oTypeEvenement = array();
			$oTypeEvenement['iResTotal'] = sizeof ($toTypeEvenement) ;
			$oTypeEvenement['toListes']  = $toTypeEvenement ;
		}else{
			$oTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

		//$toLegende = typeEvenementsSrv::listCriteria($oParamsTypeevent);
		$this->_tpl->assign('toLegende', $oTypeEvenement['toListes']); 
	}
}
?>