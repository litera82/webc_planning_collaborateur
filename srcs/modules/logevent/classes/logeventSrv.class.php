<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage evenement
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
@ini_set ("memory_limit", -1) ;
class logeventSrv 
{
	static function debutsem($year,$month,$day) {
	 $num_day      = date('w', mktime(0,0,0,$month,$day,$year));
	 $premier_jour = mktime(0,0,0, $month,$day-(!$num_day?7:$num_day)+1,$year);
	 $datedeb      = date('d-m-Y', $premier_jour);
		return $datedeb;
	}

	static function finsem($year,$month,$day) {
	 $num_day      = date('w', mktime(0,0,0,$month,$day,$year));
	 $dernier_jour = mktime(0,0,0, $month,7-(!$num_day?7:$num_day)+$day,$year);
	 $datedeb      = date('d-m-Y', $dernier_jour);
		return $datedeb;
	}
	static function toDateSQL($_zDatefr) {
		$zDate =  trim($_zDatefr);    
		$zSeparateur = strrpos($zDate, "/")?'/':'-';
		$tD = explode($zSeparateur,$zDate);
		if($tD[0]<>"") {
			$zDatesql = $tD[2]."-".$tD[1]."-".$tD[0];
			return $zDatesql;
		}
		return "NULL";
	}
	
