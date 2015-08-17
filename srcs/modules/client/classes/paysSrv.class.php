<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage societe
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class paysSrv
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~pays') ;
		return $oFac->get($_iId) ;
	}
	
	/**
	 * Creation d'un tableau d'objet selon critère
	 * @param array $_toParams tableau des parametres
	 * @param string $_zSortedField champ de trie (colone d'une table mysql)
	 * @param string $_zSortedDirection direction du trie
	 * @param int $_iStart premier enregistrement
	 * @param int $_iOffset nombre d'enregistrement affiché
	 *  @return array
	 */
	static function chargerTous($_zSortField="pays_zNom", $_zSortDirection="ASC") 
	{

		$oDao = jDao::get('commun~pays') ;
		
		$oConditions = jDao::createConditions();
		$oConditions->addItemOrder($_zSortField, $_zSortDirection);
		
		$oDao = $oDao->findBy($oConditions);
        $toPays = $oDao->fetchAll();
		
		return $toPays;
	}

	
}