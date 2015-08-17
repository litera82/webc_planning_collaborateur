<?php
/**
* @package      jelix_calendar
* @subpackage   utilisateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des utilisateurs en ajax
*/
class BoUtilisateursListeAjaxZone extends jZone
{
    protected $_tplname = 'utilisateurs~BoUtilisateursListeAjax.zone' ;

	private $_zSortField = 'utilisateur_id' ;
    private $_zSortDirection = 'DESC' ;
    private $_iCurrentPage = 1 ;
    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	
		jClasses::inc('utilisateurs~utilisateursSrv');
    	
		$_toCriterias = array();
		$this->_zSortField		= $this->getParam('zSortField', $this->_zSortField) ;
        $this->_zSortDirection	= $this->getParam('zSortDirection', $this->_zSortDirection) ;
        $this->_iCurrentPage	= $this->getParam('iPage', $this->_iCurrentPage) ;
        $iNbParPage				= $this->getParam('iNbParPage', PAGINATION_NB_ITEM_PER_PAGE) ;
		$iStart 		= ($this->_iCurrentPage > 1) ? ($this->_iCurrentPage - 1) * $iNbParPage : 0 ;

		$toResults = utilisateursSrv::listCriteria($_toCriterias, $this->_zSortField , $this->_zSortDirection , $iStart , $iNbParPage ) ;
    	
		$tzParams 						= array();
        $tzParams['zone'] 				= $this->getParam('zone', 'utilisateurs~BoUtilisateursListeAjax');
        $toParams['toListes']			= $toResults['toListes'];
		$toParams['iNumListes']			= $toResults['iResTotal'];
		$toParams['iNbPages'] 			= 3 ;
        $toParams['iCurrentPage'] 		=  $this->_iCurrentPage ;
        $toParams['zSortField'] 		= $this->_zSortField ;
        $toParams['zSortDirection'] 	= $this->_zSortDirection ;
    	$toParams['tzParams'] 			= $tzParams ;
        $this->_tpl->assign($toParams) ;
    }

}