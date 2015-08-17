<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires pour les nombres
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolNombre {
	
	private $tParams = array(
					'decimal_point',
					'thousands_sep',
					'grouping',
					'int_curr_symbol',
					'currency_symbol',
					'mon_decimal_point',
					'mon_thousands_sep',
					'mon_grouping',
					'positive_sign',
					'negative_sign',
					'int_frac_digits',
					'frac_digits',
					'p_cs_precedes',
					'p_sep_by_space',
					'n_cs_precedes',
					'n_sep_by_space',
					'p_sign_posn',
					'n_sign_posn'
			);

	/**
	* Récupération d'un format de nombre suivant la langue
	* 
	* @param int	 $_iNumber : nombre à formater
	* @param string  $_zLangue : langue de l'utilisateur
	* @return string	
	*/
	static function getNombreAuFormatLocale($_iNumber,$_zLangue="fr_FR") {
		
		//Récupération des langues autorisées
		$tLocales = self::getAvailableLanguageFromConf();
		
		//Exception si la langue n'a pas été trouvée
		if (!in_array($_zLangue,$tLocales)) {
			throw new jException("jelix~format.langue.unknown");
		} else {

			$Tool = new ToolNombre();
			foreach($Tool->tParams as $val) {
				$$val = jLocale::get("jelix~format.".$val,$_zLangue);
			}
			
			// Sign specifications:
			if ($_iNumber>0) {
				$sign		  = $positive_sign;
				$sign_posn	  = $p_sign_posn;
				$sep_by_space = $p_sep_by_space;
				$cs_precedes  = $p_cs_precedes;
			} else {
				$sign		  = $negative_sign;
				$sign_posn    = $n_sign_posn;
				$sep_by_space = $n_sep_by_space;
				$cs_precedes  = $n_cs_precedes;
			}
		
			// Number format:
			$n = number_format(abs($_iNumber), $frac_digits,
			$decimal_point, $thousands_sep);
			$n = str_replace(' ', '&nbsp;', $n);

			switch($sign_posn) {
				case 0:  $n = "($n)"; break;
				case 1:  $n = "$sign$n"; break;
				case 2:  $n = "$n$sign"; break;
				case 3:  $n = "$sign$n"; break;
				case 4:  $n = "$n$sign"; break;
				default: $n = "$n [error sign_posn=$sign_posn&nbsp;!]";
			}

			return $n;
		}
	}

	/**
	* Recupérer toutes les langues disponibles depuis la configuration de l'application
	* 
	* @return array
	*/
	static function getAvailableLanguageFromConf() {
		$zIniFile	  = JELIX_APP_CONFIG_PATH.'autolocale.coord.ini.php';
		$toIniContent = jIniFile::read($zIniFile);
		
		return explode(",",$toIniContent['availableLanguageCode']);
	}

	/**
	* Transforme un nombre dans une langue donnée en float
	* 
	* @param 	string $_zNumber	nombre formatté
	* @param 	string $_zLang 		langue de l'utilisateur
	* @return 	float
	**/
	static function setNombreEnFloat($_zNumber, $_zLang = "fr_FR"){

		//Récupération des langues autorisées
		$tLocales = self::getAvailableLanguageFromConf();
		
		//Exception si la langue n'a pas été trouvée
		if (!in_array($_zLang,$tLocales)) {
			throw new jException("jelix~format.langue.unknown");
		} else {

			$tKeys = array(	'decimal_point',
							'thousands_sep'
						);

			foreach($tKeys as $val) {
				$$val = jLocale::get("jelix~format.".$val, $_zLang);
			}

			if(strstr($_zNumber,$decimal_point)){
				$_zNumber = floatval(self::removeBlank(str_replace($decimal_point,".",$_zNumber)));
			}

			if(strstr($_zNumber,$thousands_sep)){
				$_zNumber = floatval(self::removeBlank(str_replace($thousands_sep,"",$_zNumber)));
			}
			return $_zNumber;
		}
	
	}

	/**
	* remove blank from variable
	* 
	* @param string $_var		chaine de caractères à nettoyer
	*/
	static function removeBlank($_var){
		return str_replace(' ', '', $_var);
	}
}
?>