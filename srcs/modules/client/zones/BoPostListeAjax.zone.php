<?php
/**
* @package      jelix_calendar
* @subpackage   Clientistrateurs
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des Clientistrateurs en ajax
*/
class BoPostListeAjaxZone extends jZone
{
    protected $_tplname = 'client~BoPostListeAjax.zone' ;

	private $_zSortField = 'ID' ;
    private $_zSortDirection = 'DESC' ;
    private $_iCurrentPage = 1 ;
    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
		if ($this->getParam('iParPage')) 
		{
			$this->iParPage = $this->getParam('iParPage',PAGINATION_NB_ITEM_PER_PAGE);
		}
		else
		{
			$this->iParPage = PAGINATION_NB_ITEM_PER_PAGE ; 
		}		
    	
		jClasses::inc('client~PostSrv');

		$this->_zSortField		= $this->getParam('zSortField', $this->_zSortField) ;
        $this->_zSortDirection	= $this->getParam('zSortDirection', $this->_zSortDirection) ;
        $this->_iCurrentPage	= $this->getParam('iPage', $this->_iCurrentPage) ;
        $iNbParPage				= $this->getParam('iNbParPage', PAGINATION_NB_ITEM_PER_PAGE) ;
		$iStart 				= ($this->_iCurrentPage > 1) ? ($this->_iCurrentPage - 1) * $iNbParPage : 0 ;
		$_toCriterias			= array($this->getParam('oCritere')) ;
		//print_r($_toCriterias); die("BoPostListeAjaxZone");
		$toResults = PostSrv::listCriteria($_toCriterias, $this->_zSortField , $this->_zSortDirection) ;
		
		//Pagination PAGINATION_NBITEMPARPAGE
		$iNbPage = ceil(sizeof($toResults['toListes']) / $this->iParPage );
		$toResult = array_slice($toResults['toListes'], $this->iParPage*($this->_iCurrentPage-1), $this->iParPage);

		$tzParams 		= array();
        $tzParams['zone'] 				= $this->getParam('zone', 'client~BoPostListeAjax');
        $toParams['toListes']			= $toResult;
		$toParams['iNumListes']			= $toResults['iResTotal'];
		$toParams['iNbrTotal']			= $toResults['iResTotal'];
		$toParams['iNbPages'] 			= $iNbPage ;
		$toParams['iParPage'] 			= $this->iParPage ;
        $toParams['iCurrentPage'] 		= $this->_iCurrentPage ;
        $toParams['zSortField'] 		= $this->_zSortField ;
        $toParams['zSortDirection'] 	= $this->_zSortDirection ;
        $toParams['oCritere'] 			= $_toCriterias ;
    	$toParams['tzParams'] 			= $tzParams ;

        $this->_tpl->assign($toParams) ;
    }

}