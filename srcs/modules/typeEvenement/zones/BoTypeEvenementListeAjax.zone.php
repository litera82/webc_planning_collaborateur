<?php
/**
* @package      jelix_calendar
* @subpackage   typeEvenement
* @author       contact@webi-fy.net
*/

/**
* @desc Zone affichant la liste des typeEvenement en ajax
*/
class BoTypeEvenementListeAjaxZone extends jZone
{
    protected $_tplname = 'typeEvenement~BoTypeEvenementListeAjax.zone' ;

	private $_zSortField = 'typeevenements_iOrdre' ;
    private $_zSortDirection = 'ASC' ;
    private $_iCurrentPage = 1 ;
    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
    	
		jClasses::inc('typeEvenement~typeEvenementsSrv');
    	/*
    	$toParams = $this->getParam('toParams', array()) ;
    	$iRub = (isset($toParams['iRubrique']) && $toParams['iRubrique']>0) ? $toParams['iRubrique'] : 0 ; 
    		    	
    	*/
		$_toCriterias = array();
		$this->_zSortField		= $this->getParam('zSortField', $this->_zSortField) ;
        $this->_zSortDirection	= $this->getParam('zSortDirection', $this->_zSortDirection) ;
        $this->_iCurrentPage	= $this->getParam('iPage', $this->_iCurrentPage) ;
        $iNbParPage				= $this->getParam('iNbParPage', 1000) ;
		$iStart 				= ($this->_iCurrentPage > 1) ? ($this->_iCurrentPage - 1) * $iNbParPage : 0 ;

		$toResults = typeEvenementsSrv::listCriteria($_toCriterias, $this->_zSortField , $this->_zSortDirection , $iStart , $iNbParPage ) ;

    	foreach ($toResults['toListes'] as $oResults){
			$oResults->canUp = true;
			$oResults->canDown = true;
			if ($oResults->typeevenements_iOrdre == 1){
				$oResults->canUp = false;
			}
			if ($oResults->typeevenements_iOrdre == $toResults['iResTotal']){
				$oResults->canDown = false;
			}
		}

		$tzParams 						= array();
        $tzParams['zone'] 				= $this->getParam('zone', 'typeEvenement~BoTypeEvenementListeAjax');
        $toParams['toListes']			= $toResults['toListes'];
		$toParams['iNumListes']			= $toResults['iResTotal'];
		$toParams['iNbPages'] 			= 1 ;
        $toParams['iCurrentPage'] 		=  $this->_iCurrentPage ;
        $toParams['zSortField'] 		= $this->_zSortField ;
        $toParams['zSortDirection'] 	= $this->_zSortDirection ;
    	$toParams['tzParams'] 			= $tzParams ;
		$toParams['iNbParPage'] 		= $iNbParPage;
        $this->_tpl->assign($toParams) ;
    }

}