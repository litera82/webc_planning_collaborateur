<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class BoLeftZone extends jZone 
{
 
    protected $_tplname		= 'commun~BoLeft.zone' ;
	protected $_useCache	= false ;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{
          
		$tiMenusActifs = $this->getParam('tiMenusActifs') ;

		$tzMenus = array() ;
        
		array_push($tzMenus, array('Administrateurs', jurl::get('admin~administrateurs:index'),'_self')) ;
		array_push($tzMenus, array('Types d\'utilisateurs', jurl::get('admin~typeUtilisateurs:index'),'_self')) ;
		//array_push($tzMenus, array('Utilisateurs', jurl::get('admin~utilisateurs:index'),'_self')) ;

		array_push($tzMenus, 
			array('Collaborateurs','#','_self', 
				array(
					array(
						'Liste', jurl::get('admin~utilisateurs:index'),'_self'
					),
					array(
						'Disponibilités', jurl::get('evenement~evenement:disponibilite'),'_self'
					),
					array(
						'Planning par email', jurl::get('evenement~evenement:planingprofparemail'),'_self'
					)
				)
			)
		) ;

		array_push($tzMenus, array('Types d\'événements', jurl::get('typeEvenement~typeEvenement:index'),'_self')) ;
		//array_push($tzMenus, array('Clients', jurl::get('client~client:index'),'_self')) ;
		array_push($tzMenus, 
			array('Projets','#','_self', 
				array(
					array(
						'Liste', jurl::get('client~client:index'),'_self'
					),
					array(
						'Import XML', jurl::get('client~client:pageImportXmlData'),'_self'
					),
					array(
						'Import BDD logevent', jurl::get('client~client:pageImportBddLogEvent'),'_self'
					)
				)
			)
		) ;
		array_push($tzMenus, array('Evénements', jurl::get('evenement~evenement:index'),'_self')) ;
		array_push($tzMenus, array('Import Données', '#', '_self', array(array('XML stagiaires', jurl::get('auto~import:importXmlStagiaire'), '_self'))));
		array_push($tzMenus, 
					array('Log Event', '#', '_self', 
						array(
							array('Par défaut', jurl::get('logevent~logevent:index'),'_self'),
							array('Avec paramètres', jurl::get('logevent~logevent:logEventWithParam'),'_self')	
							 )
						 )
				  );
		array_push($tzMenus, 
					array('Posts', '#', '_self', 
						array(
							array('Liste', jurl::get('posts~posts:index'),'_self'),
							array('Générer projets / posts', jurl::get('posts~posts:pagePostProjet'),'_self'),
							array('Dedoublonner posts', jurl::get('posts~posts:pageDedoublonnerPost'),'_self')
							 )
						 )
				  );
		//array_push($tzMenus, array('Créer les disponibilités', jurl::get('evenement~evenement:disponibilite'),'_self')) ;
		//array_push($tzMenus, array('Planning par email', jurl::get('evenement~evenement:planingprofparemail'),'_self')) ;

		$tzMenus		= json_encode($tzMenus) ;
		$tiMenusActifs	= json_encode($tiMenusActifs) ;	
		
		$this->_tpl->assign('tzMenus', $tzMenus) ;
		$this->_tpl->assign('tiMenusActifs', $tiMenusActifs) ;
	}
}
?>