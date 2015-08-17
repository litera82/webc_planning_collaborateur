<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoAjoutClientZone extends jZone 
{
 
    protected $_tplname		= 'client~FoAjoutClient.zone' ;
	protected $_useCache	= false ;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{
		jClasses::inc ('commun~toolDate') ;
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc ('typeEvenement~typeEvenementsSrv');
    	jClasses::inc ('client~clientSrv');
    	jClasses::inc ('client~societeSrv');
    	jClasses::inc ('client~paysSrv');

 
		$iEvenementId 				= $this->getParam('iEvenementId',0);  
		$iClientId 					= $this->getParam('iClientId',0);  
		$oUser = jAuth::getUserSession();
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::getById ($iUtilisateurId) ;
		$toProfesseur['toListes'] = array () ;
		array_push ($toProfesseur['toListes'], $oUtilisateur);
		if (isset($oUtilisateur->utilisateur_bSuperviseur) && $oUtilisateur->utilisateur_bSuperviseur == UTILISATEUR_SUPERVISEUR){
			$toParamsUtilisateur['utilisateur_statut'] = 1;
			$toParamsUtilisateur['notinutilisateur'] = $iUtilisateurId;
			$toTmpProfesseur = utilisateursSrv::listCriteria($toParamsUtilisateur, 'utilisateur_zPrenom');
			//$toTmpProfesseur = utilisateursSrv::getUtilisateurBySuperviseurId($iUtilisateurId) ;
			foreach($toTmpProfesseur['toListes'] as $oProfesseur){
				array_push ($toProfesseur['toListes'], $oProfesseur);
			}
		}

		$_toParamsUtilisateur['utilisateur_statut'] = 1;
       	$toTmpUtilisateur			= utilisateursSrv::listCriteria($_toParamsUtilisateur);
		$bEdit 						= ($iClientId>0) ? true : false ;
        $oClient 					= ($iClientId>0) ? ClientSrv::getById($iClientId) : jDao::createRecord('commun~client') ;
		$toParamsSociete = array ();
		$toParamsSociete[0] = new stdClass();
		$toParamsSociete[0]->statut = 1;
       	$toTmpSociete				= societeSrv::listCriteria($toParamsSociete);
       	$toTmpPays					= paysSrv::chargerTous();
		$toParams['bEdit'] 			= $bEdit ;
       	$toParams['iClientId'] 		= $iClientId ;
       	$toParams['oClient'] 		= $oClient ;
		$toParams['toSociete'] 		= $toTmpSociete['toListes'];
		$toParams['toPays'] 		= $toTmpPays;
		$toParams['iUtilisateurId'] = $iUtilisateurId;
		$toParams['toUtilisateur'] 	= $toTmpUtilisateur['toListes'];
		$toParams['toProfesseur'] 	= $toProfesseur['toListes'];
		$toParams['oUtilisateur'] 	= $oUtilisateur;

		$this->_tpl->assign($toParams);
	}
}
?>