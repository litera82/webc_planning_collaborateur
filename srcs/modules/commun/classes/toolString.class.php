<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires pour les chaînes de caractères
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolString {

	
	/**
	* Remplace les caractères accentués en simples caractères
	*
	* @param	string	$string	Chaine à traiter
	* @return	string	$string			Chaine sans accent
	*/
	static function remplacerAccent($string) {
		return strtr($string, "ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêë
		ìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeei
		iiionoooooouuuuyy");
	}


	/**
	* Remplace les caractères spéciaux par _
	*
	* @param	string	$_zString	Chaine à traiter
	* @return	string				Chaine enlevée des caractères spéciaux
	*/
	static function remplacerSpecialChars($_zString) {
		return strtr($_zString, "?,;.:/!�&#{[(|\\@)]=+}\$�*%><\"'", "_______________________________");
	} 


	/**
	* Génère un hashcode
	*
	* @return String  $zHashcode La chaine code généré
	*/
	static function generateHashCode()	{
	   $zHashcode = "";
	   srand(microtime());
	   for ($iC0=0; $iC0<20; $iC0++) {
			if (58<=($iElt=rand(48,90)) && $iElt<=64) {
			   $iC0--;
			   continue;
			}
	      $zHashcode .= chr($iElt);
		}
		return $zHashcode;
	}



	/**
	* Vérifie si une chaine est vide
    *
	* @param 	string		$_zString
	* @return 	boolean
	*/
	public static function isEmpty($_zString) 
	{
		$zValue = strip_tags($_zString);
		
		if (strlen(trim($zValue)) == 0) {
			return true;
		} else {
		    return false;
		}
	}

	/**
	* Nettoyer une chaine 
	* Supprime les balises HTML et PHP
    *
	* @param  string	$_zString	la chaine à nettoyer
	* @return string 				La chaîne traitée
	*/
	public static function stringProtect($_zString) 
	{
		if(is_string($_zString)) {
			return strip_tags($_zString);
		}else{
			return "";
		}
	}
	
	/**
	* Nettoyer une chaine
	* Enlever les retours chariots
	* 
	* @param 	string	$_zStr	la chaine à nettoyer
	* @return 	string			La chaîne traitée
    */
	static function nettoyerString ($_zStr)
	{
		$zRet = trim($_zStr) ;
		$zRet = str_replace (chr (13) . chr (10), chr (10), $zRet) ;
		$zRet = str_replace (chr (13), chr (10), $zRet) ;
		return $zRet ;
	}
}
?>