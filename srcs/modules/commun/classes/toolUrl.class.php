<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires pour les urls
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolUrl {


	/**
	* formatage des URL : pas de lettres accentuées, pas d'espace, pas de caractère spéciaux
	* @param	string	$strTochange	à formatter
	* @return	string	$strResult 
	*/
	static function urlChange($strTochange){
		$character = array("é", " ", "è", "ê", "ë", "ô", "ö", "â", "ä", "à", "ù", "û", "ü", "ç", " ", "€",    "<", ">", "/", "\\", '"', "'", "\r", "\n", "+", "?","!",":",";",".",",","’","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","Œ","Ø","Š","Ù","Ú","Û","Ü","Ý","Ž","Þ", "À", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "«", "»"," - ");
		$codeNew =   array("e", "-", "e", "e", "e", "o", "o", "a", "a", "a", "u", "u", "u", "c", "-", "euro", "",  "",  "-", "-",  "",  "",  "-",  "",   "",  "", "", "-","", "", "", "", "a","a","a","a","a","a","c","e","e","e","e","e","i","i","i","i","d","n","o","o","o","o","o","0","s","u","u","u","u","y","z","",  "a", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "",  "", "-");

		$strResult = str_replace($character, $codeNew, $strTochange);

		return $strResult;
	}

	/**
	 * verification d'un url
	 *
	 * @param	string		$_zInputUrl		l'url à verifie
	 *
	 * @return	boolean
	 */
	 static function isValidUrl ($_zInputUrl) {
		  $zPattern = '^http(s)?://[a-zA-Z\\d-_]+\\.[a-zA-Z\\-_=&\\?%#:\\d\\./\\+\\*]*$' ;
		  $pattern1 = '^http(s)?://[-[:alnum:]]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(:[0-9]+)?$';
		  $pattern2 = '^^http(s)?://([a-zA-Z0-9-]+.)?([a-zA-Z0-9-]+.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(:[0-9]+)?(/[a-zA-Z0-9-]*/?|/[a-zA-Z0-9]+\.[a-zA-Z0-9]{1,4})?$';
		  
		  if( ereg($pattern2, $_zInputUrl) ){
			   return true;
		  }else{
			   return false;
		  }
	}
}
?>