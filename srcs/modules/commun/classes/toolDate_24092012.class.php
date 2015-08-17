<?php
/**
* @package		
* @subpackage	commun
* @version		1
* @author		
*/

/**
* Fonctions utilitaires pour les dates
*
* @package		
* @subpackage	commun
*/
class ToolDate {

	/**
	* Fonction de formatage d'une date 
	*
	* @param	string	$_zDate				Date au format local
	* @param    string  $_zFormatOrigine	Format d'origine de la date => LANG, DB
	* @param    string  $_zFormatFinal		Format final de la date => LANG, DB
	* @param	string	$_zTypeDateTime		Type de date/time : D=>date, T=>time, DT=>dateTime
	* @return	string	$zDateSql			Date au format mySql
	*
	*/
	 static function formaterDate($_zDate, $_zFormatOrigine="LANG", $_zFormatFinal="DB", $_zTypeDateTime="DT" ) {
	    
		$zDate= new jDateTime();
		eval("\$zFormatOrigine = jDateTime::" . $_zFormatOrigine . "_" . $_zTypeDateTime . "FORMAT;");
		eval("\$zFormatFinal = jDateTime::" . $_zFormatFinal . "_" . $_zTypeDateTime . "FORMAT;");
		$bValid=$zDate->setFromString($_zDate,$zFormatOrigine);
		if($bValid) {
			$zDate = $zDate->toString($zFormatFinal);
			return $zDate;
		}else{
			throw new jException('jelix~errors.datetime.invalid', array($zDate->year, $zDate->month, $zDate->day,
                $zDate->hour, $zDate->minute, $zDate->second));
		}		
	}  

	/**
	* function getDatesBetween
	* renvoie un tableau contenant toutes les dates, jour par jour,
	* comprises entre les deux dates passées en paramètre.
	* NB : les dates doivent être au format aaaa-mm-dd(mais on peut changer le parsing)
	* @param(string) $dStart : date de départ
	* @param(string) $dEnd : date de fin
	* @return(array) aDates : tableau des dates si succès
	* @return(bool) false : si échec
	*/
	static function getDatesBetween($dStart, $dEnd) {

	$iStart = strtotime($dStart);
		$iEnd = strtotime($dEnd);
		if(false === $iStart || false === $iEnd) {
			return false;
		}
		$aStart = explode('-', $dStart);
		$aEnd = explode('-', $dEnd);
		if(count($aStart) !== 3 || count($aEnd) !== 3) {
			return false;
		}
		if(false === checkdate($aStart[1], $aStart[2], $aStart[0]) || false === checkdate($aEnd[1], $aEnd[2], $aEnd[0]) || $iEnd <= $iStart) {
			return false;
		}
		$jkl = 0;
		for($i = $iStart; $i < $iEnd + 86400; $i = strtotime('+1 day', $i) ) {
			$sDateToArr = strftime('%Y-%m-%d', $i);
			$sYear = substr($sDateToArr, 0, 4);
			$sMonth = substr($sDateToArr, 5, 2);
			//$aDates[$sYear][$sMonth][] = $sDateToArr;
			$aDates[$jkl] = $sDateToArr;
			$jkl++;
		}
		if(isset($aDates) && !empty($aDates)) {
			return $aDates;
		} else {
			return false;
		}
	}

	static function getDatesTousLesXMois($_tDates, $_iDay, $_iMounthInterval){
		$tDate = array();
		$tDateFinal = array();
		foreach($_tDates as $oDates){
			$toDates = explode('-', $oDates);
			if(strlen($_iDay) == 1){
				$_iDay = '0'.$_iDay;
			}
			if($toDates[2] == $_iDay){
				array_push($tDate, $oDates); 				
			}
		}
		if(sizeof($tDate) > 0){
			for($i=$_iMounthInterval; $i<sizeof($tDate); ){
				array_push($tDateFinal, $tDate[$i]);
				$i = $i+$_iMounthInterval;
			}
		}
		return $tDateFinal; 
	}
	/**
	* Différence de 2 dates au format mySql. Résultat en secondes 
	*
	* @param	string	$_zDate_1			Date 1
	* @param	string	$_zDate_2			Date 2
	* @param    string  $_zFormatOrigine	Format d'origine de la date => LANG, DB
	* @param	string	$_zTypeDateTime		Type de date/time : D=>date, T=>time, DT=>dateTime
	* @return	integer	$iSeconde			Difference entre les 2 dates en seconde
	*/
	static function dateDiff($_zDate_1, $_zDate_2, $_zFormatOrigine="LANG", $_zTypeDateTime="DT") {

		eval("\$zFormatOrigine = jDateTime::" . $_zFormatOrigine . "_" . $_zTypeDateTime . "FORMAT;");

		$zDate1=new jDateTime();
		$bValid=$zDate1->setFromString($_zDate_1, $zFormatOrigine);

		if($bValid) {
			$zDate2=new jDateTime();
			$bValid=$zDate2->setFromString($_zDate_2, $zFormatOrigine);
			if($bValid) {
				$iSeconde=$zDate1->toDuration($zDate2, true);
				return $iSeconde;
			}else{
				throw new jException('jelix~errors.datetime.invalid', array($zDate2->year, $zDate2->month, $zDate2->day,
					$zDate2->hour, $zDate2->minute, $zDate2->second));
			}			
		}else{
			throw new jException('jelix~errors.datetime.invalid', array($zDate1->year, $zDate1->month, $zDate1->day,
                $zDate1->hour, $zDate1->minute, $zDate1->second));
		}
	} 

	/**
	* Formattage d'une date en toute lettre en fonction de la langue courante
	*
	* @param  string $_zDate             Date de la forme mySql
	* @param  string $_zFormatOrigine	 Format d'origine de la date => LANG, DB
	* @param  string $_zTypeDateTime	 Type de date/time : D=>date, T=>time, DT=>dateTime
	* @return string $zDateEnTouteLettre Date en toute lettre
	*/
	static function dateEnTouteLettre($_zDate, $_zFormatOrigine="LANG", $_zTypeDateTime="DT") {

		eval("\$zFormatOrigine = jDateTime::" . $_zFormatOrigine . "_" . $_zTypeDateTime . "FORMAT;");

		$zDate=new jDateTime();
		$bValid=$zDate->setFromString($_zDate, $zFormatOrigine);

		if($bValid) {
			$iMonth = $zDate->month;
			$iAn= $zDate->year;
			$iJour = $zDate->day;

			$iJourDansSemaine = date("w", $zDate->toString(jDateTime::TIMESTAMP_FORMAT));

			$tJours = explode(' ',jLocale::get('jelix~format.jours'));
			$tMois = explode(' ',jLocale::get('jelix~format.mois'));
			
			$zDateEnTouteLettre = jLocale::get('jelix~format.date_en_toute_lettre');
			$zDateEnTouteLettre = str_replace("%l",$tJours[$iJourDansSemaine],$zDateEnTouteLettre);
			$zDateEnTouteLettre = str_replace("%F",$tMois[$iMonth-1],$zDateEnTouteLettre);
			$zDateEnTouteLettre = str_replace("%d",$iJour,$zDateEnTouteLettre);
			$zDateEnTouteLettre = str_replace("%Y",$iAn,$zDateEnTouteLettre);
			
			return $zDateEnTouteLettre;
		}else{
			throw new jException('jelix~errors.datetime.invalid', array($zDate->year, $zDate->month, $zDate->day,
                $zDate->hour, $zDate->minute, $zDate->second));
		}
	} 

