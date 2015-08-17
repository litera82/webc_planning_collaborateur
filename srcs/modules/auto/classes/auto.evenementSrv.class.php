<?php

jClasses::inc('evenement~evenementSrv') ;
class autoEvemenementSrv extends evenementSrv
{
    
    
    static function getUserTypeDisponibility ($_iUserType = TYPE_UTILISATEUR_PROFESSEUR, $_iTypeEvent = ID_TYPE_EVENEMENT_DISPONIBLE, $_zDateDebut = 0, $_zDateFin = 0, $_p = 1)
    {
        
        $_zDateFin =  $_zDateFin ;
        
        $zSql  = " SELECT * FROM `evenement`" ;
        $zSql .= " INNER JOIN `typeevenements` ON `typeevenements`.`typeevenements_id` = `evenement`.`evenement_iTypeEvenementId`" ;
        $zSql .= " INNER JOIN `utilisateurs` ON `utilisateurs`.`utilisateur_id` = `evenement`.`evenement_iUtilisateurId`" ;
        $zSql .= " INNER JOIN `typeutilisateurs` ON `typeutilisateurs`.`type_id` = `utilisateurs`.`utilisateur_iTypeId`" ;
        $zSql .= " WHERE type_id = " . $_iUserType ;
		if ($_iTypeEvent == ID_TYPE_EVENEMENT_DISPONIBLE || $_iTypeEvent == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE){
			$zSql .= " AND (typeevenements_id = " . ID_TYPE_EVENEMENT_DISPONIBLE . " OR typeevenements_id = " . ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . ") " ;
		}else{
			$zSql .= " AND typeevenements_id = " . $_iTypeEvent ;
		}
		$zSql .= " AND evenement.evenement_zDateHeureDebut BETWEEN '" . $_zDateDebut . " 00:00:00' AND '" . $_zDateFin . " 23:59:59' ";
		switch ($_p){
			case 4: 
				$zSql .= " AND evenement_iUtilisateurId = " . AUDIT_ID_FARAH ;
				break;
			case 3: 
				$zSql .= " AND evenement_iUtilisateurId = " . AUDIT_ID_CATRIONA ;
				break;
			case 2: 
				$zSql .= " AND evenement_iUtilisateurId = " . AUTOPLANNIFICATION_ID_FARAH ;
				break;
			default:
				$zSql .= " AND evenement_iUtilisateurId = " . AUTOPLANNIFICATION_ID_CATRIONA ;
				break;
		}
		$zSql .= " GROUP BY evenement_id";
		$zSql .= " ORDER BY evenement_zDateHeureDebut ASC ";

		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;
		
		return $toResults ;
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
	static function listCriteria($_toParams, $_zSortedField = 'evenement_zDateHeureDebut', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM evenement " ;
		$zSql .= " LEFT JOIN clients ON evenement.evenement_iStagiaire = client_id "; 
		$zSql .= " INNER JOIN societe ON client_iSociete = societe_id ";  
		$zSql .= " INNER JOIN utilisateurs ON evenement_iUtilisateurId = utilisateur_id "; 
		$zSql .= " INNER JOIN typeutilisateurs ON type_id = utilisateur_iTypeId "; 
		$zSql .= " , typeevenements " ;
		$zSql .= " WHERE evenement_iTypeEvenementId = typeevenements_id " ;
		$zSql .= " AND evenement_iUtilisateurId = utilisateur_id " ;

		if (isset($_toParams[0]->libelle) && $_toParams[0]->libelle != ""){
            $zSql .= " AND evenement_zLibelle LIKE '%".$_toParams[0]->libelle."%'";	
		}
		if (isset($_toParams[0]->statut) && $_toParams[0]->statut != 3){
            $zSql .= " AND evenement_iStatut = " . $_toParams[0]->statut;	
		}
		if (isset($_toParams[0]->zDateDebut) && isset($_toParams[0]->zDateFin) && $_toParams[0]->zDateDebut != "" && $_toParams[0]->zDateFin != ""){
            $zSql .= " AND evenement_zDateHeureDebut BETWEEN DATE_FORMAT('".toolDate::toDateSQL($_toParams[0]->zDateDebut)."','%Y/%m/%d') AND DATE_FORMAT('".toolDate::toDateSQL($_toParams[0]->zDateFin)."','%Y/%m/%d')";	
		}
		if (isset($_toParams[0]->iTypeEvenement) && $_toParams[0]->iTypeEvenement != 0){
            $zSql .= " AND evenement_iTypeEvenementId = " . $_toParams[0]->iTypeEvenement;	
		}
		if (isset($_toParams[0]->iStagiaire) && $_toParams[0]->iStagiaire != 0){
            $zSql .= " AND client_id = " . $_toParams[0]->iStagiaire;	
		}
		if (isset($_toParams[0]->iUtilisateur) && $_toParams[0]->iUtilisateur != 0){
            $zSql .= " AND evenement_iUtilisateurId = " . $_toParams[0]->iUtilisateur;	
		}

		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
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
	static function getEventAuto($_toParams, $_zSortedField = 'evenement_zDateHeureDebut', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM evenement " ;
		$zSql .= " LEFT JOIN clients ON evenement.evenement_iStagiaire = client_id "; 
		$zSql .= " INNER JOIN utilisateurs ON evenement_iUtilisateurId = utilisateur_id "; 
		$zSql .= " INNER JOIN typeutilisateurs ON type_id = utilisateur_iTypeId "; 
		$zSql .= " , typeevenements " ;
		$zSql .= " WHERE evenement_iTypeEvenementId = typeevenements_id " ;
		$zSql .= " AND evenement_iUtilisateurId = utilisateur_id " ;

		if (isset($_toParams[0]->libelle) && $_toParams[0]->libelle != ""){
            $zSql .= " AND evenement_zLibelle LIKE '%".$_toParams[0]->libelle."%'";	
		}
		if (isset($_toParams[0]->statut) && $_toParams[0]->statut != 3){
            $zSql .= " AND evenement_iStatut = " . $_toParams[0]->statut;	
		}
		if (isset($_toParams[0]->zDateDebut) && isset($_toParams[0]->zDateFin) && $_toParams[0]->zDateDebut != "" && $_toParams[0]->zDateFin != ""){
            $zSql .= " AND evenement_zDateHeureDebut BETWEEN DATE_FORMAT('".toolDate::toDateSQL($_toParams[0]->zDateDebut)."','%Y/%m/%d') AND DATE_FORMAT('".toolDate::toDateSQL($_toParams[0]->zDateFin)."','%Y/%m/%d')";	
		}
		if (isset($_toParams[0]->iTypeEvenement) && $_toParams[0]->iTypeEvenement != 0){
            $zSql .= " AND evenement_iTypeEvenementId = " . $_toParams[0]->iTypeEvenement;	
		}
		if (isset($_toParams[0]->iStagiaire) && $_toParams[0]->iStagiaire != 0){
            $zSql .= " AND client_id = " . $_toParams[0]->iStagiaire;	
		}
		if (isset($_toParams[0]->iUtilisateur) && $_toParams[0]->iUtilisateur != 0){
            $zSql .= " AND evenement_iUtilisateurId = " . $_toParams[0]->iUtilisateur;	
		}

		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}

	static function sendMailReservationChoix ($toParams, $oCurrentUser){
		jClasses::inc('client~clientSrv');
		jClasses::inc('commun~mailSrv');

		if (isset ($oCurrentUser->client_id) && intval($oCurrentUser->client_id) > 0){
			$oClient = clientSrv::getById($oCurrentUser->client_id); 
			if (isset($oClient->client_zMail) && $oClient->client_zMail != ""){	
				$tplMail = new jTpl();
				$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
				$tplMail->assign ('oClient', $oClient) ;
				$tplMail->assign ('toParams', $toParams) ;

				$tpl = $tplMail->fetch ('auto~corpsMailConfirmationChoix') ;
				
				$zToMail = array (MAIL_TESTORALDEBUT_PROPOSITION, MAIL_TESTORALDEBUT_PROPOSITION_1);

				mailSrv::envoiEmail ($oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom, $zToMail, "Forma2+" , "Un stagiaire a proposé ses choix pour son test de debut de stage", $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;
			}
		}
	}	

	static function saveContactEvent($iEventId, $zContact) {
		if ($iEventId > 0){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oRecord = $oDaoFact->get($iEventId) ;
			$oRecord->evenement_zContactTel = $zContact;
			$oDaoFact->update($oRecord);
			$iRet = $oRecord->evenement_id ;
		}else{
			$iRet = 0 ;
		}
		return $iRet ;
	}

}
?>