<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Toky RABESON <t.rabeson@gmail.com>
*/

class FoAjoutSocieteZone extends jZone 
{
 
    protected $_tplname		= 'client~FoAjoutSociete.zone' ;
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

		$bEdit 						= false ;
		$this->_tpl->assign('bEdit', $bEdit);
	}
}
?>