	static function logevent($debut=null, $fin=null, $table=null) 
	{
		if (is_null($debut) && is_null($fin) && !isset($table)){
			list($y, $m, $d) = explode('-', date('Y-m-d')); 
			$dDateDebut = self::toDateSQL(self::debutsem($y,$m,$d));
			$dDateFin = self::toDateSQL(self::finsem($y,$m,$d));
			$dTable = "logevent" ;
		}else{
			jClasses::inc('commun~toolDate');
			if (!is_null($debut) && !is_null($fin)){
				$dDateDebut = self::toDateSQL($debut);
				$dDateFin = self::toDateSQL($fin);
			}else{
				list($y, $m, $d) = explode('-', date('Y-m-d')); 
				$dDateDebut = self::toDateSQL(self::debutsem($y,$m,$d));
				$dDateFin = self::toDateSQL(self::finsem($y,$m,$d));
			}
			$dTable = "logevent_all" ;
		}

		$zSql = "SELECT * FROM evenement 
				LEFT JOIN typeevenements ON evenement.evenement_iTypeEvenementId = typeevenements.typeevenements_id
				LEFT JOIN utilisateurs ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id
				LEFT JOIN typeutilisateurs ON utilisateurs.utilisateur_iTypeId = typeutilisateurs.type_id
				LEFT JOIN clients ON evenement.evenement_iStagiaire = clients.client_id
				LEFT JOIN duree ON evenement.evenement_iDureeTypeId = duree.duree_id
				LEFT JOIN societe ON clients.client_iSociete = societe.societe_id
				LEFT JOIN pays ON clients.client_iPays = pays.pays_id
				WHERE evenement.evenement_zDateHeureSaisie BETWEEN '".$dDateDebut." 00:00:00' AND '".$dDateFin." 23:59:59'"; 

		$oDBW		= jDb::getDbWidget() ;
		$toResults	= $oDBW->fetchAll($zSql) ;
		$iCpt = 0;
		$zMediaValues = "";
		$iSize = sizeof($toResults); 
		foreach($toResults as $oResults){
			$logevent_evenementLibelle					= "";
			if (isset($oResults->evenement_zLibelle) && ($oResults->evenement_zLibelle != "" && !is_null($oResults->evenement_zLibelle))){
				$logevent_evenementLibelle					.= $oResults->evenement_zLibelle ;
			}else{
				$logevent_evenementLibelle					.= $oResults->typeevenements_zLibelle;
			}

			$logevent_evenementDescription				= $oResults->evenement_zDescription;
			$logevent_evenementContactTel				= $oResults->evenement_zContactTel;
			$logevent_evenementDateHeureDebut			= $oResults->evenement_zDateHeureDebut;
			$logevent_evenementDateHeureSaisie			= $oResults->evenement_zDateHeureSaisie;
			if (isset ($oResults->evenement_origine)){
				switch($oResults->evenement_origine){
					case 1:
						$logevent_evenementOrigine					= "Autoplanification";
					break;
					case 2:
						$logevent_evenementOrigine					= "Planing en ligne";
					break;
				}
			}else{
					$logevent_evenementOrigine					= "Planing en ligne";
			}

			if (isset ($oResults->duree_id)){
				switch($oResults->duree_id){
					case 1:
						$logevent_evenementDure						= intval($oResults->evenement_iDuree) * 60 . " Minutes";
					break;
					case 2:
						$logevent_evenementDure						= $oResults->evenement_iDuree . " " . $oResults->duree_libelle;
					break;
				}
			}
			$logevent_typeevenements					= $oResults->typeevenements_zLibelle;
			$logevent_stagiaireCivilite					= "";
			$logevent_stagiaireNom						= "";
			$logevent_stagiairePrenom					= "";
			$logevent_stagiaireFonction					= "";
			$logevent_stagiaireMail						= "";
			$logevent_stagiaireTel						= "";
			$logevent_stagiaireMobile					= "";
			$logevent_stagiaireLogin					= "";
			$logevent_stagiairePassword					= "";
			$logevent_stagiaireAdresse					= "";
			$logevent_stagiaireNumeroIndividu			= "";
			$logevent_stagiaireSociete					= "";
			$logevent_stagiaireTestDebut				= 0;
			if (isset($oResults->evenement_iStagiaire) && $oResults->evenement_iStagiaire>0){
				switch ($oResults->client_iCivilite){
					case 1: // Mr
						$logevent_stagiaireCivilite					.= "Mr "; 
					break;
					case 0: 
						$logevent_stagiaireCivilite					.= "Mme ";
					break;
					case 2: 
						$logevent_stagiaireCivilite					.= "Mlle ";
					break;
				}
				$logevent_stagiaireNom								.= $oResults->client_zNom;
				$logevent_stagiairePrenom							.= $oResults->client_zPrenom;
				if (isset($oResults->client_zFonction) && ($oResults->client_zFonction != "" && !is_null($oResults->client_zFonction))){
					$logevent_stagiaireFonction						.= $oResults->client_zFonction;
				}
				if (isset($oResults->client_zMail) && ($oResults->client_zMail != "" && !is_null($oResults->client_zMail))){
					$logevent_stagiaireMail = $oResults->client_zMail;
				}
				if (isset($oResults->client_zTel) && ($oResults->client_zTel != "" && !is_null($oResults->client_zTel))){
					$logevent_stagiaireTel							.= $oResults->client_zTel;
				}
				if (isset($oResults->client_zPortable) && ($oResults->client_zPortable != "" && !is_null($oResults->client_zPortable))){
					$logevent_stagiaireMobile						.= $oResults->client_zPortable;
				}
				if (isset($oResults->client_zLogin) && ($oResults->client_zLogin != "" && !is_null($oResults->client_zLogin))){
					$logevent_stagiaireLogin						.= $oResults->client_zLogin;
				}
				if (isset($oResults->client_zPass) && ($oResults->client_zPass != "" && !is_null($oResults->client_zPass))){
					$logevent_stagiairePassword						.= $oResults->client_zPass;
				}

				if (isset($oResults->client_zRue) && ($oResults->client_zRue != "" && !is_null($oResults->client_zRue))){
					$logevent_stagiaireAdresse						.= $oResults->client_zRue;
				}
				if (isset($oResults->client_zVille) && ($oResults->client_zVille != "" && !is_null($oResults->client_zVille))){
					$logevent_stagiaireAdresse						.= " " . $oResults->client_zVille;
				}
				if (isset($oResults->client_zCP) && ($oResults->client_zCP != "" && !is_null($oResults->client_zCP))){
					$logevent_stagiaireAdresse						.= " - " . $oResults->client_zCP;
				}
				if (isset($oResults->pays_zNom) && ($oResults->pays_zNom != "" && !is_null($oResults->pays_zNom))){
					$logevent_stagiaireAdresse						.= " " . $oResults->pays_zNom;
				}
				if (isset($oResults->client_iNumIndividu) && ($oResults->client_iNumIndividu != "" && !is_null($oResults->client_iNumIndividu))){
					$logevent_stagiaireNumeroIndividu					.= $oResults->client_iNumIndividu;
				}
				if (isset($oResults->societe_zNom) && ($oResults->societe_zNom != "" && !is_null($oResults->societe_zNom))){
					$logevent_stagiaireSociete			= $oResults->societe_zNom;
				}
				if (isset($oResults->client_testDebut) && ($oResults->client_testDebut != "" && !is_null($oResults->client_testDebut))){
					$logevent_stagiaireTestDebut			= $oResults->client_testDebut;
				}
			}

			$logevent_profCivilite			= "";
			$logevent_profNom				= "";
			$logevent_profPrenom			= "";
			$logevent_profTel				= "";
			$logevent_profLogin				= "";
			$logevent_profPassword			= "";
			$logevent_profAdresse			= "";
			if (isset($oResults->evenement_iUtilisateurId) && $oResults->evenement_iUtilisateurId>0){
				switch ($oResults->utilisateur_iCivilite){
					case 1: // Mr
						$logevent_profCivilite					.= "Mr "; 
					break;
					case 0: 
						$logevent_profCivilite					.= "Mme ";
					break;
					case 2: 
						$logevent_profCivilite					.= "Mlle ";
					break;
				}
				$logevent_profNom						.= $oResults->utilisateur_zNom;
				$logevent_profPrenom					.= $oResults->utilisateur_zPrenom;
				if (isset($oResults->utilisateur_zTel) && ($oResults->utilisateur_zTel != "" && !is_null($oResults->utilisateur_zTel))){
					$logevent_profTel					.= $oResults->utilisateur_zTel;
				}
				if (isset($oResults->utilisateur_zLogin) && ($oResults->utilisateur_zLogin != "" && !is_null($oResults->utilisateur_zLogin))){
					$logevent_profLogin					.= $oResults->utilisateur_zLogin;
				}
				if (isset($oResults->utilisateur_zPass) && ($oResults->utilisateur_zPass != "" && !is_null($oResults->utilisateur_zPass))){
					$logevent_profPassword				.= $oResults->utilisateur_zPass;
				}
			}

			$zMediaValues .= ($iCpt < 2)? "(" : ",( ";
			$zMediaValues .= "'".  nl2br(addslashes($logevent_evenementLibelle)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementDescription)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementContactTel)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementDateHeureDebut)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementDateHeureSaisie)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementOrigine)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementDure)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_typeevenements)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireCivilite)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireNom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiairePrenom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireFonction)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireMail)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireTel)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireMobile)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireLogin)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiairePassword)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireAdresse)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireNumeroIndividu)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireSociete)) . "'";  
			$zMediaValues .= ", ".   $logevent_stagiaireTestDebut;  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profCivilite)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profNom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profPrenom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profTel)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profLogin)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profPassword)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profAdresse)) . "')";  
			$zMediaValues .= ($iCpt == 0 && $iSize > 1)? "," : " ";
			$iCpt ++;
		}
		if ($zMediaValues != ""){
			$zSqlInsert = "INSERT INTO ".$dTable." (
												logevent_evenementLibelle
												, logevent_evenementDescription
												, logevent_evenementContactTel
												, logevent_evenementDateHeureDebut
												, logevent_evenementDateHeureSaisie
												, logevent_evenementOrigine
												, logevent_evenementDure
												, logevent_typeevenements
												, logevent_stagiaireCivilite
												, logevent_stagiaireNom
												, logevent_stagiairePrenom
												, logevent_stagiaireFonction
												, logevent_stagiaireMail
												, logevent_stagiaireTel
												, logevent_stagiaireMobile
												, logevent_stagiaireLogin
												, logevent_stagiairePassword
												, logevent_stagiaireAdresse
												, logevent_stagiaireNumeroIndividu
												, logevent_stagiaireSociete
												, logevent_stagiaireTestDebut
												, logevent_profCivilite
												, logevent_profNom
												, logevent_profPrenom
												, logevent_profTel
												, logevent_profLogin
												, logevent_profPassword
												, logevent_profAdresse
			) VALUES " . $zMediaValues ;
			$oCnx = jDb::getConnection('logevent');
			$bOk = false;
			try{
				$oRes = $oCnx->exec($zSqlInsert);	
				$bOk = true;
			}catch(Exception $e){
				$e->getMessage();
			}
			return $bOk;
		}
		return true;
	}

	static function logeventAfterAutoplannification($iEventId) 
	{
		$zSql = "SELECT * FROM evenement 
				LEFT JOIN typeevenements ON evenement.evenement_iTypeEvenementId = typeevenements.typeevenements_id
				LEFT JOIN utilisateurs ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id
				LEFT JOIN typeutilisateurs ON utilisateurs.utilisateur_iTypeId = typeutilisateurs.type_id
				LEFT JOIN clients ON evenement.evenement_iStagiaire = clients.client_id
				LEFT JOIN duree ON evenement.evenement_iDureeTypeId = duree.duree_id
				LEFT JOIN societe ON clients.client_iSociete = societe.societe_id
				LEFT JOIN pays ON clients.client_iPays = pays.pays_id
				WHERE evenement.evenement_id = " . $iEventId . " LIMIT 0,1" ; 

		$oDBW		= jDb::getDbWidget() ;
		$toResults	= $oDBW->fetchAll($zSql) ;

		$iCpt = 0;
		$zMediaValues = "";
		$iSize = sizeof($toResults); 
		
		foreach($toResults as $oResults){
			$logevent_evenementLibelle					= "";
			if (isset($oResults->evenement_zLibelle) && ($oResults->evenement_zLibelle != "" && !is_null($oResults->evenement_zLibelle))){
				$logevent_evenementLibelle					.= $oResults->evenement_zLibelle ;
			}else{
				$logevent_evenementLibelle					.= $oResults->typeevenements_zLibelle;
			}

			$logevent_evenementDescription				= $oResults->evenement_zDescription;
			$logevent_evenementContactTel				= $oResults->evenement_zContactTel;
			$logevent_evenementDateHeureDebut			= $oResults->evenement_zDateHeureDebut;
			$logevent_evenementDateHeureSaisie			= $oResults->evenement_zDateHeureSaisie;
			if (isset ($oResults->evenement_origine)){
				switch($oResults->evenement_origine){
					case 1:
						$logevent_evenementOrigine					= "Autoplanification";
					break;
					case 2:
						$logevent_evenementOrigine					= "Planing en ligne";
					break;
				}
			}else{
					$logevent_evenementOrigine					= "Planing en ligne";
			}

			if (isset ($oResults->duree_id)){
				switch($oResults->duree_id){
					case 1:
						$logevent_evenementDure						= intval($oResults->evenement_iDuree) * 60 . " Minutes";
					break;
					case 2:
						$logevent_evenementDure						= $oResults->evenement_iDuree . " " . $oResults->duree_libelle;
					break;
				}
			}else{
				$logevent_evenementDure								= "";
			}
			$logevent_typeevenements					= $oResults->typeevenements_zLibelle;
			$logevent_stagiaireCivilite					= "";
			$logevent_stagiaireNom						= "";
			$logevent_stagiairePrenom					= "";
			$logevent_stagiaireFonction					= "";
			$logevent_stagiaireMail						= "";
			$logevent_stagiaireTel						= "";
			$logevent_stagiaireMobile					= "";
			$logevent_stagiaireLogin					= "";
			$logevent_stagiairePassword					= "";
			$logevent_stagiaireAdresse					= "";
			$logevent_stagiaireNumeroIndividu			= "";
			$logevent_stagiaireSociete					= "";
			if (isset($oResults->evenement_iStagiaire) && $oResults->evenement_iStagiaire>0){
				switch ($oResults->client_iCivilite){
					case 1: // Mr
						$logevent_stagiaireCivilite					.= "Mr "; 
					break;
					case 0: 
						$logevent_stagiaireCivilite					.= "Mme ";
					break;
					case 2: 
						$logevent_stagiaireCivilite					.= "Mlle ";
					break;
				}
				$logevent_stagiaireNom								.= $oResults->client_zNom;
				$logevent_stagiairePrenom							.= $oResults->client_zPrenom;
				if (isset($oResults->client_zFonction) && ($oResults->client_zFonction != "" && !is_null($oResults->client_zFonction))){
					$logevent_stagiaireFonction						.= $oResults->client_zFonction;
				}
				if (isset($oResults->client_zMail) && ($oResults->client_zMail != "" && !is_null($oResults->client_zMail))){
					$logevent_stagiaireMail = $oResults->client_zMail;
				}
				if (isset($oResults->client_zTel) && ($oResults->client_zTel != "" && !is_null($oResults->client_zTel))){
					$logevent_stagiaireTel							.= $oResults->client_zTel;
				}
				if (isset($oResults->client_zPortable) && ($oResults->client_zPortable != "" && !is_null($oResults->client_zPortable))){
					$logevent_stagiaireMobile						.= $oResults->client_zPortable;
				}
				if (isset($oResults->client_zLogin) && ($oResults->client_zLogin != "" && !is_null($oResults->client_zLogin))){
					$logevent_stagiaireLogin						.= $oResults->client_zLogin;
				}
				if (isset($oResults->client_zPass) && ($oResults->client_zPass != "" && !is_null($oResults->client_zPass))){
					$logevent_stagiairePassword						.= $oResults->client_zPass;
				}

				if (isset($oResults->client_zRue) && ($oResults->client_zRue != "" && !is_null($oResults->client_zRue))){
					$logevent_stagiaireAdresse						.= $oResults->client_zRue;
				}
				if (isset($oResults->client_zVille) && ($oResults->client_zVille != "" && !is_null($oResults->client_zVille))){
					$logevent_stagiaireAdresse						.= " " . $oResults->client_zVille;
				}
				if (isset($oResults->client_zCP) && ($oResults->client_zCP != "" && !is_null($oResults->client_zCP))){
					$logevent_stagiaireAdresse						.= " - " . $oResults->client_zCP;
				}
				if (isset($oResults->pays_zNom) && ($oResults->pays_zNom != "" && !is_null($oResults->pays_zNom))){
					$logevent_stagiaireAdresse						.= " " . $oResults->pays_zNom;
				}
				if (isset($oResults->client_iNumIndividu) && ($oResults->client_iNumIndividu != "" && !is_null($oResults->client_iNumIndividu))){
					$logevent_stagiaireNumeroIndividu					.= $oResults->client_iNumIndividu;
				}
				if (isset($oResults->societe_zNom) && ($oResults->societe_zNom != "" && !is_null($oResults->societe_zNom))){
					$logevent_stagiaireSociete			= $oResults->societe_zNom;
				}
				if (isset($oResults->client_testDebut) && ($oResults->client_testDebut != "" && !is_null($oResults->client_testDebut))){
					$logevent_stagiaireTestDebut			= $oResults->client_testDebut;
				}
			}

			$logevent_profCivilite			= "";
			$logevent_profNom				= "";
			$logevent_profPrenom			= "";
			$logevent_profTel				= "";
			$logevent_profLogin				= "";
			$logevent_profPassword			= "";
			$logevent_profAdresse			= "";
			if (isset($oResults->evenement_iUtilisateurId) && $oResults->evenement_iUtilisateurId>0){
				switch ($oResults->utilisateur_iCivilite){
					case 1: // Mr
						$logevent_profCivilite					.= "Mr "; 
					break;
					case 0: 
						$logevent_profCivilite					.= "Mme ";
					break;
					case 2: 
						$logevent_profCivilite					.= "Mlle ";
					break;
				}
				$logevent_profNom						.= $oResults->utilisateur_zNom;
				$logevent_profPrenom					.= $oResults->utilisateur_zPrenom;
				if (isset($oResults->utilisateur_zTel) && ($oResults->utilisateur_zTel != "" && !is_null($oResults->utilisateur_zTel))){
					$logevent_profTel					.= $oResults->utilisateur_zTel;
				}
				if (isset($oResults->utilisateur_zLogin) && ($oResults->utilisateur_zLogin != "" && !is_null($oResults->utilisateur_zLogin))){
					$logevent_profLogin					.= $oResults->utilisateur_zLogin;
				}
				if (isset($oResults->utilisateur_zPass) && ($oResults->utilisateur_zPass != "" && !is_null($oResults->utilisateur_zPass))){
					$logevent_profPassword				.= $oResults->utilisateur_zPass;
				}
			}

			$zMediaValues .= ($iCpt < 2)? "(" : ",( ";
			$zMediaValues .= "'".  nl2br(addslashes($logevent_evenementLibelle)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementDescription)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementContactTel)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementDateHeureDebut)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementDateHeureSaisie)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementOrigine)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_evenementDure)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_typeevenements)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireCivilite)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireNom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiairePrenom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireFonction)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireMail)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireTel)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireMobile)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireLogin)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiairePassword)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireAdresse)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireNumeroIndividu)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_stagiaireSociete)) . "'";  
			$zMediaValues .= ", ".   $logevent_stagiaireTestDebut;  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profCivilite)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profNom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profPrenom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profTel)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profLogin)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profPassword)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($logevent_profAdresse)) . "')";  
			$zMediaValues .= ($iCpt == 0 && $iSize > 1)? "," : " ";
			$iCpt ++;
		}

		if ($zMediaValues != ""){
			$zSqlInsert = "INSERT INTO logevent (
												logevent_evenementLibelle
												, logevent_evenementDescription
												, logevent_evenementContactTel
												, logevent_evenementDateHeureDebut
												, logevent_evenementDateHeureSaisie
												, logevent_evenementOrigine
												, logevent_evenementDure
												, logevent_typeevenements
												, logevent_stagiaireCivilite
												, logevent_stagiaireNom
												, logevent_stagiairePrenom
												, logevent_stagiaireFonction
												, logevent_stagiaireMail
												, logevent_stagiaireTel
												, logevent_stagiaireMobile
												, logevent_stagiaireLogin
												, logevent_stagiairePassword
												, logevent_stagiaireAdresse
												, logevent_stagiaireNumeroIndividu
												, logevent_stagiaireSociete
												, logevent_stagiaireTestDebut
												, logevent_profCivilite
												, logevent_profNom
												, logevent_profPrenom
												, logevent_profTel
												, logevent_profLogin
												, logevent_profPassword
												, logevent_profAdresse
			) VALUES " . $zMediaValues ;
			$oCnx = jDb::getConnection('logevent');
			$bOk = false;

			try{
				$oRes = $oCnx->exec($zSqlInsert);	
				$bOk = true;
			}catch(Exception $e){
				$e->getMessage();
			}
			return $bOk;
		}
		return true;
	}

}
?>