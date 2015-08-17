<?php 
/**
* @package      jelix_calendar
* @subpackage   utilisateur
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un utilisateur
*/
class BoUtilisateursEditZone extends jZone
{
    protected $_tplname = 'utilisateurs~BoUtilisateursEdit.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
		jClasses::inc('commun~toolDate') ;
    	jClasses::inc('utilisateurs~utilisateursSrv');
    	jClasses::inc('utilisateurs~plageHoraireSrv');
		jClasses::inc('utilisateurs~typesSrv');
    	jClasses::inc('client~paysSrv');
		jClasses::inc('utilisateurs~groupeSrv');
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('utilisateurs~utilisateursIndisponibiliteSrv');
        jClasses::inc('utilisateurs~utilisateursdisponibiliteSrv');
    	jClasses::inc('client~clientSrv');
		jClasses::inc('utilisateurs~blogSrv');

        $iUtilisateurId 					= $this->getParam('iUtilisateurId',0);  
        $bEdit 								= ($iUtilisateurId>0) ? true : false ;
        $oUtilisateurs 						= ($iUtilisateurId>0) ? utilisateursSrv::getById($iUtilisateurId) : jDao::createRecord('commun~utilisateurs') ;
		$_toCriterias						=  array();
		$toResultsType						= typesSrv::listCriteria($_toCriterias, "type_zLibelle" , "ASC" , 0 , 0 ) ;
		$toTypes							= $toResultsType['toListes'];
       	$toTmpPays							= paysSrv::chargerTous();
       	$toPlageHoraire						= plageHoraireSrv::chargerTous();
		$toTypeEvenements					= typeEvenementsSrv::listCriteria (NULL, 'typeevenements_zLibelle'); 
		$_toParamsUtilisateur = array() ;
		$_toParamsUtilisateur['utilisateur_statut'] = STATUT_PUBLIE ;
		$_toParamsUtilisateur['utilisateur_bSuperviseur'] = UTILISATEUR_SUPERVISEUR;
		$toUtilisateur						= utilisateursSrv::listCriteria ($_toParamsUtilisateur, 'utilisateur_zNom'); 
		$toTypeEvenementsUtilisateur		= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId) ;

		for ($i=0; $i<=sizeof($toTypeEvenements['toListes']); $i++){
			for ($j=0; $j<=sizeof($toTypeEvenementsUtilisateur); $j++){
				if (isset ($toTypeEvenements['toListes'][$i]) && isset($toTypeEvenementsUtilisateur[$j]) && $toTypeEvenements['toListes'][$i]->typeevenements_id == $toTypeEvenementsUtilisateur[$j]->typeevenements_id) {
					unset($toTypeEvenements['toListes'][$i]); 
				}
			}
		}
		$zlisteTypeEvenement = "" ;
		$iCpt = 0;
		foreach ($toTypeEvenementsUtilisateur as $oTypeEvenementsUtilisateur){
			if ($iCpt == 0){
				$zlisteTypeEvenement .= $oTypeEvenementsUtilisateur->typeevenements_id ;
			}else{
				$zlisteTypeEvenement .= "," . $oTypeEvenementsUtilisateur->typeevenements_id ;
			}
			$iCpt++ ;
		}

		$toGroupe							= groupeSrv::listCriteria (NULL, 'groupe_libelle'); 
		$toGroupeUtilisateur				= utilisateursSrv::getListeGroupeUtilisateur ($iUtilisateurId) ;

		for ($i=0; $i<=sizeof($toGroupe['toListes']); $i++){
			for ($j=0; $j<=sizeof($toGroupeUtilisateur); $j++){
				if (isset ($toGroupe['toListes'][$i]) && isset($toGroupeUtilisateur[$j]) && $toGroupe['toListes'][$i]->groupe_id == $toGroupeUtilisateur[$j]->groupe_id) {
					unset($toGroupe['toListes'][$i]); 
				}
			}
		}
		$zlisteGroupe = "" ;
		$iCpt1 = 0;
		foreach ($toGroupeUtilisateur as $oGroupeUtilisateur){
			if ($iCpt1 == 0){
				$zlisteGroupe .= $oGroupeUtilisateur->groupe_id ;
			}else{
				$zlisteGroupe .= "," . $oGroupeUtilisateur->groupe_id ;
			}
			$iCpt1++ ;
		}
/**********************************************************************/
		$toClients								= clientSrv::listCriteria (array()); 
		$_toParamsBlog = array ();
		$_toParamsBlog['blog_utilisateurId']	= $iUtilisateurId ;
		$toBlogUtilisateur						= blogSrv::listCriteria ($_toParamsBlog) ;

		for ($i=0; $i<=sizeof($toClients['toListes']); $i++){
			for ($j=0; $j<=sizeof($toBlogUtilisateur['toListes']); $j++){
				if (isset ($toClients['toListes'][$i]) && isset($toBlogUtilisateur['toListes'][$j]) && $toClients['toListes'][$i]->client_id == $toBlogUtilisateur['toListes'][$j]->blog_clientId) {
					unset($toClients['toListes'][$i]); 
				}
			}
		}
		$zlisteBlog = "" ;
		$iCpt2 = 0;
		foreach ($toBlogUtilisateur['toListes'] as $oBlogUtilisateur){
			if ($iCpt2 == 0){
				$zlisteBlog .= $oBlogUtilisateur->client_id ;
			}else{
				$zlisteBlog .= "," . $oBlogUtilisateur->client_id ;
			}
			$iCpt2++ ;
		}
/**********************************************************************/
       	$toParams['bEdit'] 							= $bEdit ;
       	$toParams['oUtilisateurs'] 					= $oUtilisateurs ;

		$toParams['toTypes'] 						= $toTypes ;
		$toParams['toPays'] 						= $toTmpPays;
		$toParams['toPlageHoraire']					= $toPlageHoraire;
		$toParams['toTypeEvenements']				= array_values($toTypeEvenements['toListes']);
		$toParams['toTypeEvenementsUtilisateur']	= $toTypeEvenementsUtilisateur;
		$toParams['zlisteTypeEvenement']			= $zlisteTypeEvenement;
		$toParams['toGroupe']						= array_values($toGroupe['toListes']);
		$toParams['toGroupeUtilisateur']			= $toGroupeUtilisateur;
		$toParams['zlisteGroupe']					= $zlisteGroupe;
		$toParams['toUtilisateur']					= $toUtilisateur['toListes'];

		$toParams['toClients']						= array_values($toClients['toListes']);
		$toParams['toBlogUtilisateur']				= $toBlogUtilisateur['toListes'];
		$toParams['zlisteBlog']						= $zlisteBlog;

		$this->_tpl->assign($toParams);
    }
}