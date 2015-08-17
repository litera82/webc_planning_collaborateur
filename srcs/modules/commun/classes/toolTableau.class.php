<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires pour les tableaux
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolTableau {

	/**
	* Dédoublonne un Objet/tableau associatif et/ou indexé (comme array_unique)
	*
	* @param	array		&$_tVar		référence au Tableau d'Objets unidimensionnel ou tableau simple bidimensionnel
	* @param	boolean		$_bAssoc	identifie si tenir compte des cles/index (TRUE) ou par valeur uniquement (FALSE)
	* 
	* @return	array		$tDoubles	Tableau contenant les éléments en double qu'on a supprimés du tableau en param
	* 					
	*/
	static function dedoublonne(&$_tVar,$_bAssoc=TRUE)	{
	   $tDoubles = array();
	   $tTemp = $_tVar;
		foreach ($_tVar as $key=>$value) { // prendre un élément à comparer aux autres
			$iVType = gettype($value);
			if ($iVType=="array"){
		      $value_ = implode("-",array_values($value));
			} elseif ($iVType=="object") {
		      $value_ = implode("-",self::getElts($value, FALSE));
			}
	      foreach ($tTemp as $key1=>$value1) { // comparaison de l'élément avec tous les autres
			   if ($iVType=="array"){
			      $value1_ = implode("-",array_values($value1));
				} elseif ($iVType=="object") {
			      $value1_ = implode("-",self::getElts($value1, FALSE));
				}
				if ($value_==$value1_) {
				   if (!$_bAssoc || ($_bAssoc && $key!=$key1)) {
						$tDoubles[$key] = $_tVar[$key];
						unset($_tVar[$key]);
						unset($tTemp[$key]);
						break;
					}
				}
			}
		}
		return $tDoubles;
	}  // FIN : dedoublonne()

	/**
	* Récupère dans un tableau toutes les (propriétés ou valeurs) d'un Objet
	*
	* @param	object	$_tObj		Objet dont les prop ou valeurs sont à recuperer
	* @param	boolean	$_bProp		Indique si recuperer les proprietes (TRUE: par défaut) ou les valeurs (FALSE)
	* 
	* @return	string	$tReturn	La chaine résultante
	*/
	static function getElts($_tObj, $_bProp=TRUE)	{
		$tReturn = array();
		foreach ($_tObj as $key=>$value) {
		   if ($_bProp) { // récupérer les propriéts
				array_push($tReturn, $key);
			} else {
				array_push($tReturn, $value);
			}
		}
		return $tReturn;
	} //FIN : getElts()



	/**
	* Transforme un objet en un tableau dont les clés sont les propriétés de l'objet
	*
	* @param	object	$_oFrom  Objet de départ
	* 
	* @return	array	$tTo     Tableau d'arrivée
	*/
	static function object2Array($_oFrom) {
	   $tTo = array();
	   foreach ($_oFrom as $zProp) {
	      $tTo[$zProp] = $_oFrom->$zProp;
		}
		return $tTo;
	}  //	FIN : object2Array()

	/**
	* Fonction de suppression d'éléments d'un tableau unidimensionnel
	*
	* @param	array		&$_tArray	Référence du tableau duquel on veut supprimer les éléments
	* @param	array		$_tRem		Tableau des éléments à supprimer
	* @param	boolean		$_bInd		Booleen indiquant si on veut supprimer par index (TRUE) ou par clé (FALSE)
	*/
	static function remFromArray(&$_tArray, $_tRem, $_bInd=TRUE) {
	   $iRem = count($_tRem);
		if ($_bInd) {
		   for ($iC0=0; $iC0< $iRem; $iC0++) {
		      array_splice($_tArray, $_tRem[$iC0], 1);
			}
		} else {
		   for ($iC0=0; $iC0< $iRem; $iC0++) {
		      $key = array_search($_tRem[$iC0], $_tArray);
		      unset($_tArray[$key]);
			}
		}
	}  // FIN : remFromArray

	/**
	* Ramasse les valeurs d'un tableau 2d dans un autres selon clés données
	*
	* @param 	array	$_tInput 	Tableau d'entrée, de laquelle les valeurs sont à prendre
	* @param 	string	$_zKeysList Chaine listant (séparateur = "-") les clés à voir
	* 
	* @return	array 	$tReturn 	Tableau 2d contenant les valeurs prises
	*/
	static function getFromAAByKeys($_tInput, $_zKeysList="") {
	   $iInput = count($_tInput);
	   $tReturn = $_tInput;
	   if ($_zKeysList!="") {
	      $tKeys = explode('-',$_zKeysList);
	      foreach ($tKeys as $key) {
	         for ($iC0=0; $iC0<$iInput; $iC0++) {
	            $tReturn[$iC0][$key] = $_tInput[$iC0][$key];
				}
			}
		}
		return $tReturn;
	}  //	FIN : getFromAAByKeys()

	/**
	* Ramasse les valeurs d'un tableau d'objets dans un autres selon proprités données
	*
	* @param 	array		$_tInput 	Tableau d'entrée, depuis lequel les valeurs sont à prendre
	* @param 	string	$_zPropList Chaine listant (séparateur = "-") les propriétés à voir
	* 
	* @return	array 	$tReturn 	Tableau 2d contenant les valeurs prises
	*/
	static function getFromAOByKeys($_tInput, $_zPropList="") {
	   if ($_zPropList!="") {
	   	$tReturn = array();
	      $tProp = explode('-',$_zPropList);
	      if (count($tProp)==1) {
	         $iC0 = 0;
	      	while (isset($_tInput[$iC0]) && is_object($_tInput[$iC0])) {
	      	   $tReturn[$iC0] = $_tInput[$iC0]->$_zPropList;
					$iC0++;
				}
			} else {
		      foreach ($tProp as $prop) {
		      	for ($iC0=0; $iC0<$iInput; $iC0++) {
		            $tReturn[$iC0][$prop] = $_tInput[$iC0]->$prop;
					}
				}
			}
		} else {
	   	$tReturn = $_tInput;
		}
		return $tReturn;
	}  // FIN : getFromAOByKeys()

	/**
	* Transforme un tableau en un objet
	*
	* @param 	array		$_tArray 	Tableau d'entrée, depuis lequel les valeurs sont à prendre
	* 
	* @return	array 	$oRet 		Objet obtenu
	*/
	static function array2Object($_tArray) {
	   $oRet = new StdClass();
	   $tKeys = array_keys($_tArray);
	   foreach ($tKeys as $key) {
	      $oRet->$key = $_tArray[$key];
		}
		return $oRet;
	}


	/**   
	 * Retourne les propriétés d'un objet
	 * 
	 * @param	object	$_oObj		Nom de l'objet
	 * 
	 * @return  array	$tzProp		Liste des proprietes de l'objet donne
	 **/
	static function getProperties($_oObj)
	{
		$tzProp = array();
		if (is_object($_oObj)) {
			$tzProp = get_object_vars($_oObj);
		}
		return $tzProp;
	}


	/**
	* Recherche l'occurrence d'un élément quelconque dans un Array
	* 
	* @param	mixed	$_oNeedle	L'élément à trouver
	* @param	array	$_tArray	Le tableau à explorer
	* 
	* @return	mixed	$oKey		La clé (ou index) de l'occurrence, sinon -1
	*/
	static function findInArray ($_oNeedle, $_tArray)
	{
		$iArray = count ($_tArray) ;
	    if (is_object ($_oNeedle))
	    {
	        foreach ($_tArray as $zKey => $zVal )
	        {
				if (is_object ($zVal) && $zVal === $_oNeedle)
				{
				    return $zKey ;
				}
	        }
	    }
		else
		{
	        foreach ($_tArray as $zKey => $zVal )
	        {
				if ($zVal == $_oNeedle)
				{
				    return $zKey ;
				}
	        }
		}
		return -1 ;
	}

	/**
	* fonction permettant de controler
	* les données venant d'un formulaire
	* prises directement avec param
	* ex : les select multiple
	* @param	array		$_tTab		tableau contenant les données
	* @param	int		$_iFiltre	entier qui filtre le type de données :
	*	1 pour numerique/ 2 pour chaine de caractère
	* @return	array	$tTab	tableau contrôlé
	*/
	static function controleData($_tTab , $_iFiltre){
		$tTab = array();
		for ($i=0; $i<sizeof($_tTab); $i++){
			// ajouter controle de la saisie dans la mesure où on est pas protéger de l'injection par un getparam particulier
			switch($_iFiltre){
				case 1 :
					if(isset($_tTab[$i]) && ($_tTab[$i]!=0)) {
						if (is_numeric($_tTab[$i])) {
							$tTab[$i] = intval($_tTab[$i]);
						}else{
							$tTab[$i] = null;
						}
					}
					break;

				case 2 :
					//Chaine de caractère
					$tKey = array_keys($_tTab);
					if (isset($tTab[$i]) && is_string($tTab[$i]) && $i == $tKey[0]) {
						$tTab[$i] = strip_tags($_tTab[$i]);
					}else{
						$tTab[$i] = null;
					}
					break;
			}
		}
		
		return $tTab ;
	}
	
	
	
}
?>