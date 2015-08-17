<?php
/**
* @package		reghalal
* @subpackage	espace
* @version		1
* @author		NEOV
*/

/**
* Zone de formulaire pour la recherche de test consommateur
* @package		reghalal
* @subpackage	espace
*/
class BoPostsRechercheZone extends jZone {

	protected $_tplname='posts~BoPostsRecherche.zone';
	protected $_useCache = false;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{

		$oCritere = $this->getParam("oCritere", "");
		$this->_tpl->assign('oCritere', $oCritere);
	}
}
?>