<?php 
/**
* @package      jelix_calendar
* @subpackage   Clientistrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone l'edition et la création d'un Clientistrateur
*/
class BoPostsEditZone extends jZone
{
    protected $_tplname = 'posts~BoPostsEdit.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	jClasses::inc('posts~wppostsSrv');
    	jClasses::inc('client~clientSrv');

		$id 						= $this->getParam('id',0);  
        $bEdit 						= ($id>0) ? true : false ;
        $oPosts 					= ($id>0) ? wppostsSrv::getPostsAndClient($id) : jDao::createRecord('commun~posts') ;

		$toListProjetDispo			= wppostsSrv::getListProjetDisponible();

		$toParams['bEdit'] 						= $bEdit ;
       	$toParams['id'] 						= $id ;
       	$toParams['oPosts'] 					= $oPosts ;
       	$toParams['toListProjetDispo'] 			= $toListProjetDispo;
       	$toParams['today'] 						= date('Y-m-d');

		$this->_tpl->assign($toParams);
	}

}