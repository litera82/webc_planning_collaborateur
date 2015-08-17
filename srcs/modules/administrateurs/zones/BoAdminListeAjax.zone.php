<?php
/**
* @package      jelix_calendar
* @subpackage   administrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des administrateurs en ajax
*/
class BoAdminListeAjaxZone extends jZone
{
    protected $_tplname = 'administrateurs~BoAdminListeAjax.zone' ;

	private $_zSortField = 'admin_id' ;
    private $_zSortDirection = 'DESC' ;
    private $_iCurrentPage = 1 ;
    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	
		jClasses::inc('administrateurs~administrateursSrv');
    	/*
    	$toParams = $this->getParam('toParams', array()) ;
    	$iRub = (isset($toParams['iRubrique']) && $toParams['iRubrique']>0) ? $toParams['iRubrique'] : 0 ; 
    		    	
    	*/
		$_toCriterias = array();
		$this->_zSortField		= $this->getParam('zSortField', $this->_zSortField) ;
        $this->_zSortDirection	= $this->getParam('zSortDirection', $this->_zSortDirection) ;
        $this->_iCurrentPage	= $this->getParam('iPage', $this->_iCurrentPage) ;
        $iNbParPage				= $this->getParam('iNbParPage', PAGINATION_NB_ITEM_PER_PAGE) ;
		$iStart 		= ($this->_iCurrentPage > 1) ? ($this->_iCurrentPage - 1) * $iNbParPage : 0 ;

		$toResults = administrateursSrv::listCriteria($_toCriterias, $this->_zSortField , $this->_zSortDirection , $iStart , $iNbParPage ) ;
    	
		$tzParams 		= array();
        $tzParams['zone'] 				= $this->getParam('zone', 'administrateurs~BoAdminListeAjax');
        $toParams['toListes'] = $toResults['toListes'];
		$toParams['iNumListes'] = $toResults['iResTotal'];
		$toParams['iNbPages'] 			= 1 ;
        $toParams['iCurrentPage'] 		=  $this->_iCurrentPage ;
        $toParams['zSortField'] 		= $this->_zSortField ;
        $toParams['zSortDirection'] 	= $this->_zSortDirection ;
    	$toParams['tzParams'] 			= $tzParams ;

        $this->_tpl->assign($toParams) ;
    }

}