	/**
	* Formattage d'une date en toute lettre en fonction de la langue courante
	*
	* @param  string $_zDate             Date de la forme mySql
	* @param  string $_zFormatOrigine	 Format d'origine de la date => LANG, DB
	* @param  string $_zTypeDateTime	 Type de date/time : D=>date, T=>time, DT=>dateTime
	* @return string $zDateEnTouteLettre Date en toute lettre
	*/
	static function dateAbregeEnTouteLettre($_zDate, $_zFormatOrigine="LANG", $_zTypeDateTime="DT") {

		eval("\$zFormatOrigine = jDateTime::" . $_zFormatOrigine . "_" . $_zTypeDateTime . "FORMAT;");

		$zDate=new jDateTime();
		$bValid=$zDate->setFromString($_zDate, $zFormatOrigine);

		if($bValid) {
			$iMonth = $zDate->month;
			$iAn= $zDate->year;
			$iJour = $zDate->day;

			$iJourDansSemaine = date("w", $zDate->toString(jDateTime::TIMESTAMP_FORMAT));

			$tJours = explode(' ',jLocale::get('jelix~format.joursAbreges'));
			$tMois = explode(' ',jLocale::get('jelix~format.moisAbreges'));
			
			$zDateEnTouteLettre = jLocale::get('jelix~format.date_en_toute_lettre');
			$zDateEnTouteLettre = str_replace("%l",$tJours[$iJourDansSemaine],$zDateEnTouteLettre);
			$zDateEnTouteLettre = str_replace("%F",$tMois[$iMonth-1],$zDateEnTouteLettre);
			$zDateEnTouteLettre = str_replace("%d",$iJour,$zDateEnTouteLettre);
			$zDateEnTouteLettre = str_replace("%Y",$iAn,$zDateEnTouteLettre);
			
			return $zDateEnTouteLettre;
		}else{
			throw new jException('jelix~errors.datetime.invalid', array($zDate->year, $zDate->month, $zDate->day,
                $zDate->hour, $zDate->minute, $zDate->second));
		}
	} 

	/**
	* A partir d'une date, renvoi le mois en toute lettre en fonction de la langue courante
	*
	* @param  string $_zDate             Date
	* @param  string $_zFormatOrigine	 Format d'origine de la date => LANG, DB
	* @param  string $_zTypeDateTime	 Type de date/time : D=>date, T=>time, DT=>dateTime
	* @return string $zMoisEnTouteLettre Date en toute lettre
	*/
	static function moisEnTouteLettre($_zDate, $_zFormatOrigine="LANG", $_zTypeDateTime="DT") {

		eval("\$zFormatOrigine = jDateTime::" . $_zFormatOrigine . "_" . $_zTypeDateTime . "FORMAT;");

		$zDate=new jDateTime();
		$bValid=$zDate->setFromString($_zDate, $zFormatOrigine);

		if($bValid) {
			$iMonth = $zDate->month;
			$tMois = explode(' ',jLocale::get('jelix~format.mois'));
			$zMoisEnTouteLettre = $tMois[$iMonth];

			return $zMoisEnTouteLettre;

		}else{
			throw new jException('jelix~errors.datetime.invalid', array($zDate->year, $zDate->month, $zDate->day,
                $zDate->hour, $zDate->minute, $zDate->second));
		}
	} 

	/**
	* A partir d'une date, renvoi le jour de la semaine en toute lettre en fonction de la langue courante
	*
	* @param  string $_zDate             Date
	* @param  string $_zFormatOrigine	 Format d'origine de la date => LANG, DB
	* @param  string $_zTypeDateTime	 Type de date/time : D=>date, T=>time, DT=>dateTime
	* @return string $zMoisEnTouteLettre Date en toute lettre
	*/
	static function jourEnTouteLettre($_zDate, $_zFormatOrigine="LANG", $_zTypeDateTime="DT") {

		eval("\$zFormatOrigine = jDateTime::" . $_zFormatOrigine . "_" . $_zTypeDateTime . "FORMAT;");

		$zDate=new jDateTime();
		$bValid=$zDate->setFromString($_zDate, $zFormatOrigine);

		if($bValid) {
			$iJourDansSemaine = date("w", $zDate->toString(jDateTime::TIMESTAMP_FORMAT));
			$tJours = explode(' ',jLocale::get('jelix~format.jours'));
			$zJourEnTouteLettre = $tJours[$iJourDansSemaine];
			return $zJourEnTouteLettre;
		}else{
			throw new jException('jelix~errors.datetime.invalid', array($zDate->year, $zDate->month, $zDate->day,
                $zDate->hour, $zDate->minute, $zDate->second));
		}
	} 

	/**
	* Vérifie si la date est valide
	*
	* @param  string 	$_zDate				Date
	* @param  string 	$_zFormatOrigine	Format d'origine de la date => LANG, DB
	* @param  string 	$_zTypeDateTime		Type de date/time : D=>date, T=>time, DT=>dateTime
	* @return boolean	$bValid
	*/
	public static function isValidDate($_zDate, $_zFormatOrigine="LANG", $_zTypeDateTime="DT")
	{
		eval("\$zFormatOrigine = jDateTime::" . $_zFormatOrigine . "_" . $_zTypeDateTime . "FORMAT;");

		$zDate=new jDateTime();
		$bValid=$zDate->setFromString($_zDate, $zFormatOrigine);

		return $bValid;
	}

	static function getDateFormatYYYYMMDD($_zDate){
		$zSql = "SELECT DATE_FORMAT('".$_zDate."', '%Y-%m-%d') as dates"; 
		$oCnx = jDb::getConnection();
		$Rs = $oCnx->query($zSql);
		$oRecord=$Rs->fetch();
		return $oRecord->dates;
	}

	static function getDateFormatDDMMYYToYYYYMMDD($_zDate){
		$zSql = "SELECT DATE_FORMAT('".$_zDate."', '%Y-%m-%d') as dates"; 
		$oCnx = jDb::getConnection();
		$Rs = $oCnx->query($zSql);
		$oRecord=$Rs->fetch();
		return $oRecord->dates;
	}

	static function getDateFormatDD($_zDate){
		$zSql = "SELECT DATE_FORMAT('".$_zDate."', '%W') as day"; 
		$oCnx = jDb::getConnection();
		$Rs = $oCnx->query($zSql);
		$oRecord=$Rs->fetch();
		return $oRecord->day;
	}

	static function getDateFormatDDParNumero($_zDate){
		$zSql = "SELECT DATE_FORMAT('".$_zDate."', '%w') as day"; 
		$oCnx = jDb::getConnection();
		$Rs = $oCnx->query($zSql);
		$oRecord=$Rs->fetch();
		return $oRecord->day;
	}
	
