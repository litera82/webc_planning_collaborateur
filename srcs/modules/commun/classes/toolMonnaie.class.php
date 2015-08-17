<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires pour les formats monétaires
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolMonnaie {
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
	* Fonction de php displayLocales
	* version originale
	* @link http://php.net/manual/fr/function.localeconv.php
	*/
	/*static function getNombreAuFormatLocaleOriginal($number, $isMoney, $lg='fr_FR.utf8') {
	    $ret = setLocale(LC_ALL, $lg);
	    setLocale(LC_TIME, 'Europe/Paris');
	    if ($ret===FALSE) {
	        echo "Language '$lg' is not supported by this system.\n";
	        return;
	    }
	    $LocaleConfig = localeConv();
	    
	    forEach($LocaleConfig as $key => $val) $$key = $val;
	
	    // Sign specifications:
	    if ($number>0) {
	        $sign = $positive_sign;
	        $sign_posn = $p_sign_posn;
	        $sep_by_space = $p_sep_by_space;
	        $cs_precedes = $p_cs_precedes;
	    } else {
	        $sign = $negative_sign;
	        $sign_posn = $n_sign_posn;
	        $sep_by_space = $n_sep_by_space;
	        $cs_precedes = $n_cs_precedes;
	    }
	
	    // Number format:
	    $n = number_format(abs($number), $frac_digits,
	    $decimal_point, $thousands_sep);
	    $n = str_replace(' ', '&nbsp;', $n);
	    switch($sign_posn) {
	        case 0: $n = "($n)"; break;
	        case 1: $n = "$sign$n"; break;
	        case 2: $n = "$n$sign"; break;
	        case 3: $n = "$sign$n"; break;
	        case 4: $n = "$n$sign"; break;
	        default: $n = "$n [error sign_posn=$sign_posn&nbsp;!]";
	    }
	
	    // Currency format:
	    $m = number_format(abs($number), $frac_digits,
	        $mon_decimal_point, $mon_thousands_sep);
	    if ($sep_by_space) $space = ' '; else $space = '';
	    if ($cs_precedes) $m = "$currency_symbol$space$m";
	    else $m = "$m$space$currency_symbol";
	    $m = str_replace(' ', '&nbsp;', $m);
	    switch($sign_posn) {
	        case 0: $m = "($m)"; break;
	        case 1: $m = "$sign$m"; break;
	        case 2: $m = "$m$sign"; break;
	        case 3: $m = "$sign$m"; break;
	        case 4: $m = "$m$sign"; break;
	        default: $m = "$m [error sign_posn=$sign_posn&nbsp;!]";
	    }
	    if ($isMoney) return $m; else return $n;
	}*/

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
	* Récupération d'un format monétaire suivant la langue
	* 
	* @param	int		$_iNumber	nombre à formater
	* @param	string  $_zLangue	langue de l'utilisateur
	* @return	string
	*/
	static function getMonnaieAuFormatLocale($_iNumber, $_zLangue='en_EN') {
		
		//Récupération des langues autorisées
		jClasses::inc("commun~toolNombre");
		$tLocales = self::getAvailableLanguageFromConf();
		
		//Exception si la langue n'a pas été trouvée
		if (!in_array($_zLangue,$tLocales)) {
			throw new jException("jelix~format.langue.unknown");
		} else {

			$Tool = new ToolMonnaie();
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
		
			// Currency format:
			$m = number_format(abs($_iNumber), $frac_digits,
				$mon_decimal_point, $mon_thousands_sep);
			if ($sep_by_space) $space = ' '; else $space = '';
			if ($cs_precedes) $m = "$currency_symbol$space$m";
			else $m = "$m$space$currency_symbol";
			$m = str_replace(' ', '&nbsp;', $m);

			switch($sign_posn) {
				case 0: $m = "($m)"; break;
				case 1: $m = "$sign$m"; break;
				case 2: $m = "$m$sign"; break;
				case 3: $m = "$sign$m"; break;
				case 4: $m = "$m$sign"; break;
				default: $m = "$m [error sign_posn=$sign_posn&nbsp;!]";
			}

			return $m;
		}
	}

	/**
	* Transforme la monnaie dans une langue donnée en float
	*
	* @param	string	$_zMonnaie 	monnaie formatée
	* @param	string	$_zLang 	langue de l'utilisateur
	* @return 	float
	**/
	static function setMonnaieEnFloat($_zMonnaie, $_zLang = "fr_FR"){
		jClasses::inc("commun~toolNombre");
		//Récupération des langues autorisées
		$tLocales = toolNombre::getAvailableLanguageFromConf();
		
		//Exception si la langue n'a pas été trouvée
		if (!in_array($_zLang,$tLocales)) {
			throw new jException("jelix~format.langue.unknown");
		} else {

			$tKeys = array(	'mon_decimal_point',
							'mon_thousands_sep'
						);

			foreach($tKeys as $val) {
				$$val = jLocale::get("jelix~format.".$val,$_zLang);
			}

			if(strstr($_zMonnaie,$mon_decimal_point)){
				$_zMonnaie = floatval(toolNombre::removeBlank(str_replace($mon_decimal_point,".",$_zMonnaie)));
			}

			if(strstr($_zMonnaie,$mon_thousands_sep)){
				$_zMonnaie = floatval(toolNombre::removeBlank(str_replace($mon_thousands_sep,"",$_zMonnaie)));
			}
			return $_zMonnaie;
		}	
	}
}
?>