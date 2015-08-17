<?php
/**
* @package		reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions métiers pour les parametres d'affichage
* @package		reghalal
* @subpackage	commun
*/

@ini_set ("memory_limit", -1) ;
class reghalal_parametreSrv{

	/**
	* Chargement des parametres existants dans la base
	* @return	array	$toParametre	
	*/
	static function chargeTous()
	{
		$oDao = jDao::get("commun~reghalal_parametre");
		
		$oDao			= $oDao->findAll();
        $toParametre	= $oDao->fetchAll();

		return $toParametre[0];
	}

	/**
	* Charge une sousRubrique par son identifiant
	* @param	object	$_oParametre
	*/
	static function modifie ($_oParametre){

		$oFactory = jDao::create('commun~reghalal_parametre');

		$oUpdateRecord = $oFactory->get ($_oParametre->parametre_id);

		$oUpdateRecord->parametre_id					= $_oParametre->parametre_id;
		$oUpdateRecord->parametre_optionTestConsomateur	= $_oParametre->parametre_optionTestConsomateur;
		$oUpdateRecord->parametre_optionRecette			= $_oParametre->parametre_optionRecette;
		
		$oFactory->update ($oUpdateRecord);

	}
	
}
?>