	/**
	* Fonction de formatage de date FR en date EN(format mysql)
	*
	* @param		string	$_zDatefr	Date FR
	* @return	string	$zDatesql	Date UK(ou NULL)
	*/
	 static function toDateSQL($_zDatefr) {
	    $zDate =  trim($_zDatefr);    
		
		$zSeparateur = strrpos($zDate, "/")?'/':'-';

		$tD = explode($zSeparateur,$zDate);

		if($tD[0]<>"") {
			$zDatesql = $tD[2]."-".$tD[1]."-".$tD[0];
			return $zDatesql;
		}
		return "NULL";
	}  // FIN : toDateSQL()

	/**
	* Fonction de formatage de date format mysql en date FR
	*
	* @param		string	$_zDatesql	Date FR
	* @return	string	$zDatefr		Date FR(ou cha?ne vide)
	*/

	static function toDateFR($_zDatesql) {
		$_zDatesql = trim($_zDatesql);
		if(strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);
			//print_r($d);
			$zDatefr = $tD[2]."/".$tD[1]."/".$tD[0];
			return $zDatefr;
		}
		return "";
	}  // FIN : toDateFR()

	/**
	* Fonction de formatage de date format mysql en date spécifique pour Reghalal
	*
	* @param	string	$_zDatesql		Date FR
	* @return	string	$zDateReghalal		
	*/
	static function toDateWebCalendarFin($_zDatesql){
		$_zDatesql = trim($_zDatesql);
		if(strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);
			$zMounth = "";
			switch($tD[1]){
				case '01': $zMounth = "Janvier"; break;
				case '02': $zMounth = "Février"; break;
				case '03': $zMounth = "Mars"; break;
				case '04': $zMounth = "Avril"; break;
				case '05': $zMounth = "Mai"; break;
				case '06': $zMounth = "Juin"; break;
				case '07': $zMounth = "Juillet"; break;
				case '08': $zMounth = "Août"; break;
				case '09': $zMounth = "Septembre"; break;
				case '10': $zMounth = "Octobre"; break;
				case '11': $zMounth = "Novembre"; break;
				case '12': $zMounth = "Décembre"; break;
			}
			
			$zDateReghalal = $tD[2] . " " . $zMounth . " " . $tD[0];
			return $zDateReghalal;
		}
		return "";
	}

	static function toDateWebCalendarDebut($_zDatesql){
		$_zDatesql = trim($_zDatesql);
		if(strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);
			$zMounth = "";
			switch($tD[1]){
				case '01': $zMounth = "Janvier"; break;
				case '02': $zMounth = "Février"; break;
				case '03': $zMounth = "Mars"; break;
				case '04': $zMounth = "Avril"; break;
				case '05': $zMounth = "Mai"; break;
				case '06': $zMounth = "Juin"; break;
				case '07': $zMounth = "Juillet"; break;
				case '08': $zMounth = "Août"; break;
				case '09': $zMounth = "Septembre"; break;
				case '10': $zMounth = "Octobre"; break;
				case '11': $zMounth = "Novembre"; break;
				case '12': $zMounth = "Décembre"; break;
			}
			
			$zDateReghalal = $tD[2] . " " . $zMounth . " " . $tD[0];
			return $zDateReghalal;
		}
		return "";
	}

	static function toDateWebCalendarDebutBis($_zDatesql){
		$_zDatesql = trim($_zDatesql);
		if(strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);
			$zMounth = "";
			switch($tD[1]){
				case '01': $zMounth = "Janvier"; break;
				case '02': $zMounth = "Février"; break;
				case '03': $zMounth = "Mars"; break;
				case '04': $zMounth = "Avril"; break;
				case '05': $zMounth = "Mai"; break;
				case '06': $zMounth = "Juin"; break;
				case '07': $zMounth = "Juillet"; break;
				case '08': $zMounth = "Août"; break;
				case '09': $zMounth = "Septembre"; break;
				case '10': $zMounth = "Octobre"; break;
				case '11': $zMounth = "Novembre"; break;
				case '12': $zMounth = "Décembre"; break;
			}
			
			$zDateReghalal = $tD[2];
			return $zDateReghalal;
		}
		return "";
	}

	static function toDateWebCalendarForXls($_zDatesql){
		$_zDatesql = trim($_zDatesql);
		if(strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_tzDatesql = explode(" ", $_zDatesql);
			$tD = explode('-',$_tzDatesql[0]);
			$zMounth = "";
			switch($tD[1]){
				case '01': $zMounth = "Janvier"; break;
				case '02': $zMounth = "Février"; break;
				case '03': $zMounth = "Mars"; break;
				case '04': $zMounth = "Avril"; break;
				case '05': $zMounth = "Mai"; break;
				case '06': $zMounth = "Juin"; break;
				case '07': $zMounth = "Juillet"; break;
				case '08': $zMounth = "Août"; break;
				case '09': $zMounth = "Septembre"; break;
				case '10': $zMounth = "Octobre"; break;
				case '11': $zMounth = "Novembre"; break;
				case '12': $zMounth = "Décembre"; break;
			}
			
			$zDateHeurFr = $tD[2] . " " . $zMounth . " " . $tD[0] . " à " . $_tzDatesql[1];
			return $zDateHeurFr;
		}
		return "";
	}

	/**
	* Fonction de formatage de date format mysql en mois et jour de la semaine en toute lettre
	*
	* @param	string	$_zDatesql		Date FR
	* @param	boolean	$_bYear
	* @param	boolean	$_bDay
	* @return	string	$zDateReghalal		
	*/
	static function toDateSortie($_zDatesql,$_bYear = true,$_bDay = false){
		$_zDatesql = trim($_zDatesql);
		if(strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);
			$zMounth = "";
			switch($tD[1]){
				case '01': $zMounth = "Janvier"; break;
				case '02': $zMounth = "Février"; break;
				case '03': $zMounth = "Mars"; break;
				case '04': $zMounth = "Avril"; break;
				case '05': $zMounth = "Mai"; break;
				case '06': $zMounth = "Juin"; break;
				case '07': $zMounth = "Juillet"; break;
				case '08': $zMounth = "Août"; break;
				case '09': $zMounth = "Septembre"; break;
				case '10': $zMounth = "Octobre"; break;
				case '11': $zMounth = "Novembre"; break;
				case '12': $zMounth = "Décembre"; break;
			}
			$timestamp = mktime(0, 0, 0, $tD[1], $tD[2], $tD[0]);
			$joursem = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
			$zJour = $joursem[date("w",$timestamp)];
			if(!$_bDay){
				if($_bYear){
					$zDateReghalal = $zJour . " " . $tD[2] . " " . $zMounth . " " . $tD[0];
				} else {
					$zDateReghalal = $zJour . " " . $tD[2] . " " . $zMounth;
				}
			} else {
				if($_bYear){
					$zDateReghalal =  $tD[2] . " " . $zMounth . " " . $tD[0];
				} else {
					$zDateReghalal =  $tD[2] . " " . $zMounth;
					
				}
			}
			return $zDateReghalal;
		}
		return "";
	}

	/**
	* Convertion nombre entier en time de mysql
	* @param int $_iDuree
	* @return int
	*/
	static function intToTimeMinute($_iDuree){
		if($_iDuree > 0){
			if($_iDuree >= 60){
				$iH = round($_iDuree/60);  
				$iM = round($_iDuree%60);  
				if($iH < 10){
					$iH = '0'.$iH;
				}
				if($iM < 10){
					$iM = '0'.$iM;
				}
				$zDuree = $iH.':'.$iM.':00';
			}else{
				$zDuree = '00:'.$_iDuree.':00';
			}
			return $zDuree;
		}else{
			return $_iDuree;
		}
	}

	/**
	* Récupère toutes les dates
	* du jour de l'année
	* pour un jour donné
	* @param int $_iDayId id du jour(0,..,6) Dimanche Lundi ..
	* @param int $_iMonthId id du mois(1,..,12)
	*/
	static function getDateByDay($_iDayId, $_iMonthId){
		
		$Y = date("Y");
		$oDay = new stdClass();
		$oDay->mois_libelle = self::getMonthById($_iMonthId);
		$oDay->mois_id = $_iMonthId;
		$oDay->datedujour = array();
		$oDay->moisjour = array();

		$zMonthDays		= date('t', strtotime(date($_iMonthId . '/01/' . $Y)));
		
		$zMonthFirstDay = date('w', strtotime(date($_iMonthId . '/01/' . $Y)));
		
		for($i = 1, $j = intval($zMonthFirstDay), $w = 1;$i <= intval($zMonthDays);$i++) {
			if($j==$_iDayId){
				array_push($oDay->datedujour, $i);
				array_push($oDay->moisjour, date($Y.'-'.$_iMonthId.'-'.$i));
			}
			$j++;
			if($j == 7) {
				$j = 0;
				$w++;
			}
		}
		$i = 1;
		
		return $oDay;

	}
		
		/**
		* Récupère Le mois à partit
		* de son Id
		* @param int $_iMonthId id d mois
		*/
		static function getMonthById($_iMonthId){
			$zMounth = "";
			switch($_iMonthId){
				case '1': $zMounth = "Janvier"; break;
				case '2': $zMounth = "Février"; break;
				case '3': $zMounth = "Mars"; break;
				case '4': $zMounth = "Avril"; break;
				case '5': $zMounth = "Mai"; break;
				case '6': $zMounth = "Juin"; break;
				case '7': $zMounth = "Juillet"; break;
				case '8': $zMounth = "Août"; break;
				case '9': $zMounth = "Septembre"; break;
				case '10': $zMounth = "Octobre"; break;
				case '11': $zMounth = "Novembre"; break;
				case '12': $zMounth = "Décembre"; break;
			}
			return $zMounth;
		}

		/**
		* Récupère Le jour à séLectionner
		* @param object $toMercredi
		* @return  array $toMercredi
		*/
		static function selectDay($toMercredi){
			
			$iMoisEnCours = date("n");
			$iJoursEnCours = date("j");
			$iMoisPrecedent = 0;
			
			if($toMercredi[sizeof($toMercredi)-1]->mois_id == $iMoisEnCours-1){
				$toMercredi[sizeof($toMercredi)-1]->isSelected = $toMercredi[sizeof($toMercredi)-1]->datedujour[sizeof($toMercredi[sizeof($toMercredi)-1]->datedujour) - 1];
			}else{
				foreach($toMercredi as $oMercredi){
					foreach($oMercredi->datedujour as $val){
						if($iMoisEnCours==$oMercredi->mois_id){

							if(in_array($iJoursEnCours, $oMercredi->datedujour) ){
								if($val==$iJoursEnCours ){
									$oMercredi->isSelected = $val;
								}
							}elseif(intval(date('w', strtotime(date($oMercredi->mois_id.'/01/'. date("Y")))))!=3 && 
								$iJoursEnCours<$oMercredi->datedujour[0]){
								
								$iMoisPrecedent = $iMoisEnCours - 1;
							}else{
								
								if($iJoursEnCours>$val && $iJoursEnCours<$val+7){
									$oMercredi->isSelected= $val;
								}
							}
						}
					}
				}
			}

			if($iMoisPrecedent == $iMoisEnCours - 1){
				foreach($toMercredi as $oMercredi){
					foreach($oMercredi->datedujour as $val){
						if($iMoisPrecedent==$oMercredi->mois_id){
								$oMercredi->isSelected = $oMercredi->datedujour[sizeof($oMercredi->datedujour)-1];
						}
					}
				}
			}
			return $toMercredi;
		}

		/**
		* Fonction de formatage de date format mysql en mois et jour de la semaine en toute lettre
		*
		* @param	string	$_zDatesql		Date FR
		* @return	string	$zDateReghalal		
		*/
		static function toDateFrEnTouteLettre($_zDatesql){
		$_zDatesql = trim($_zDatesql);
		if(strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_zDatesql = substr($_zDatesql, 0,10);
			$tD = explode('-',$_zDatesql);
			$zMounth = "";
			switch($tD[1]){
				case '01': $zMounth = "Janvier"; break;
				case '02': $zMounth = "Février"; break;
				case '03': $zMounth = "Mars"; break;
				case '04': $zMounth = "Avril"; break;
				case '05': $zMounth = "Mai"; break;
				case '06': $zMounth = "Juin"; break;
				case '07': $zMounth = "Juillet"; break;
				case '08': $zMounth = "Août"; break;
				case '09': $zMounth = "Septembre"; break;
				case '10': $zMounth = "Octobre"; break;
				case '11': $zMounth = "Novembre"; break;
				case '12': $zMounth = "Décembre"; break;
			}
			
			$zDateReghalal = $tD[1] . " " .$zMounth . " " . $tD[0];
			return $zDateReghalal;
		}
		return "";
	}

	/**
	* récupère les jours $_iNbDay à partir de la date du jour
	* @param int $_iNbDay
	* @return array $tDate
	*/
	static function getNextDays($_iNbDay){
		$tDate = array();

		for($i=0;$i<=$_iNbDay;$i++){
			$tResult = array();
			$tResult["toutelettre"] = self::toDateSortie(date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y"))),false);
			$tResult["simple"] = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y")));
			
			$tResult["sansjour"] = self::toDateSortie(date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y"))),false,true);
			array_push($tDate, $tResult);
		}
		return $tDate;
	}

	/**
	* Récupère une tranche d'heure
	* @param array $_tTrancheHeure
	* @return object $oTranche
	*/
	static function getTrancheHeure($_tTrancheHeure){
		$oTranche = new stdClass();
		$oTranche->timeInf  = $_tTrancheHeure[0];
		$oTranche->timeSup  = $_tTrancheHeure[1];

		if($_tTrancheHeure[0]==0){
			$tPrev[0] = 22;
			$tPrev[1] = 24;
		}else{			
			$tPrev[0] = $_tTrancheHeure[0]-2;
			$tPrev[1] = $_tTrancheHeure[0];
		}
		$oTranche->zTrancheHeurePrec  = implode('/', $tPrev);

		if($_tTrancheHeure[1]==24){
			$tNext[0] = 0;
			$tNext[1] = 2;
		}else{
			$tNext[0] = $_tTrancheHeure[1];	
			$tNext[1] = $_tTrancheHeure[1]+2;
		}

		$oTranche->zTrancheHeureSuiv  = implode('/', $tNext);

		return $oTranche;

	}

	/**
	* retourne une liste d'heure
	* @return array $tHour
	*/
	static function getListHour(){
		$tHour = array();
		$j = 0;
		$k = 2;
		for($i=0;$i<12;$i++){
			$oHour = new stdClass();
			$p =($j<10)? 0: null;
			$q =($k<10)? 0: null;

			$oHour->interval = $p.$j.'h - '.$q.$k.'h';
			$oHour->limit	 = $j.'/'.$k;
			$j = $j+2;
			$k = $k+2;
			array_push($tHour, $oHour);
		}
		return $tHour;
	}

	/**
	* date sql en h min
	* @param array $_toObject objet à traiter
	* @return array $_toObject
	*/
	static function getFormatHour($_toObject){
		if(sizeof($_toObject)>0){
			foreach($_toObject as $oProgrammeBouquetTv){
				$tDateTime = explode(" ",$oProgrammeBouquetTv->programme_dateDebutDiffusion);
				$tTime = explode(":",$tDateTime[1]);
				$oProgrammeBouquetTv->programme_dateDebutDiffusion = $tTime[0]."h".$tTime[1];
				$tDuree = explode(":",$oProgrammeBouquetTv->programme_duree);
				$oProgrammeBouquetTv->programme_duree =  $tDuree[0]."h".$tDuree[1]."min";
			}
		}
		return $_toObject;
	}

	/**
	* Formattage d'une date en format longue : Dimanche 15 janvier 2005
	*
	* @param string $_zStrDateInput Date de la forme YYYY-MM-JJ
	* @param string $_zSeparateur Séparateur
	* @param string $_zLangue Langue(fr/en, par défaut fr)
	* @return string $zDateResult
	*/

	static function formatToLongDate($_zStrDateInput, $_zSeparateur="-", $_zLangue  = 'fr') {
		list($iYear, $iMonth, $iDay)=explode($_zSeparateur, $_zStrDateInput);
		$_zStrDateInput = mktime(0,0,0,$iMonth, $iDay, $iYear);
		$zFormat = "w";
		$iDay = date($zFormat, $_zStrDateInput);
		$zFormat = "n";
		$iMonth = date($zFormat, $_zStrDateInput);

		if($_zLangue == 'en') {
			$tTabDays = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday",
							"Friday", "Saturday");
			$tTabMonths = array("january", "february", "march", "april", "mai", "june", "july",
								"august", "september", "october", "november", "december");
			$zDateResult = $tTabDays[$iDay] . ", " .  $tTabMonths[$iMonth-1] . " " .
			date("d", $_zStrDateInput). ", " . date("Y", $_zStrDateInput);
		} else {
			$tTabDays = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
			$tTabMonths = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
			$zDateResult = $tTabDays[$iDay] . " " . date("d", $_zStrDateInput) . " " . $tTabMonths[$iMonth-1] .
			" " . date("Y", $_zStrDateInput);
		}

		return $zDateResult;
	}

	/**
	*
	* Fonction qui vous permettra de connaître la date
	* de début d'une semaine à partir de la date du jour 
	*/

	static function debutsem($year,$month,$day) {
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?7:$num_day)+1,$year);
		$datedeb      = date('d-m-Y', $premier_jour);
		return self::toDateWebCalendarDebutBis(self::toDateSQL($datedeb));
	}

	static function finsem($year,$month,$day) {
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$dernier_jour = mktime(0,0,0, $month,7-(!$num_day?7:$num_day)+$day,$year);
		$datedeb      = date('d-m-Y', $dernier_jour);
		return self::toDateWebCalendarFin(self::toDateSQL($datedeb));
	}

	static function getListeDateSemaine($_zDate){
		$tDate = array();
		for($i=0; $i<7; $i++){
			$oDBW	  = jDb::getDbWidget() ;
			$oDate = $oDBW->fetchFirst("SELECT DATE_ADD('".$_zDate."', INTERVAL ".$i." DAY) AS zDate "); 
			array_push($tDate, $oDate->zDate) ;
		}
		return $tDate;
	}

	static function getIntervalDateAffichageParMois($_zDate){
		$oDBW	  = jDb::getDbWidget() ;
		$oDate = $oDBW->fetchFirst("SELECT DATE_SUB('".$_zDate."', INTERVAL 3 MONTH) AS zDateDebut, DATE_ADD('".$_zDate."', INTERVAL 3 MONTH) AS zDateFin "); 
		return $oDate;
	}

	static function getIntervalDateByIntervalDay($_zDate, $_iInterval){
		$oDBW	  = jDb::getDbWidget() ;
		$oDate = $oDBW->fetchFirst("SELECT DATE_SUB('".$_zDate."', INTERVAL ".$_iInterval." DAY) AS zDate"); 
		return $oDate->zDate;
	}	

	static function getListeDateSemaineSansWE($_zDate){
		$tDate = array();
		for($i=0; $i<7; $i++){
			$oDBW	  = jDb::getDbWidget() ;
			$oDate = $oDBW->fetchFirst("SELECT DATE_ADD('".$_zDate."', INTERVAL ".$i." DAY) AS zDate "); 
			array_push($tDate, $oDate->zDate) ;
		}

		//unset($tDate[sizeof($tDate)-2]);
		//unset($tDate[sizeof($tDate)-1]);
		unset($tDate[5]);
		unset($tDate[6]);
		return $tDate;
	}

	static function selectNumeroSemaine($_zdateDebut){
		$zSql = " SELECT DATE_FORMAT(SUBDATE('" . $_zdateDebut . "', INTERVAL 7 DAY), '%u') as iNumeroSemainePrecedente, DATE_FORMAT('" . $_zdateDebut . "' , '%u' ) as iNumeroSemaine, DATE_FORMAT(ADDDATE('" . $_zdateDebut . "', INTERVAL 7 DAY), '%u') as iNumeroSemaineSuivante" ;
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		return $toResults[0];		
	}

	static function selectDateDebutSemaineSuivante($_zDatefin){
		$zSql = " SELECT DATE_FORMAT(ADDDATE('" . $_zDatefin . "', INTERVAL 1 DAY), '%Y-%m-%d') as zDateDebutSemaineSuivante	";
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		return $toResults[0]->zDateDebutSemaineSuivante;		 
	}

	static function selectDateDebutSemainePrecedente($_zDateDebut){
		$zSql = " SELECT DATE_FORMAT(SUBDATE('" . $_zDateDebut . "', INTERVAL 7 DAY), '%Y-%m-%d') as zDateDebutSemainePrecedente	";
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		return $toResults[0]->zDateDebutSemainePrecedente;		 
	}

	static function getMoisEnTouteLettre($_zMois){
		$zMois_fr = "";
		switch($_zMois){
			case '01' : $zMois_fr = 'Janvier'; break;
			case '02' : $zMois_fr = 'Février'; break;
			case '03' : $zMois_fr = 'Mars'; break;
			case '04' : $zMois_fr = 'Avril'; break;
			case '05' : $zMois_fr = 'Mai'; break;
			case '06' : $zMois_fr = 'Juin'; break;
			case '07' : $zMois_fr = 'Juillet'; break;
			case '08' : $zMois_fr = 'Aout'; break;
			case '09' : $zMois_fr = 'Septembre'; break;
			case '10' : $zMois_fr = 'Octobre'; break;
			case '11' : $zMois_fr = 'Novembre'; break;
			case '12' : $zMois_fr = 'Decembre'; break;
		}
		return $zMois_fr;
	}

	static function getMoisEnChiffre($_zMois){
		switch($_zMois){
			case '01' : $iMois = 1; break;
			case '02' : $iMois = 2; break;
			case '03' : $iMois = 3; break;
			case '04' : $iMois = 4; break;
			case '05' : $iMois = 5; break;
			case '06' : $iMois = 6; break;
			case '07' : $iMois = 7; break;
			case '08' : $iMois = 8; break;
			case '09' : $iMois = 9; break;
			case '10' : $iMois = 10; break;
			case '11' : $iMois = 11; break;
			case '12' : $iMois = 12; break;
		}
		return $iMois;
	}

	static function getTousLesJoursDuMois(){
		$mois_fr = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Décembre");
		$jour_fr = array("lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche");
		 
		$y = date("Y"); // Année en cours
		 
		$mois = 4; // Mois de Mars
		 
		$nb_jours = date("t", mktime(0, 0, 0, $mois, 1, $y )); // Nombre de jours dans le mois
		$tzDateFr = array();
		for($i = 0; $i <= $nb_jours; $i++)
		{
			$jour = mktime(0, 0, 0, $mois, $i, $y );
			array_push($tzDateFr, $jour_fr[date("w", $jour)].' '.date("d",$jour).' '.$mois_fr[$mois].' '.$y);
		}
		return $tzDateFr;
	}

	static function getDatePrecSuiv($_zDate){
		$zSql = "SELECT DATE_FORMAT(DATE_ADD('".$_zDate."', INTERVAL 1 DAY), '%Y-%m-%d') AS jourSuiv, DATE_FORMAT(DATE_SUB('".$_zDate."', INTERVAL 1 DAY), '%Y-%m-%d') AS jourPrec"; 	
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		return $toResults[0];		 
	}

	static function getDateSuiv($_zDate){
		$zSql = "SELECT DATE_FORMAT(DATE_ADD('".$_zDate."', INTERVAL 1 DAY), '%Y-%m-%d') AS jourSuiv"; 	
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		return $toResults[0]->jourSuiv;		 
	}

	static function dateDiffBySql ($_zDateDebut, $_zCurrentDate){
		$zSql = "SELECT DATEDIFF('".$_zDateDebut."', '".$_zCurrentDate."') AS diff"; 	
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		return $toResults[0]->diff;		 
	}

	static function getShortDayFr($_zDateEn){
		$zSql = "SELECT DATE_FORMAT('".$_zDateEn."', '%a') AS jourEn"; 	
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		switch($toResults[0]->jourEn){
			case 'Sun': $zJourFr = 'Dim'; break;
			case 'Mon': $zJourFr = 'Lun'; break;
			case 'Tue': $zJourFr = 'Mar'; break;
			case 'Tue': $zJourFr = 'Mar'; break;
			case 'Wed': $zJourFr = 'Mer'; break;
			case 'Thu': $zJourFr = 'Jeu'; break;
			case 'Fri': $zJourFr = 'Ven'; break;
			case 'Sat': $zJourFr = 'Sam'; break;
			default: $zJourFr = 'Lun'; 
		}
		return $zJourFr;
	}

	static function getDateParIntervalSeptJours($_zDate, $_iOccurence){
		$iInterval = 7 * $_iOccurence;
		$zSql = "SELECT DATE_ADD('" . $_zDate . "', INTERVAL " . $iInterval . " DAY) AS zDate"; 	
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;

		return $toResults[0]->zDate;
	}

	static function periodiciteQuotidienneGetDateNombreOccurence($_iTousLesXJour, $_iOccurence, $_zDateDebut){
		$tResults = array();

		for($i=$_iTousLesXJour; $i<=($_iOccurence*2);){
			$zSql = "SELECT DATE_ADD('" . $_zDateDebut . "', INTERVAL " . $i . " DAY) AS zDate"; 	
			$i=$i+$_iTousLesXJour;
			$oDBW	  = jDb::getDbWidget() ;
			$toResults = $oDBW->fetchAll($zSql) ;
			array_push($tResults, $toResults[0]->zDate);
		}

		return $tResults;
	}

	static function periodiciteQuotidienneGetDateParDateDeFin($_iTousLesXJour, $_tDate, $_zDateDebut){
		$tResults = array();
		for($i=0; $i<sizeof($_tDate);){
			$zSql = "SELECT DATE_ADD('" . $_zDateDebut . "', INTERVAL " . $i . " DAY) AS zDate"; 	
			$i=$i+$_iTousLesXJour;
			$oDBW	  = jDb::getDbWidget() ;
			$toResults = $oDBW->fetchAll($zSql) ;
			if(in_array($toResults[0]->zDate, $_tDate)){
				array_push($tResults, $toResults[0]->zDate);
			}
		}
		return $tResults;
	}

	static function periodiciteQuotidienneGetDateHebdomadaireParOccurence($_iTousLesXSemaine, $_iOccurence, $_zDateDebut, $_toParams){
		$tResults = array();
		$tMyTabloFinal = array();
		$tTmpDateFinal = array();
		$tDateFinal = array();

		for($i=$_iTousLesXSemaine; $i<=($_iOccurence*2);){
			$zSql = "SELECT DATE_ADD('" . $_zDateDebut . "', INTERVAL " . $i . " WEEK) AS zDate"; 	
			$i=$i+$_iTousLesXSemaine;
			$oDBW	  = jDb::getDbWidget() ;
			$toResults = $oDBW->fetchAll($zSql);
			$tResults[0] = $_zDateDebut;
			array_push($tResults, $toResults[0]->zDate);
		}

		foreach($tResults as $oResults){
			$toResults = explode(' ', $oResults);
			$tzDate = explode('-', $toResults[0]);
			$zSql = "SELECT WEEK('" . $oResults . "') AS iWeek"; 	
			$i=$i+$_iTousLesXSemaine;
			$oDBW	  = jDb::getDbWidget() ;
			$toResultWeeks = $oDBW->fetchAll($zSql);
			$tMyTablo = self::GetJoursFromWeek($toResultWeeks[0]->iWeek, $tzDate[0]);
			array_push($tMyTabloFinal, $tMyTablo);
		}

		foreach($tMyTabloFinal as $oMyTabloFinal){
			foreach($oMyTabloFinal as $oTmpMyTabloFinal){
				if($_toParams['evenement_iLundi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Monday"){
					array_push($tTmpDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
				if($_toParams['evenement_iMardi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Tuesday"){
					array_push($tTmpDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
				if($_toParams['evenement_iMercredi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Wednesday"){
					array_push($tTmpDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
				if($_toParams['evenement_iJeudi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Thursday"){
					array_push($tTmpDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
				if($_toParams['evenement_iVendredi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Friday"){
					array_push($tTmpDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
			}
		}

		for($i=0; $i<sizeof($tTmpDateFinal); $i++){
			if(sizeof($tDateFinal) < $_iOccurence){
				array_push($tDateFinal, $tTmpDateFinal[$i]);
			}
		}

		return $tDateFinal;
	}

	static function periodiciteQuotidienneGetDateHebdomadaireParDateDeFin($_iTousLesXSemaine, $_tDate, $_zDateDebut, $_toParams){
		$tResults = array();
		$tMyTabloFinal = array();
		$tTmpDateFinal = array();
		$tDateFinal = array();

		for($i=0; $i<sizeof($_tDate);){
			$zSql = "SELECT DATE_ADD('" . $_zDateDebut . "', INTERVAL " . $i . " WEEK) AS zDate; "; 	
			$i=$i+$_iTousLesXSemaine;
			$oDBW	  = jDb::getDbWidget() ;
			$toResults = $oDBW->fetchAll($zSql) ;
			$tResults[0] = $_zDateDebut;
			if(in_array($toResults[0]->zDate, $_tDate)){
				array_push($tResults, $toResults[0]->zDate);
			}
		}


		foreach($tResults as $oResults){
			$toResults = explode(' ', $oResults);
			$tzDate = explode('-', $toResults[0]);
			$zSql = "SELECT WEEK('" . $oResults . "') AS iWeek"; 	
			$oDBW	  = jDb::getDbWidget() ;
			$toResultWeeks = $oDBW->fetchAll($zSql);
			$tMyTablo = self::GetJoursFromWeek($toResultWeeks[0]->iWeek, $tzDate[0]);
			array_push($tMyTabloFinal, $tMyTablo);
		}

		foreach($tMyTabloFinal as $oMyTabloFinal){
			foreach($oMyTabloFinal as $oTmpMyTabloFinal){
				if($_toParams['evenement_iLundi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Monday"){
					array_push($tDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
				if($_toParams['evenement_iMardi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Tuesday"){
					array_push($tDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
				if($_toParams['evenement_iMercredi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Wednesday"){
					array_push($tDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
				if($_toParams['evenement_iJeudi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Thursday"){
					array_push($tDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
				if($_toParams['evenement_iVendredi'] == 1 && self::getDateFormatDD(self::toDateSQL($oTmpMyTabloFinal)) == "Friday"){
					array_push($tDateFinal, self::toDateSQL($oTmpMyTabloFinal)); 
				}
			}
		}
		return $tDateFinal;
	}	

	static function GetJoursFromWeek($week, $year){

		$date = mktime(0, 0, 0, 1, 4, $year);
		$jour_semaine = date("N", $date);
		$diffYear=date("Y")-$year; // nouvelle ligne
		$lundi = $date-86400*($jour_semaine-$diffYear)+604800*($week-1); // ajout de $diffYear a la place de 1 dans la soustraction

		for($i=0; $i<7; $i++)
		$Tablo[] = date("d-m-Y", $lundi +($i*60*60*24));
		$FinalTablo = array();
		for($i=1; $i<sizeof($Tablo)-1; $i++){
			array_push($FinalTablo, $Tablo[$i]);
		}
		return $FinalTablo;
	}

	static function periodiciteQuotidienneGetDateMensuelleParOccurence($_iLeXDeChaqueMois, $_iTousLesXMois, $_zDateDebut, $_iOccurence){
		$tResults = array();
		$tResult = array();
		$tFinalResults = array();
		if($_iLeXDeChaqueMois < 10){
			$zLeXDeChaqueMois = '0'.$_iLeXDeChaqueMois;
		}else{
			$zLeXDeChaqueMois = $_iLeXDeChaqueMois;
		}

		for($i=$_iTousLesXMois; $i<=($_iOccurence*$_iTousLesXMois)-1;){
			$zSql = "SELECT DATE_ADD('" . $_zDateDebut . "', INTERVAL " . $i . " MONTH) AS zDate"; 	
			$i=$i+$_iTousLesXMois;
			$oDBW	  = jDb::getDbWidget() ;
			$toResults = $oDBW->fetchAll($zSql) ;
			$tResults[0] = $_zDateDebut;
			array_push($tResults, $toResults[0]->zDate);
		}

		$tAllDate = self::getDatesBetween(toolDate::getDateFormatYYYYMMDD($tResults[0]), toolDate::getDateFormatYYYYMMDD($tResults[sizeof($tResults)-1]));
		foreach($tAllDate as $oAllDate){
			$tzAllDate = explode("-", $oAllDate);
			if($tzAllDate[2] == $zLeXDeChaqueMois){
				array_push($tResult, $oAllDate);
			}
		}
		$tFinalResults[0] = $_zDateDebut;
		for($i=$_iTousLesXMois; $i<=sizeof($tResult); $i=$i+$_iTousLesXMois){
			array_push($tFinalResults, $tResult[$i-1]);
		}
		return $tFinalResults;
	}

	static function periodiciteQuotidienneGetDateMensuelleParOccurence1($_iNumeroDuJours, $_iJours, $_iTousLesXMois, $_zDateDebut, $_iOccurence){
		$tResults = array();
		$tResult = array();
		$tFinalResults = array();

		for($i=$_iTousLesXMois; $i<=($_iOccurence*$_iTousLesXMois)-1;){
			$zSql = "SELECT DATE_ADD('" . $_zDateDebut . "', INTERVAL " . $i . " MONTH) AS zDate"; 	
			$i=$i+$_iTousLesXMois;
			$oDBW	  = jDb::getDbWidget() ;
			$toResults = $oDBW->fetchAll($zSql) ;
			$tResults[0] = $_zDateDebut;
			array_push($tResults, $toResults[0]->zDate);
		}
		$tAllDate = self::getDatesBetween(toolDate::getDateFormatYYYYMMDD($tResults[0]), toolDate::getDateFormatYYYYMMDD($tResults[sizeof($tResults)-1]));

		foreach($tAllDate as $oAllDate){
			if($_iJours == self::getDateFormatDDParNumero($oAllDate)){
				$tzAllDate = explode('-',$oAllDate);
				$tResult[$tzAllDate[1]][] = $oAllDate;
			}
		}
		$tNewResult = array_values($tResult);
		$tFinalResults[0] = $_zDateDebut; 

		for($i = 0; $i<sizeof($tNewResult); $i++){
			for($j = 0; $j<sizeof($tNewResult[$i]); $j++){
				if(!in_array($tNewResult[$i][$_iNumeroDuJours-1], $tFinalResults)){
					array_push($tFinalResults, $tNewResult[$i][$_iNumeroDuJours-1]);
				}
			}		
		}

		return $tFinalResults;
	}

	static function periodiciteQuotidienneGetDateMensuelleParDateDeFin($_iLeXDeChaqueMois, $_iTousLesXMois, $_zDateDebut, $_zDateFin){
		$tResults = array();
		$tResult = array();
		$tFinalResults = array();
		if($_iLeXDeChaqueMois < 10){
			$zLeXDeChaqueMois = '0'.$_iLeXDeChaqueMois;
		}else{
			$zLeXDeChaqueMois = $_iLeXDeChaqueMois;
		}

		$tAllDate = self::getDatesBetween(toolDate::getDateFormatYYYYMMDD($_zDateDebut), toolDate::getDateFormatYYYYMMDD($_zDateFin));
		foreach($tAllDate as $oAllDate){
			$tzAllDate = explode("-", $oAllDate);
			if($tzAllDate[2] == $zLeXDeChaqueMois){
				array_push($tResult, $oAllDate);
			}
		}
		$tFinalResults[0] = $_zDateDebut;
		for($i=$_iTousLesXMois; $i<=sizeof($tResult); $i=$i+$_iTousLesXMois){
			array_push($tFinalResults, $tResult[$i-1]);
		}
		return $tFinalResults;
	}

	static function periodiciteQuotidienneGetDateMensuelleParDateDeFin1($_iNumeroDuJours, $_iJours, $_iTousLesXMois, $_zDateDebut, $_zDateFin){
		$tResults = array();
		$tResult = array();
		$tFinalResults = array();

		$tAllDate = self::getDatesBetween(toolDate::getDateFormatYYYYMMDD($_zDateDebut), toolDate::getDateFormatYYYYMMDD($_zDateFin));

		foreach($tAllDate as $oAllDate){
			if($_iJours == self::getDateFormatDDParNumero($oAllDate)){
				$tzAllDate = explode('-',$oAllDate);
				$tResult[$tzAllDate[1]][] = $oAllDate;
			}
		}
		$tNewResult = array_values($tResult);
		$tFinalResults[0] = $_zDateDebut; 

		for($i = 0; $i<sizeof($tNewResult); $i++){
			for($j = 0; $j<sizeof($tNewResult[$i]); $j++){
				if(!in_array($tNewResult[$i][$_iNumeroDuJours-1], $tFinalResults)){
					array_push($tFinalResults, $tNewResult[$i][$_iNumeroDuJours-1]);
				}
			}		
		}

		return $tFinalResults;
	}

	static function date_diff($datedebut,$datefin) /// format de vos dates date("Y-m-d H:i:s");
	{
		list($de,$td) = explode(' ', $datedebut); // Séparation date et heure début
		list($df,$tf) = explode(' ',$datefin); // Séparation date et heure fin

		$dd = explode("-",$de); $ddannee = $dd[0]; $ddmois = $dd[1]; $ddjour = $dd[2]; /// date 1
		$hd = explode(":",$td); $hdheure = $hd[0]; $hdmin = $hd[1]; 
		if(isset($hd[2])){
			$hdsec = $hd[2]; /// heure 1
		}else{
			$hdsec = '00'; /// heure 1
		}

		$df = explode("-",$df); $dfannee = $df[0]; $dfmois = $df[1]; $dfjour = $df[2]; /// date 2
		$hf = explode(":",$tf); $hfheure = $hf[0]; $hfmin = $hf[1];
		if(isset($hf[2])){
			$hfsec = $hf[2]; /// heure 2
		}else{
			$hfsec = '00'; /// heure 2
		}
		$time1=time() - mktime($hdheure, $hdmin, $hdsec, $ddmois, $ddjour, $ddannee);
		/// difference de seconde entre 1-1-1970 et la date 1
		$time2=time() - mktime($hfheure, $hfmin, $hfsec, $dfmois, $dfjour, $dfannee);
		/// difference de seconde entre 1-1-1970 et la date 2

		$tsecs = ceil(($time1-$time2)/60/15); /// time1 - time2 donne le nombre en secondes

		$texte=$tsecs;
		return $texte;
	} 

	static function getDateDebutPlusDeuxMois($zDateDebut = ""){
		$zDateDebutSql = self::toDateSQL($zDateDebut);
		$zSql	= "SELECT DATE_ADD('" . $zDateDebutSql . "', INTERVAL 2 MONTH) AS zDateFin"; 	
		$oDBW	= jDb::getDbWidget() ;
		$oDate	= $oDBW->fetchFirst($zSql); 
		return self::toDateFr($oDate->zDateFin) ;
	}
	
	static function dateAdd ($d, $zInterval){
		$zSql	= "SELECT DATE_ADD('".$d."', INTERVAL ".$zInterval.") AS d"; 	
		$oDBW	= jDb::getDbWidget() ;
		$oDate	= $oDBW->fetchFirst($zSql); 
		return $oDate->d ; 
	}
	
	static function getTimeListeDecalageHoraire ($tTimeListe, $iDecalageHoraire){
		$datejour = date('Y-m-d');
		$toArray = array () ; 
		foreach ($tTimeListe as $zTimeListe){
			$zDate = $datejour . " " . $zTimeListe ;
			if ($iDecalageHoraire > 0){
				$zSql = "SELECT DATE_ADD('".$zDate."', INTERVAL ".$iDecalageHoraire." HOUR) AS zDate" ;
			}else{
				$zSql = "SELECT DATE_SUB('".$zDate."', INTERVAL ".abs($iDecalageHoraire)." HOUR) AS zDate" ;
			}
			$oDBW	= jDb::getDbWidget() ;
			$oDate	= $oDBW->fetchFirst($zSql); 
			$tDate  = explode(" ", $oDate->zDate); 
			$tDate2 = explode (":", $tDate[1]) ;
			$zDate2 = $tDate2[0] . ':' . $tDate2[1] ;
			$oTime = new stdClass () ;
			$oTime->time1 = $zTimeListe ;
			$oTime->time2 = $zDate2 ;
			array_push ($toArray, $oTime) ;
		}		
		return $toArray ;
	}

	static function getDateFin ($_oRec){
		if (isset($_oRec->evenement_iDureeTypeId) && $_oRec->evenement_iDureeTypeId > 0){
			if ($_oRec->evenement_iDureeTypeId == 1){  
				$zSql = "SELECT DATE_ADD('".$_oRec->evenement_zDateHeureDebut."', INTERVAL ".$_oRec->evenement_iDuree." HOUR) AS zDateFin" ;
			}else{
				$zSql = "SELECT DATE_ADD('".$_oRec->evenement_zDateHeureDebut."', INTERVAL ".$_oRec->evenement_iDuree." MINUTE) AS zDateFin" ;
			}
			$oDBW	= jDb::getDbWidget() ;
			$oDate	= $oDBW->fetchFirst($zSql); 
			return $oDate->zDateFin ; 
		}else{
			return "" ; 
		}
	}

	static function getDateAddYYYYmmddhis ($zCurrDate){
		$zSql = "SELECT DATE_FORMAT(ADDDATE('" . $zCurrDate . "', INTERVAL 20 MINUTE), '%d/%m/%Y %H:%i:%s') AS zDateNext" ;

		$oDBW	= jDb::getDbWidget() ;
		$oDate	= $oDBW->fetchFirst($zSql); 
		return $oDate->zDateNext ;
	}
}
?>