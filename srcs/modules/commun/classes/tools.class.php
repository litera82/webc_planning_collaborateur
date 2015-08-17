<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires
* @package Reghalal
* @subpackage commun
*/
class Tools {

	/**
	* Fonction de formatage de date FR en date EN (format mysql)
	*
	* @param	string	$_zDatefr	Date FR
	* @return	string	$zDatesql	Date UK (ou NULL)
	*/
	 static function toDateSQL($_zDatefr) {
	    $zDate =  trim($_zDatefr);    
		
		$zSeparateur = strrpos( $zDate, "/")?'/':'-';

		$tD = explode($zSeparateur,$zDate);

		if ($tD[0]<>"") {
			$zDatesql = $tD[2]."-".$tD[1]."-".$tD[0];
			return $zDatesql;
		}
		return "NULL";
	}  // FIN : toDateSQL()


	/**
	* Fonction de formatage mois de date FR
	*
	* @param	string	$_zDatefr	Date FR
	* @return	array	$tD[1]
	*/
	static function getMonthDateFr($_zDatefr){
		$_zDatefr = trim($_zDatefr);
		$tD = explode('/',$_zDatefr);
		return $tD[1];
	}

	/**
	* Fonction de formatage annee de date FR
	*
	* @param	string	$_zDatefr	Date FR
	* @return	array	$tD[2]
	*/
	static function getYearDateFr($_zDatefr){
		$_zDatefr = trim($_zDatefr);
		$tD = explode('/',$_zDatefr);
		return $tD[2];
	}

	/**
	* Fonction recupération(année/mois)  date EN (format mysql)
	*
	* @param	string	$_zDatefr	Date FR
	* @return	string	$zDatesql	Date UK (ou NULL)
	*/
	static function getMonthDateEn($_zDatefr){
		$_zDatefr = trim($_zDatefr);
		$tD = explode('-',$_zDatefr);
		return $tD[1];
	}

	/**
	* Fonction recupération(année/mois)  date EN (format mysql)
	*
	* @param	string	$_zDatefr	Date FR
	* @return	array	$tD[0]
	*/
	static function getYearDateEn($_zDatefr){
		$_zDatefr = trim($_zDatefr);
		$tD = explode('-',$_zDatefr);
		return $tD[0];
	}

	/**
	* Fonction permettant de tester l'environnement
	*
	* @param string $_zChaine
	*/
	static function Adds($_zChaine){
		return( get_magic_quotes_gpc() == 1 ?
			$_zChaine :
			AddSlashes($_zChaine) );
	}  // FIN : Adds()

	/**
	* Fonction permettant de tester l'environnement
	*
	* @param		string	$_zChaine
	*/
	function Strips($_zChaine){
  	return( get_magic_quotes_gpc() == 1 ?
        StripSlashes($_zChaine) :
        $_zChaine );
	}  // FIN : Strips()

	/**
	* Fonction de formatage de date format mysql en date FR
	*
	* @param		string	$_zDatesql		Date FR
	* @return		string	$zDatefr		Date FR (ou cha?ne vide)
	*/

	static function toDateFR($_zDatesql) {
		$_zDatesql = trim($_zDatesql);
		if (strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);
			//print_r($d);
			$zDatefr = $tD[2]."/".$tD[1]."/".$tD[0];
			return $zDatefr;
		}
		return "";
	}  // FIN : toDateFR()
	
	/**
	* Fonction de formatage de date format mysql en date FR
	*
	* @param	string	$_zDatesql	Date FR
	* @return	string	$zDatefr		Date FR (ou cha?ne vide)
	*/
	
	static function toDateAutre($_zDatesql) {
		$_zDatesql = trim($_zDatesql);
		if (strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);

			$zDatefr = $tD[0]."_".$tD[1]."_".$tD[2];
			return $zDatefr;
		}
		return "";
	}

	/**
	* Fonction de formatage de date UK (format mysql) en date FR
	*
	* @param	string	$_zDatesql	Date UK
	* @return	string	$zDateuk		Date FR (ou cha?ne vide)
	*/

	static function toDateUK($_zDatesql) {
		$_zDatesql = trim($_zDatesql);
		if (strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);
			$zDateuk = $tD[1]."/".$tD[2]."/".$tD[0];
			return $zDateuk;
		}
		return "";
	}  // FIN : toDateUK

	/**
	* Fonction pour remplacer les caract?res accentu?s en simples caract?res
	*
	* @param		string	$string	
	* @return		string					Chaine sans accent
	*/
	static function removeaccents($string) {
		return strtr($string, "ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêë
		ìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeei
		iiionoooooouuuuyy");
	}  // FIN : removeaccents()

	/**
	* Fonction pour remplacer les caract?res sp?ciaux en _
	*
	* @param		string	$_zString	Cha?ne ? traiter
	* @return	string					Ch?ine enlev?e des caract?ers sp?ciaux
	*/
	static function removeSpecialChars($_zString) {
		return strtr($_zString, "?,;.:/!?&#{[(|\\@)]=+}\$?*%><\"'", "_______________________________");
	}  // FIN : removeSpecialChars()

	/**
	* Diff?rence de 2 dates en secondes pour une date MySQL , de la forme AAAA-MM-JJ
	*
	* @param		string	$_zDate_1	Date 1
	* @param		string	$_zDate_2	Date 2
	* @return	int	$iDiff		Difference entre les 2 dates
	*/
	static function dateDiff($_zDate_1, $_zDate_2) {
		$zMktime_1= mktime(0,0,0,substr($_zDate_1,5,2),substr($_zDate_1,-2),substr($_zDate_1,0,4));
		$zMktime_2 = mktime(0,0,0,substr($_zDate_2,5,2),substr($_zDate_2,-2),substr($_zDate_2,0,4));
		$iDiff = intval($zMktime_1 - $zMktime_2);
		return $iDiff;
	}  // FIN : dateDiff

	/**
	* Formattage d'une date en format longue : Dimanche 15 janvier 2005
	*
	* @param string $_zStrDateInput Date de la forme YYYY-MM-JJ
	* @param string $_zSeparateur S?parateur
	* @param string $_zLangue Langue (fr/en, par d?faut fr)
	* @return string $zDateResult Date au format long
	*/
	static function formatToLongDate($_zStrDateInput, $_zSeparateur, $_zLangue  = 'fr') {
		list($yYear, $iMonth, $iDay)=explode($_zSeparateur, $_zStrDateInput);
		$_zStrDateInput = mktime(0,0,0,$iMonth, $iDay, $yYear);
		$zFormat = "w";
		$iDay = date($zFormat, $_zStrDateInput);
		$zFormat = "n";
		$iMonth = date($zFormat, $_zStrDateInput);

		if ($_zLangue == 'en') {
			$tTabDays = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday",
							"Friday", "Saturday");
			$tTabMonths = array("january", "february", "march", "april", "mai", "june", "july",
								"august", "september", "october", "november", "december");
			$zDateResult = $tTabDays[$iDay] . ", " .  $tTabMonths[$iMonth-1] . " " .
			date("d", $_zStrDateInput). ", " . date("Y", $_zStrDateInput);
		} else {
			$tTabDays = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
			$tTabMonths = array("janvier", "f?vrier", "mars", "avril", "mai", "juin", "juillet", "ao?t",
								"septembre", "octobre", "novembre", "d?cembre");
			$zDateResult = $tTabDays[$iDay] . " " . date("d", $_zStrDateInput) . " " . $tTabMonths[$iMonth-1] .
			" " . date("Y", $_zStrDateInput);
		}

		return $zDateResult;
	}  // FIN : formatToLongDate()


	/**
	* Formattage d'une date en format longue sans jour : 15 janvier 2005
	*
	* @param string $strDateInput Date de la forme YYYY-MM-JJ
	* @param string $langue Langue (fr/en, par d?faut fr)
	* @return string $date_result Date au format long sans jour
	*/
	static function formatThisDate($strDateInput,$langue = 'fr') {
		list($year,$month,$day)=explode("-",$strDateInput);
		$strDateInput = mktime(0,0,0,$month,$day,$year);
		$format = "w";
		$day = date($format,$strDateInput);
		$format = "n";
		$month = date($format,$strDateInput);

		if ($langue == 'en') {
			$tab_months = array("january", "february", "march", "april", "mai", "june", "july", "august",
								"september", "october", "november", "december");
			$date_result = $tab_months[$month-1] . ", " . date("d",$strDateInput). ", " . date("Y",$strDateInput);
		} else {
			$tab_months = array("janvier", "f?vrier", "mars", "avril", "mai", "juin", "juillet", "ao?t",
								"septembre", "octobre", "novembre", "d?cembre");
			$date_result = date("d",$strDateInput) . " " . $tab_months[$month-1] . " " . date("Y",$strDateInput);
		}

		return $date_result;
	}  // FIN : formatThisDate()


	/**
	* Formatage du parcours en km : en BD : XX.XX ( pour le BO)
	*
	* @param		string	$input_distance	valeur originale de la distance
	* @param		int		$str_search		cha?ne ? remplacer
	* @param		string	$str_replace		cha?ne de remplacement
	* @return		array	$tzResult				cha?ne format?e (la distance)
	*/
	function formatDistanceBO($input_distance,$str_search,$str_replace){
	if(!strpos($input_distance,'.'))
	{
		$tzResult = intval($input_distance)==0?'':$input_distance;
	}
	else
	{
		list($int_part,$dec_part)=explode($str_search,strval($input_distance));
		if(intval($dec_part)==0)
		{

			$str_replace='';
			$dec_part='';
		}
		else
		{
			if(substr($dec_part,-1)==0)
			{
				$dec_part=substr($dec_part,0,1);
			}
			else
			{
				$dec_part=substr($dec_part,0,2);
			}
		}
		//return $dec_part;
		$tzResult = $int_part.$str_search.$dec_part >0 ? $int_part.$str_replace.$dec_part :'';
	}
	return $tzResult;
}


	/**
	* Fonction d'ajout de n jour ? une date donn?e
	*
	* @param		string	$_zStrDate	Date de la forme YYYY-MM-JJ
	* @param		int		$_iIntDays	Nomber de jours ? ajouter
	* @return		string	$nextDate	Date au format YYYY-MM-JJ
	*/
	static function addDayToDate($_zStrDate,$_iIntDays)	{
		list($iYear, $iMonth, $iDay)=explode("-", $_zStrDate);
		$zMkDate = mktime(0,0,0, $iMonth, $iDay + $_iIntDays, $iYear);
		$zNextDate  = date("Y-m-d", $zMkDate);
		return $zNextDate;
	}// FIN : addDayToDate()

	/**
	* Fonction d'upload d'un fichier
	* @param		string	$_zFileSrc		fichier source ? charger
	* @param		string	$_zUploadPath	chemin de la destination
	*/
	static function uploadFile($_zFileSrc, $_zUploadPath) {
		if (isset($_FILES[$_zFileSrc]["name"])&& ($_FILES[$_zFileSrc]["name"]!= "")) {
			$zFileDestArr =str_replace(" ", "_", $_FILES[$_zFileSrc]["name"]);
			$tzFileDestArr = explode(".", $zFileDestArr);
			$zStrBasename= $tzFileDestArr[0];
			$zStrExtension= $tzFileDestArr[sizeof($tzFileDestArr)-1];

			$iC0 = 0;

			while (file_exists($_zUploadPath . $zStrBasename . $iC0 . "." . $zStrExtension)) {
				$iC0++;
			}

			$zFileDest = $zStrBasename . $iC0 . "." . $zStrExtension;

			if (strlen(trim($zFileDest)) > 0)  {
				if (isset($_FILES[$_zFileSrc]["name"])) {
					if (file_exists($_FILES[$_zFileSrc]["tmp_name"])) {
						move_uploaded_file($_FILES[$_zFileSrc]["tmp_name"], $_zUploadPath . $zFileDest);
						chmod($_zUploadPath . $zFileDest, 0777);
						@unlink($_FILES[$_zFileSrc]["tmp_name"]);

						return $zFileDest;
				   }
				}
			}
		}
	}  // FIN : uploadFile()

	/**
	* Fonction de translation de mois EN en mois FR
	*
	* @param		mixed		$_moisEn			Nom du mois EN ou sa valeur num?rique modulo 12
	* @param		boolean	$_bLongFormat	Format long :nom complet du mois (true : par d?faut) ou court
	* 					:trois premi?res lettres (FALSE)
	* @return	string						Mois au format Fr
	*/
	 static function toMonthFr($_moisEn, $_bLongFormat=TRUE) {
		$ttMonthFr = array('december'=> array("d?cembre","d?c")
							,'january'=> array("janvier","jan")
							,'february'=> array("f?vrier","fev")
							,'march'=> array("mars","mar")
							,'april'=> array("avril","avr")
							,'may'=> array("mai","mai")
							,'june'=> array("juin","jui")
							,'july'=> array("juillet","jul")
							,'august'=> array("ao?t","aou")
							,'september'=> array("septembre","sep")
							,'october'=> array("octobre","oct")
							,'november'=> array("novembre","nov"));
		if ($_bLongFormat) {
		   if (is_int($_moisEn)) {
		   	return $ttMonthFr[$_moisEn%12][0];
			} else {
				return $ttMonthFr[strtolower($_moisEn)][0];
			}

		} else {
		   if (is_int($_moisEn)) {
		   	return $ttMonthFr[$_moisEn%12][1];
			} else {
				return $ttMonthFr[strtolower($_moisEn)][1];
			}
		}
	}  // FIN : toMonthFr()

	/**
	* D?doublonne un Objet/tableau associatif et/ou index? (comme array_unique)
	*
	* @param		array		&$_tVar			r?f?rence au Tableau d'Objets unidimensionnel ou tableau simple bidimensionnel
	* @param		boolean		$_bAssoc		identifie si tenir compte des cles/index (TRUE) ou par valeur uniquement (FALSE)
	* @return		array		$tDoubles		Tableau contenant les ?l?ments en double qu'on a supprim?s du tableau en param
	*/
	static function dedoublonne(&$_tVar,$_bAssoc=TRUE)	{
	   $tDoubles = array();
	   $tTemp = $_tVar;
		foreach ($_tVar as $key=>$value) { // prendre un ?l?ment ? comparer aux autres
			$iVType = gettype($value);
			if ($iVType=="array"){
		      $value_ = implode("-",array_values($value));
			} elseif ($iVType=="object") {
		      $value_ = implode("-",self::getElts($value, FALSE));
			}
	      foreach ($tTemp as $key1=>$value1) { // comparaison de l'?l?ment avec tous les autres
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
	* R?cup?re dans un tableau toutes les (propri?t?s ou valeurs) d'un Objet
	*
	* @param		object	$_tObj		Objet dont les prop ou valeurs sont ? recuperer
	* @param		boolean	$_bProp		Indique si recuperer les proprietes (TRUE: par d?faut) ou les
	* 						valeurs (FALSE)
	* @return	string	$tReturn		La cha?ne r?sultante
	*/
	static function getElts($_tObj, $_bProp=TRUE)	{
		$tReturn = array();
		foreach ($_tObj as $key=>$value) {
		   if ($_bProp) { // r?cup?rer les propri?t?s
				array_push($tReturn, $key);
			} else {
				array_push($tReturn, $value);
			}
		}
		return $tReturn;
	} //FIN : getElts()


	/**
	* G?n?re un hashcode
	*
	* @return string  $zHashcode La cha?ne code g?n?r?
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
	}	//	FIN : generateHashCode()


	/**
	* recherche les parametres de session
	*
	* @param string   $_zParName  Nom du parametre de session a rechercher
	* @param string   $_zDefault  Valeur par defaut
	* @return string  				Valeur finale obtenue
	*/
	static function sessParam($_zParName, $_zDefault="") {
	   if (isset($_SESSION[$_zParName])) {
	   	return $_SESSION[$_zParName];
		} else {
	   	return $_zDefault;
		}
	}	//	FIN : sessParam()

	/**
	* Transforme un objet en un tableau dont les cl?s sont les propri?t?s de l'objet
	*
	* @param object   $_oFrom  Objet de d?part
	* @return array   $tTo     Tableau d'arriv?e
	*/
	static function object2Array($_oFrom) {
	   $tTo = array();
	   foreach ($_oFrom as $zProp) {
	      $tTo[$zProp] = $_oFrom->$zProp;
		}
		return $tTo;
	}  //	FIN : object2Array()

	/**
	* Fonction de suppression d'?l?ments d'un tableau unidimensionnel
	*
	* @param	array		1$_tArray		R?f?rence du tableau duquel on veut supprimer les ?l?ments
	* @param	array		$_tRem			Tableau des ?l?ments ? supprimer
	* @param	boolean		$_bInd			Bool?en indiquant si on veut supprimer par index (TRUE) ou par cl? (FALSE)
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
	* Ramasse les valeurs d'un tableau 2d dans un autres selon cl?s donn?es
	*
	* @param 	array		$_tInput 	Tableau d'entr?e, de laquelle les valeurs sont ? prendre
	* @param 	string	$_zKeysList Cha?ne listant (s?parateur = "-") les cl?s ? voir
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
	* Ramasse les valeurs d'un tableau d'objets dans un autres selon proprit?s donn?es
	*
	* @param 	array		$_tInput 	Tableau d'entr?e, depuis lequel les valeurs sont ? prendre
	* @param 	string	$_zPropList Cha?ne listant (s?parateur = "-") les propri?t?s ? voir
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
	* @param 	array		$_tArray 	Tableau d'entr?e, depuis lequel les valeurs sont ? prendre
	* @return	array 	$oRet 		Objet obtenu
	*/
	static function array2Object($_tArray) {
	   $oRet = new StdClass();
	   $tKeys = array_keys($_tArray);
	   foreach ($tKeys as $key) {
	      $oRet->$key = $_tArray[$key];
		}
		return $oRet;
	}  // FIN : array2Object()

	

	/**   Charge la valeur du param?tre en DB dont le nom est fourni en param?tre de la m?thode
	 * @param   string      $_zParamName      Nom du param?tre
	 * @return  mixed       $oParamValue      Valeur du param?tre
	 **/
	static function getParam($_zParamName)
	{
		$oDb = jDb::getDbWidget();
		$zQuery = "
			SELECT	parametre_nom, parametre_valeur
			FROM	parametre
			WHERE	parametre_nom='".$_zParamName."'";

		if ($toParams = $oDb->fetchAll($zQuery))
		{
		   return $toParams[0]->parametre_valeur;
		}
		return '';
	}  // FIN : getParam()

	/**   Retourne les propri2t2s d'un objet
	 * @param   object	$_oObj		Nom du param?tre
	 * @return  array		$tzProp		Liste des proprietes de l'objet donne
	 **/
	static function getProperties($_oObj)
	{
		$tzProp = array();
		if (count($_oObj)>0)
		{
			foreach($_oObj as $key=> $value)
		   {
		   array_push($tzProp, $key);
			}
		}
		return $tzProp;
	}


	/**
	* recherche les parametres de session
	*
	* @param string   $_zParName  Nom du parametre de session a rechercher
	* @param string   $_zDefault  Valeur par defaut
	* @return string  				Valeur finale obtenue
	*/
	static function gpParam($_zParName, $_zDefault="") {
	   if (isset($_POST[$_zParName]))
		{
	   	return $_POST[$_zParName];
		}
		elseif (isset($_GET[$_zParName]))
		{
	   	return $_GET[$_zParName];
		}
		else
		{
	   	return $_zDefault;
		}
	}	//	FIN : sessParam()

	/**
	*	Recherche l'occurrence d'un ?l?ment quelconque dans un array

	* @param	mixed	$_oNeedle	L'?l?ment ? trouver
	* @param	array	$_tArray	Le tableau ? explorer

	* @return	Mixed	$oKey		La cl? (ou index) de l'occurrence, sinon -1
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
	*	Recherche caractères accentué et remplace
	* @param	string	$strTochange
	* @return	string	$strResult
	*/
	static function replaceAccent ($strTochange){    
		$character = array("é", "è", "ê", "ë", "ô", "ö", "â", "ä", "à", "ù", "û", "ü", "ç","ï","î"," ");

		$codeNew = array("e", "e", "e", "e", "o", "o", "a", "a", "a", "u" ,"u", "u", "ç","i","i","-");

		$strResult = str_replace($character, $codeNew, $strTochange);

		return $strResult;
				
	}
	/**
	* finction replaceAccentAndSpace
	* @param	string	$strTochange
	* @return	string	$strResult
	*/
	static function replaceAccentAndSpace ($strTochange){    
		$character = array("é", "è", "ê", "ë", "ô", "ö", "â", "ä", "à", "ù", "û", "ü", "ç","ï","î"," ");

		$codeNew = array("e", "e", "e", "e", "o", "o", "a", "a", "a", "u" ,"u", "u", "ç","i","i","");

		$strResult = str_replace($character, $codeNew, $strTochange);

		return $strResult;
				
	}


	/**
    * traitement des visuels téléchargés re-dimensionnement,suppression
	* @param	string	$zAction
	* @param	string	$zFichier
	* @param	int	$iImageWidth
	* @param	int	$iImageHeight
	* @param	string	$zPathResize
	* @return	array $tzResult
    */
    static function traitementVisuels($zAction, $zFichier, $iImageWidth, $iImageHeight, $zPathResize, $zMehode='ratio') {
		global $gJConfig;
		switch($zAction){
			case 'suppr'://suppression des images re-dimensionnées au cas ou annulation
				$tzResult=array();
				if(count($zFichier)>0){
					for($i=0;$i<count($zFichier);$i++){
						if($zFichier[$i]!=''){
							if(file_exists($zPathResize.basename($zFichier[$i])) && is_file($zPathResize.basename($zFichier[$i])))
							@unlink($zPathResize.basename($zFichier[$i]));
						}
					}
				}
				break;
			case 'resize'://dimensionner au format exact l'image t?l?charg?e
				$tzResult = array("visuel"=>"","image"=>"");
				$zFichier = substr($zFichier[0],strlen($gJConfig->urlengine['basePath']),strlen($zFichier[0]));
				$zExtension=explode(".",basename($zFichier));

				//jClasses::inc('commun~image') ;
				switch(strtolower($zExtension[count($zExtension)-1])){
					case 'gif':
						$zFormat='GIF';
						break;
					case 'jpeg':
					case 'jpg':
						$zFormat='JPEG';
						break;
					case 'png':
						$zFormat='PNG';
						break;
					case 'swf':
						$zFormat='SWF';
						break;
					default:
						$zFormat='';
						break;
				}
				$zNomfichier = '';
				for($i=0;$i<count($zExtension)-1;$i++){
					$zNomfichier.=$zExtension[$i];
					if($i<count($zExtension)-2)
					$zNomfichier.='.';
				}
				$zVisuel = $zNomfichier."_".$iImageWidth."_".$iImageHeight.".".strtolower($zExtension[count($zExtension)-1]);
				$i=1;
				
				while(file_exists($zPathResize.$zVisuel) && is_file($zPathResize.$zVisuel)){
					list($nom,$ext)=explode(".",$zVisuel);
					$nom=explode('_',$nom);
					$zVisuel='';
					if(!($i>1)){
						$j=count($nom)-2;
						$k=count($nom)-1;
						for($l=0;$l<count($nom)-2;$l++) $zVisuel.=$nom[$l]."_";
					}else{
						$j=count($nom)-3;
						$k=count($nom)-2;
						for($l=0;$l<count($nom)-3;$l++) $zVisuel.=$nom[$l]."_";
					}

					$zVisuel=$zVisuel.$nom[$j]."_".$nom[$k]."_".$i.".".strtolower($ext);
					$i++;
				}

				$zVisuel = self::filemanechange($zVisuel);

				$tzResult['image']=$zVisuel;
				switch($zFormat){
					case 'GIF':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);
						
						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){
							$imgF->resize($iImageWidth,$iImageHeight,$zMehode,true);
							$imgF->output($zFormat,$zPathResize.$zVisuel,true);	
						}else{
							@copy($zFichier,$zPathResize.$zVisuel);
						}						

						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;

					case 'JPEG':
						$imgF = new ImageFilter;

						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];

						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){
							$imgF->resize($iImageWidth,$iImageHeight,$zMehode,true);
							$imgF->output($zFormat,$zPathResize.$zVisuel,true);	
						}else{
							@copy($zFichier,$zPathResize.$zVisuel);
						}					
						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;

					case 'PNG':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){
							$imgF->resize($iImageWidth,$iImageHeight,$zMehode,true);
							$imgF->output($zFormat,$zPathResize.$zVisuel,true);	
						}else{
							@copy($zFichier,$zPathResize.$zVisuel);
						}						

						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;
					case 'SWF':
						@copy($zFichier,$zPathResize.$zVisuel);
						$tzResult['visuel']=sprintf('<object  name="video" id="video" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" id="index" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="%s" />	<param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="%s" menu="false" quality="high" bgcolor="#ffffff" name="index" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel,$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;
				}
				break;
		}
		return $tzResult;
	}


	/**
    * traitement des video et visuel video téléchargés re-dimensionnement,suppression
	* @param	string	$zAction
	* @param	string	$zFichier
	* @return	array $result
    */
    static function traitementVisuelsVideo($zAction, $zFichier) {
		global $gJConfig;

		switch($zAction){
			case 'suppr'://suppression des images re-dimensionn?es au cas o? annulation
				$result=array();
				if(count($zFichier)>0){
					for($i=0;$i<count($zFichier);$i++){
						if($zFichier[$i]!=''){
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i]));
						}
					}
				}
				break;
			case 'resize'://dimensionner au format exact l'image t?l?charg?e
				$result=array("visuel"=>"","image"=>"");
				$zFichier=substr($zFichier[0],strlen($gJConfig->urlengine['basePath']),strlen($zFichier[0]));
				
				$extension=explode(".",basename($zFichier));
				//jClasses::inc('commun~image') ;
				switch(strtolower($extension[count($extension)-1])){
					case 'gif':
						$format='GIF';
						break;
					case 'jpeg':
					case 'jpg':
						$format='JPEG';
						break;
					case 'png':
						$format='PNG';
						break;
					case 'swf':
						$format='SWF';
					case 'flv':
						$format='FLV';	
						break;
					case 'avi':
						$format='AVI';
						break;	
					default:
						$format='';
						break;
				}
				$nomfichier='';
				for($i=0;$i<count($extension)-1;$i++){
					$nomfichier.=$extension[$i];
					if($i<count($extension)-2)
					$nomfichier.='.';
				}
				jClasses::inc('commun~tools');
				
				//copier le fichier vers le rep origine
				if($format == 'GIF' || $format=='JPEG' || $format=='PNG' || $format=='SWF')
				{
					$oldNameFichier = Tools::replaceAccentAndSpace ($nomfichier).".".strtolower($extension[count($extension)-1]);
					@copy($zFichier,PATH_RESIZE_PHOTO_ORIGINE.$oldNameFichier);
				}
				
				
				$nomfichier = Tools::replaceAccentAndSpace ($nomfichier);
				
				$visuel=$nomfichier."_".PHOTO_FORMAT1_WIDTH."_".PHOTO_FORMAT1_HEIGHT.".".strtolower($extension[count($extension)-1]);
				//$visuel=$nomfichier.".".strtolower($extension[count($extension)-1]);
				$i=1;
				while(file_exists(PATH_RESIZE_PHOTO_FORMAT1.$visuel) && is_file(PATH_RESIZE_PHOTO_FORMAT1.$visuel)){
					list($nom,$ext)=explode(".",$visuel);
					$nom=explode('_',$nom);
					$visuel='';
					if(!($i>1)){
						$j=count($nom)-2;
						$k=count($nom)-1;
						for($l=0;$l<count($nom)-2;$l++) $visuel.=$nom[$l]."_";
					}else{
						$j=count($nom)-3;
						$k=count($nom)-2;
						for($l=0;$l<count($nom)-3;$l++) $visuel.=$nom[$l]."_";
					}
					$visuel=$visuel.$nom[$j]."_".$nom[$k]."_".$i.".".strtolower($ext);
					$i++;
				}
				$visuel = self::filemanechange($visuel);
				$result['image']=$visuel;

				$zBrowser = self::navigateur();

				switch($format){
					case 'GIF':
					break;
					case 'JPEG':
					break;
					case 'PNG':
					break;
					case 'SWF':
						@copy($zFichier,PATH_VIDEO_RESIZE.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="250" height="200" wmode="transparent" type="application/x-shockwave-flash" src="%s" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="image=%s&file=%s&autostart=false&showstop=true&usefullscreen=false"/><a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;">Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé</a>', $gJConfig->urlengine['basePath'].PATH_VIDEO_RESIZE.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="250" height="200"><param name="movie" value="%s" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /></object>', $gJConfig->urlengine['basePath'].PATH_VIDEO_RESIZE.$visuel);					
							}
						break;
					case 'FLV':
						@copy($zFichier,PATH_VIDEO_RESIZE.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="250" height="200" wmode="transparent" type="application/x-shockwave-flash" src="%sdesign/back/swf/flvplayer.swf" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/> <a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;"> Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé </a>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].PATH_VIDEO_RESIZE.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="250" height="200"><param name="movie" value="%sdesign/back/swf/flvplayer.swf" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /><param name="flashvars" value="image=&file=%s&autostart=false&showstop=true&usefullscreen=false" /></object>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].PATH_VIDEO_RESIZE.$visuel);					
							}
						break;	
					case 'AVI':
						@copy($zFichier,PATH_VIDEO_RESIZE.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="250" height="200" 
													wmode="transparent" type="application/x-shockwave-flash" 
													src="%sdesign/back/swf/flvplayer.swf" 
													pluginspage="http://www.adobe.com/go/getflashplayer" 
													flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/>
													<a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;">
													Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé
													</a>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].PATH_VIDEO_RESIZE.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="250" height="200"><param name="movie" value="%sdesign/back/swf/flvplayer.swf" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /><param name="flashvars" value="image=&file=%s&autostart=false&showstop=true&usefullscreen=false" /></object>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].PATH_VIDEO_RESIZE.$visuel);					
							}
						break;	
				} 
				break;
		}
		return $result;
	}

	/**
    * function traitementVisuelsGenerique
	* @param	string	$zAction
	* @param	string	$zFichier
	* @return	array $tzResult
    */
    static function traitementVisuelsGenerique($zAction, $zFichier) {
		global $gJConfig;
		switch($zAction){
			case 'suppr'://suppression des images re-dimensionn?es au cas o? annulation
				$tzResult=array();
				if(count($zFichier)>0){
					for($i=0;$i<count($zFichier);$i++){
						if($zFichier[$i]!=''){
							if(file_exists(PATH_GENERIQUE_RESIZE.basename($zFichier[$i])) && is_file(PATH_GENERIQUE_RESIZE.basename($zFichier[$i])))
							@unlink(PATH_GENERIQUE_RESIZE.basename($zFichier[$i]));
						}
					}
				}
				break;
			case 'resize'://dimensionner au format exact l'image t?l?charg?e
				$tzResult=array("visuel"=>"","image"=>"");
				$zFichier=substr($zFichier[0],strlen($gJConfig->urlengine['basePath']),strlen($zFichier[0]));
				$extension=explode(".",basename($zFichier));
				//jClasses::inc('commun~image') ;
				switch(strtolower($extension[count($extension)-1])){
					case 'gif':
						$format='GIF';
						break;
					case 'jpeg':
					case 'jpg':
						$format='JPEG';
						break;
					case 'png':
						$format='PNG';
						break;
					case 'swf':
						$format='SWF';
						break;
					default:
						$format='';
						break;
				}
				$nomfichier='';
				for($i=0;$i<count($extension)-1;$i++){
					$nomfichier.=$extension[$i];
					if($i<count($extension)-2)
					$nomfichier.='.';
				}
				$visuel=$nomfichier."_".GENERIQUE_WIDTH."_".GENERIQUE_HEIGHT.".".strtolower($extension[count($extension)-1]);
				$i=1;
				while(file_exists(PATH_GENERIQUE_RESIZE.$visuel) && is_file(PATH_GENERIQUE_RESIZE.$visuel)){
					list($nom,$ext)=explode(".",$visuel);
					$nom=explode('_',$nom);
					$visuel='';
					if(!($i>1)){
						$j=count($nom)-2;
						$k=count($nom)-1;
						for($l=0;$l<count($nom)-2;$l++) $visuel.=$nom[$l]."_";
					}else{
						$j=count($nom)-3;
						$k=count($nom)-2;
						for($l=0;$l<count($nom)-3;$l++) $visuel.=$nom[$l]."_";
					}
					$visuel=$visuel.$nom[$j]."_".$nom[$k]."_".$i.".".strtolower($ext);
					$i++;
				}
				$visuel = self::filemanechange($visuel);
				$tzResult['image']=$visuel;
				switch($format){
					case 'GIF':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if (GENERIQUE_WIDTH < $tSize['w'] || GENERIQUE_HEIGHT < $tSize['h']){
							$imgF->resize(GENERIQUE_WIDTH,GENERIQUE_HEIGHT,'ratio',true);
							$imgF->output($format,PATH_GENERIQUE_RESIZE.$visuel,true);	
						}else{
							@copy($zFichier,PATH_GENERIQUE_RESIZE.$visuel);
						}						

						//$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].PATH_GENERIQUE_RESIZE.$visuel);
						$tzResult['visuel'] = $gJConfig->urlengine['basePath'].PATH_GENERIQUE_RESIZE.$visuel;
						break;

					case 'JPEG':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if (GENERIQUE_WIDTH < $tSize['w'] || GENERIQUE_HEIGHT < $tSize['h']){
							$imgF->resize(GENERIQUE_WIDTH,GENERIQUE_HEIGHT,'ratio',true);
							$imgF->output($format,PATH_GENERIQUE_RESIZE.$visuel,true);	
						}else{
							@copy($zFichier,PATH_GENERIQUE_RESIZE.$visuel);
						}						

						//$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].PATH_GENERIQUE_RESIZE.$visuel);
						$tzResult['visuel']= $gJConfig->urlengine['basePath'].PATH_GENERIQUE_RESIZE.$visuel;
						break;

					case 'PNG':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if (GENERIQUE_WIDTH < $tSize['w'] || GENERIQUE_HEIGHT < $tSize['h']){
							$imgF->resize(GENERIQUE_WIDTH,GENERIQUE_HEIGHT,'ratio',true);
							$imgF->output($format,PATH_GENERIQUE_RESIZE.$visuel,true);	
						}else{
							@copy($zFichier,PATH_GENERIQUE_RESIZE.$visuel);
						}						

						$tzResult['visuel']= $gJConfig->urlengine['basePath'].PATH_GENERIQUE_RESIZE.$visuel;
						break;
					case 'SWF':
						@copy($zFichier,PATH_GENERIQUE_RESIZE.$visuel);
						$tzResult['visuel']= '';
						break;
				}
				break;
		}
		return $tzResult;
	}

	/**
	* Detection navigateur
	**/	
	static function navigateur (){
		 if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') ){
			 if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape') ){
				 $browser = 'Netscape (Gecko/Netscape)';
			 }
			 else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') ){
				$browser = 'ff';
			 }else{
				 $browser = 'ff';
			 }
		 }
		 else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ){
			 if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') ){
				 $browser = 'op';
			 }else{
				 $browser = 'ie';
			 }
		 }else{
			$browser = 'ff';
		 }
		 return $browser; 	
	} 
	

	/**
    * traitement des video et visuel video téléchargés re-dimensionnement,suppression
	* @param	string	$zAction
	* @param	string	$zFichier
	* @param	int	$iWidth
	* @param	int	$iHeight
	* @param	string	$zPathVideo
	* @return	array $result
    */
    static function traitementVisuelsVideoProjet($zAction, $zFichier, $iWidth, $iHeight, $zPathVideo) {
		global $gJConfig;

		switch($zAction){
			case 'suppr'://suppression des images re-dimensionn?es au cas o? annulation
				$result=array();
				if(count($zFichier)>0){
					for($i=0;$i<count($zFichier);$i++){
						if($zFichier[$i]!=''){
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i]));
						}
					}
				}
				break;
			case 'resize'://dimensionner au format exact l'image t?l?charg?e
				$result=array("visuel"=>"","image"=>"");
				$zFichier=substr($zFichier[0],strlen($gJConfig->urlengine['basePath']),strlen($zFichier[0]));

				$extension=explode(".",basename($zFichier));

				//jClasses::inc('commun~image') ;
				switch(strtolower($extension[count($extension)-1])){
					case 'gif':
						$format='GIF';
						break;
					case 'jpeg':
					case 'jpg':
						$format='JPEG';
						break;
					case 'png':
						$format='PNG';
						break;
					case 'swf':
						$format='SWF';
					case 'flv':
						$format='FLV';	
						break;
					case 'avi':
						$format='AVI';
						break;	
					default:
						$format='';
						break;
				}

				$nomfichier='';
				for($i=0;$i<count($extension)-1;$i++){
					$nomfichier.=$extension[$i];
					if($i<count($extension)-2)
					$nomfichier.='.';
				}
				jClasses::inc('commun~tools');
				
				//copier le fichier vers le rep origine
				if($format == 'GIF' || $format=='JPEG' || $format=='PNG' || $format=='SWF' || $format=='FLV' || $format=='AVI')
				{
					$oldNameFichier = Tools::replaceAccentAndSpace ($nomfichier).".".strtolower($extension[count($extension)-1]);
					@copy($zFichier,PATH_RESIZE_PHOTO_ORIGINE.$oldNameFichier);
				}
				
				$nomfichier = Tools::replaceAccentAndSpace ($nomfichier);
				
				$visuel=$nomfichier."_".PHOTO_FORMAT1_WIDTH."_".PHOTO_FORMAT1_HEIGHT.".".strtolower($extension[count($extension)-1]);

				$i=1;
				while(file_exists(PATH_RESIZE_PHOTO_FORMAT1.$visuel) && is_file(PATH_RESIZE_PHOTO_FORMAT1.$visuel)){
					list($nom,$ext)=explode(".",$visuel);
					$nom=explode('_',$nom);
					$visuel='';
					if(!($i>1)){
						$j=count($nom)-2;
						$k=count($nom)-1;
						for($l=0;$l<count($nom)-2;$l++) $visuel.=$nom[$l]."_";
					}else{
						$j=count($nom)-3;
						$k=count($nom)-2;
						for($l=0;$l<count($nom)-3;$l++) $visuel.=$nom[$l]."_";
					}
					$visuel=$visuel.$nom[$j]."_".$nom[$k]."_".$i.".".strtolower($ext);
					$i++;
				}

				$visuel = self::filemanechange($visuel);
				$result['image']=$visuel;
				$zBrowser = self::navigateur();

				switch($format){
					case 'GIF':
					break;
					case 'JPEG':
					break;
					case 'PNG':
					break;
					case 'SWF':
						@copy($zFichier,$zPathVideo.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="%s" height="%s" wmode="transparent" type="application/x-shockwave-flash" src="%s" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/><a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;">Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé</a>', $iWidth, $iHeight, $gJConfig->urlengine['basePath'].$zPathVideo.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="%s" height="%s"><param name="movie" value="%s" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /></object>', $iWidth, $iHeight, $gJConfig->urlengine['basePath'].$zPathVideo.$visuel);					
							}
						break;
					case 'FLV':
						copy($zFichier,$zPathVideo.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="%s" height="%s" wmode="transparent" type="application/x-shockwave-flash" src="%sdesign/back/swf/flvplayer.swf" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/> <a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;"> Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé </a>', $iWidth, $iHeight, $gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathVideo.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="%s" height="%s"><param name="movie" value="%sdesign/back/swf/flvplayer.swf" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /><param name="flashvars" value="image=&file=%s&autostart=true&showstop=true&usefullscreen=false" /></object>', $iWidth, $iHeight, $gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathVideo.$visuel);					

							}
						break;	
					case 'AVI':
						@copy($zFichier,$zPathVideo.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="%s" height="%s" 
													wmode="transparent" type="application/x-shockwave-flash" 
													src="%sdesign/back/swf/flvplayer.swf" 
													pluginspage="http://www.adobe.com/go/getflashplayer" 
													flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/>
													<a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;">
													Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé
													</a>', $iWidth, $iHeight, $gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathVideo.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="%s" height="%s"><param name="movie" value="%sdesign/back/swf/flvplayer.swf" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /><param name="flashvars" value="image=&file=%s&autostart=false&showstop=true&usefullscreen=false" /></object>', $iWidth, $iHeight, $gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathVideo.$visuel);					
							}
						break;	
				} 
				break;
		}
		return $result;
	}

	/**
	* verification d'un mail
	*
	* @param		string $_zInputStr le mail à  verifier
	* @return		string 
	*/
	static function checkEmailFormat ($_zInputStr) 
	{
		$zPattern1 = "(@.*@)|(\\.\\.)|(@\\.)|(^\\.)" ;
		//$zPattern2 = "^.+\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)$" ;
		$zPattern2 = "^.+\\@([a-zA-Z0-9\\.\\-])+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)$" ;

		return (!ereg ($zPattern1, $_zInputStr) && ereg ($zPattern2, $_zInputStr)) ;
	}

	/**
	* Fonction de formatage des URL : pas de letter accentuées, pas d'espace, pas de caractère spéciaux
	*
	* @param string $strTochange à formatter
	* @return string $strResult 
	*/
	static function urlChange($strTochange){
		$character = array("é", " ", "è", "ê", "ë", "ô", "ö", "â", "ä", "à", "ù", "û", "ü", "ç", " ", "-","€", "<", ">", "/", "\\", '"', "'", "\r", "\n", "+", "-","?","!",":",";",".",",","’","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","Œ","Ø","Š","Ù","Ú","Û","Ü","Ý","Ž","Þ", "À", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "«", "»");

		$codeNew = array("e", "_", "e", "e", "e", "o", "o", "a", "a", "a", "u", "u", "u", "c", "_", "_","euro", "", "", "_", "_", "", "", "_", "", "", "","","","_","","","","", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "e", "i", "i", "i", "i", "d", "n", "o", "o", "o", "o", "o", "0", "s", "u", "u", "u", "u", "y", "z", "", "a", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z","","");

		$strResult = str_replace($character, $codeNew, $strTochange);

		return $strResult;
				
	}

	/**
	* Fonction de formatage des URL : pas de letter accentuées, pas d'espace, pas de caractère spéciaux
	*
	* @param string $_zSource à formatter
	* @return string
	*/
	static function filemanechange($_zSource) {
		// Liste des caractères à remplacer
		$tcSpChar = array("é", " ", "è", "ê", "ë", "ô", "ö", "â", "ä", "à", "ù", "û", "ü", "ç", " ", "?", "<", ">", "/", "\\", '"', "'", "\r", "\n", "+", "-","?","!",":",";",".",",","?","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","?","Ø","?","Ù","Ú","Û","Ü","Ý","?","Þ", "À", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "«", "»", "%","@","¤","£","§","$","¨","#","~","`", "&");
		// liste des caractères normal
		$tcNormal = array("e", "", "e", "e", "e", "o", "o", "a", "a", "a", "u", "u", "u", "c", "_", "euro", "", "", "_", "_", "", "", "_", "", "", "-","","","_","","","","", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "e", "i", "i", "i", "i", "d", "n", "o", "o", "o", "o", "o", "0", "s", "u", "u", "u", "u", "y", "z", "", "a", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z","","","","","","","","","","","","","");

		$tzSource = explode ('.', $_zSource);
		
		$strResult = str_replace($tcSpChar, $tcNormal, $tzSource[0]);

		return $strResult . '.' . $tzSource[1];
	}
	
	/**
	* Fonction array_push_assoc
	* @param array $array
	* @param int $key
	* @param string $value
	* @return array $array
	*/
	static function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	/**
	* Fonction remote_file_exists
	* @param string $url
	* @return boolean
	*/
	static function remote_file_exists($url){
			@ini_set('allow_url_fopen', '1');
			if (@fclose(@fopen($url, 'r'))) {
				return true;
			} else {
				return false;
			}
	}

    /**
    * create a connector
    * @param	object  $_oProfil  profil properties
    * @return	object jDbConnection database connector
    */
    public static function createConnector ($_oProfil){
		global $gJConfig;
		if(!isset($gJConfig->_pluginsPathList_db[$_oProfil['driver']])
			|| !file_exists($gJConfig->_pluginsPathList_db[$_oProfil['driver']]) ){
			throw new jException('jelix~db.error.driver.notfound', $_oProfil['driver']);
		}
		$p = $gJConfig->_pluginsPathList_db[$_oProfil['driver']].$_oProfil['driver'];
		require_once($p.'.dbconnection.php');
		require_once($p.'.dbresultset.php');

		//creating of the connection
		$oClass = $_oProfil['driver'].'DbConnection';
		$oCnx = new $oClass ($_oProfil);
		return $oCnx;
    }

    /**
     * call it to test a profil (during an install for example)
     * @param object  $profil  profil properties
     * @return boolean  true if properties are ok
     */
    public static function testProfil($profil){
        try{
            self::createConnector ($profil);
            $ok = true;
        }catch(Exception $e){
            $ok = false;
        }
        return $ok;
    }

    /**
     * fonction remplireLog
     * @param string  $file
     * @param string  $string
     */
    static function remplireLog($file, $string){
        $handle = fopen($file, 'a');
        fwrite($handle, $string);
        fclose($handle);
    }

	/**
     * Fonction permettant de savoir la taille d'un fichier (filesize) d'une URL
     * @param string $_zUrl string url de l'image
     * @param string $_zReturn   megabite ou kbyte ou "" si on veut l'avoir en octet
     * @return int taille du fichier en $_zReturn
     */
	function getUrlFileSize($_zUrl,$_zReturn) {
		if (substr($_zUrl,0,4)=='http') { 
			$x = array_change_key_case(get_headers($_zUrl, 1),CASE_LOWER);
			$x = $x['content-length'];
		} else { 
			$x = @filesize($_zUrl); 
		}
		if (!$_zReturn) { 
			return $x ; 
		}elseif($_zReturn == 'mb') { 
			return round($x / (1024*1024),2) ; 
		}elseif($_zReturn == 'kb') { 
			return round($x / (1024),2) ; 
		}
	} 
	/**
	* test profil et reconnecter
	* @return object $oCnx
	*/
	public static function getConnect($zPorfilName){
		$oProfil = jDb::getProfil($zPorfilName);
//jLog::dump($oProfil) ;
		$bTestProfil = self::testProfil($oProfil);
//jLog::dump($bTestProfil) ;
		if (!$bTestProfil){
			self::createConnector($oProfil);
		}
		$oCnx = jDb::getConnection();
//jLog::dump($oCnx) ;
		return $oCnx;
	}
}
?>