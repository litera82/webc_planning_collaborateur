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
class evenementSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~evenement') ;
                $oEvent = $oFac->get($_iId) ;
                return $oEvent ;
	}

	/**
	 * - signaler lors de la création ou modification d’un evenement si la plage horaire est occupée (alerte)
	 * @param string $_zDate
	 * @param string $_iTime
	 *  @return int $iNbreEvent
	 */
	static function testEventExist($_zDate, $_iTime){
		$zDateHeureDebut = $_zDate . " " . $_iTime . ":00"; 
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);

		$zSql = "SELECT COUNT(*) AS iNbreEvent FROM evenement WHERE evenement_zDateHeureDebut = '" . $zDateHeureDebut . "' AND evenement_iUtilisateurId = " . $iUtilisateurId; 
		$oDBW	  = jDb::getDbWidget() ;
		$oCount = $oDBW->fetchFirst($zSql) ;
		return $oCount->iNbreEvent;
	}
	static function testEventExistEdition($_zDateTime, $_iEvenementId){
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('commun~toolDate');
		$tzDateTime = explode (" ", $_zDateTime); 

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);

		$zSql = "SELECT COUNT(*) AS iNbreEvent FROM evenement WHERE evenement_zDateHeureDebut = '" . toolDate::toDateSQL($tzDateTime[0]) . " " . $tzDateTime[1] . ":00" . "' AND evenement_iUtilisateurId = " . $iUtilisateurId . " AND (evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . " OR evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_DISPONIBLE.") " ; 
		if ($_iEvenementId != 0){
			$zSql .= " AND evenement_id <> " . $_iEvenementId; 
		}
		$oDBW	  = jDb::getDbWidget() ;
		$oCount = $oDBW->fetchFirst($zSql) ;
		return $oCount->iNbreEvent;
	}
	
	static function testEventExistEditionIsTypeEventDisponible($_zDateTime, $_iEvenementId){
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('commun~toolDate');
		$tzDateTime = explode (" ", $_zDateTime); 

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);

		$zSql = "SELECT evenement_iTypeEvenementId FROM evenement WHERE evenement_zDateHeureDebut = '" . toolDate::toDateSQL($tzDateTime[0]) . " " . $tzDateTime[1] . ":00" . "' AND evenement_iUtilisateurId = " . $iUtilisateurId; 
		if ($_iEvenementId != 0){
			$zSql .= " AND evenement_id <> " . $_iEvenementId; 
		}
		$oDBW	  = jDb::getDbWidget() ;
		$oCount = $oDBW->fetchFirst($zSql) ;
		return $oCount->evenement_iTypeEvenementId;
	}

	static function desactiverEventDispo($_zDateTime, $_iEvenementId){
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('commun~toolDate');
		$tzDateTime = explode (" ", $_zDateTime); 

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);

		$zSql = "SELECT evenement.* FROM evenement WHERE evenement_zDateHeureDebut = '" . toolDate::toDateSQL($tzDateTime[0]) . " " . $tzDateTime[1] . ":00" . "' AND evenement_iUtilisateurId = " . $iUtilisateurId; 
		if ($_iEvenementId != 0){
			$zSql .= " AND evenement_id <> " . $_iEvenementId; 
		}
		$oDBW = jDb::getDbWidget() ;
		$oEvent = $oDBW->fetchFirst($zSql) ;

		if ($oEvent->evenement_id > 0){
			$zQuery=" UPDATE evenement SET evenement_zDateHeureDebut = '0000-00-00 00:00:00' WHERE evenement_id = " . $oEvent->evenement_id;
			$oCnx = jDb::getConnection();
			$oCnx->exec($zQuery);	
		}
		return 1;
	}

	static function findEventByListEventId($_zEvenementId) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT * FROM evenement " ;
		$zSql .= " LEFT JOIN clients ON evenement.evenement_iStagiaire = client_id "; 
		$zSql .= " LEFT JOIN societe ON client_iSociete = societe_id "; 
		$zSql .= " , typeevenements, utilisateurs " ;
		$zSql .= " WHERE evenement_iTypeEvenementId = typeevenements_id " ;
		$zSql .= " AND evenement_id IN (".$_zEvenementId.")" ;
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
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
	static function listCriteria($_toParams, $_zSortedField = 'evenement_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
//print_r($_toParams);
		if (!isset($_toParams[0]->iCheckDate)){
			$_toParams[0]->iCheckDate = 0;
		}
		jClasses::inc('commun~toolDate');

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM evenement " ;
		$zSql .= " LEFT JOIN clients ON evenement.evenement_iStagiaire = client_id "; 
		$zSql .= " LEFT JOIN societe ON client_iSociete = societe_id "; 
		$zSql .= " , typeevenements, utilisateurs " ;
		$zSql .= " WHERE evenement_iTypeEvenementId = typeevenements_id " ;
		$zSql .= " AND evenement_iUtilisateurId = utilisateur_id " ;

		if (isset($_toParams[0]->libelle) && $_toParams[0]->libelle != ""){
            $zSql .= " AND evenement_zLibelle LIKE '%".$_toParams[0]->libelle."%'";	
		}
		if (isset($_toParams[0]->statut) && $_toParams[0]->statut != 3){
            $zSql .= " AND evenement_iStatut = " . $_toParams[0]->statut;	
		}

		if ($_toParams[0]->iCheckDate == 0 && isset($_toParams[0]->zDateDebut) && isset($_toParams[0]->zDateFin) && $_toParams[0]->zDateDebut != "" && $_toParams[0]->zDateFin != ""){
            $zSql .= " AND evenement_zDateHeureDebut BETWEEN DATE_FORMAT('".toolDate::toDateSQL($_toParams[0]->zDateDebut)."','%Y/%m/%d 00:00:00') AND DATE_FORMAT('".toolDate::toDateSQL($_toParams[0]->zDateFin)."','%Y/%m/%d 23:59:59')";	
		}
		if (isset($_toParams[0]->iTypeEvenement) && $_toParams[0]->iTypeEvenement != 0){
			if ($_toParams[0]->iTypeEvenement == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE || $_toParams[0]->iTypeEvenement == ID_TYPE_EVENEMENT_DISPONIBLE){
				$zSql .= " AND (evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . " OR evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_DISPONIBLE . ") ";	
			}else{
				$zSql .= " AND evenement_iTypeEvenementId = " . $_toParams[0]->iTypeEvenement;	
			}
		}
		if (isset($_toParams[0]->evenement_origine) && $_toParams[0]->evenement_origine != 0){
            $zSql .= " AND evenement_origine = " . $_toParams[0]->evenement_origine;	
		}
		if (isset($_toParams[0]->evenement_zSociete) && $_toParams[0]->evenement_zSociete != ""){
			$tzSoc = explode(" ", trim($_toParams[0]->evenement_zSociete));
			$zCritSoc = "";	
			if (sizeof($tzSoc) > 0){
				for($i=0; $i<sizeof($tzSoc); $i++){
					if ($zCritSoc!=""){
						$zCritSoc .= " OR ";
					}
					$zCritSoc .= " societe_zNom LIKE '%" . $tzSoc[$i] . "%' " ;
				}
			}
			if ($zCritSoc != ''){
	            $zSql .= " AND (" . $zCritSoc . ") ";	
			}
		}
		if (isset($_toParams[0]->iStagiaire) && $_toParams[0]->iStagiaire != 0){
            $zSql .= " AND client_id = " . $_toParams[0]->iStagiaire;	
		}
		if (isset($_toParams[0]->iUtilisateur) && $_toParams[0]->iUtilisateur != 0 && $_toParams[0]->iUtilisateur != ID_ADMINISTRATEUR_PP && isset($_toParams[0]->iCheckboxeAutoplanification) && $_toParams[0]->iCheckboxeAutoplanification == 0){
            $zSql .= " AND evenement_iUtilisateurId = " . $_toParams[0]->iUtilisateur;	
		}
		if (isset($_toParams[0]->iEventListing) && $_toParams[0]->iEventListing == 1){
            $zSql .= " AND evenement_iTypeEvenementId NOT IN ( " . ID_TYPE_EVENEMENT_DISPONIBLE . ", " . ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . ") ";	
		}
//print_r($_toParams); 
		$zSql .= " GROUP BY evenement_id ";
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;
//echo $zSql; die;
		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}

	static function getEventUser ($_iUtilisateurId, $_zDateDebut, $_zDateFin, $_iTypeEvenementId = 0, $_iUtilisateurId1 = 0, $_iAffichage = 1, $_iGroupeId = 0){
		$zSql  = "" ;
		$zSql .= " SELECT * FROM evenement ";
		$zSql .= " INNER JOIN utilisateurs ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id ";
		$zSql .= " LEFT JOIN utilisateursgroup ON utilisateurs.utilisateur_id = utilisateursgroup.utilisateursgroup_utilisateurId ";
		$zSql .= " LEFT JOIN groupe ON utilisateursgroup.utilisateursgroup_groupId = groupe.groupe_id " ;
		$zSql .= " INNER JOIN typeevenements ON evenement.evenement_iTypeEvenementId = typeevenements.typeevenements_id ";
		$zSql .= " LEFT JOIN clients ON evenement.evenement_iStagiaire = clients.client_id ";
		$zSql .= " LEFT JOIN wp_postsclients ON clients.client_id = wp_postsclients.client_id ";
		$zSql .= " LEFT JOIN wp_posts_to_import ON wp_postsclients.post_id = wp_posts_to_import.ID ";
		$zSql .= " LEFT JOIN societe ON clients.client_iSociete = societe.societe_id ";
		$zSql .= " LEFT JOIN etatevenement ON evenement.evenement_id = etatevenement.etat_iEvenementId LEFT JOIN typeetat ON etatevenement.etat_iTypeEtatId = typeetat.typeetat_id "; 
		$zSql .= " WHERE 1 ";
		/*if (!is_null($_iUtilisateurId)){
			$zSql .= " AND evenement.evenement_iUtilisateurId = " . $_iUtilisateurId ;
		}*/
		if ($_iTypeEvenementId != 0){
			$zSql .= " AND evenement.evenement_iTypeEvenementId = " . $_iTypeEvenementId;
		}
		if ($_iUtilisateurId1 != 0){
			$zSql .= " AND evenement.evenement_iUtilisateurId = " . $_iUtilisateurId1;
		}
		if ($_iGroupeId != 0){
			$zSql .= " AND groupe.groupe_id = " . $_iGroupeId;
		}
		$zSql .= " AND evenement.evenement_iStatut = 1";
		switch($_iAffichage){
			case 1:
				$zSql .= " AND evenement.evenement_zDateHeureDebut BETWEEN '" . $_zDateDebut . " 00:00:00' AND '" . $_zDateFin . " 23:59:59' ";
			break;
			case 2:
				$zSql .= " AND evenement.evenement_zDateHeureDebut BETWEEN '" . $_zDateDebut . " 00:00:00' AND '" . $_zDateFin . " 23:59:59' ";
			break;
			case 3:
				jClasses::inc ('commun~toolDate') ;
				$tD = toolDate::getIntervalDateAffichageParMois($_zDateDebut);
				$zSql .= " AND evenement.evenement_zDateHeureDebut BETWEEN '" . $tD->zDateDebut . " 00:00:00' AND '" . $tD->zDateFin . " 23:59:59' ";
			break;
		}
		//$zSql .= " GROUP BY evenement_iUtilisateurId, evenement_iTypeEvenementId, evenement_zDateHeureDebut ";
		$zSql .= " GROUP BY evenement_id ";
		$zSql .= " ORDER BY evenement_zDateHeureDebut ASC, evenement_zDateHeureSaisie ASC ";

		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}
	static function getEventById ($_iEventId){

		$zSql  = "" ;
		$zSql .= " SELECT * FROM evenement ";
		$zSql .= " INNER JOIN utilisateurs ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id ";
		$zSql .= " INNER JOIN typeevenements ON evenement.evenement_iTypeEvenementId = typeevenements.typeevenements_id ";
		$zSql .= " LEFT JOIN clients ON evenement.evenement_iStagiaire = clients.client_id LEFT JOIN societe ON clients.client_iSociete = societe.societe_id ";
		$zSql .= " WHERE 1 ";
		if ($_iEventId > 0){
			$zSql .= " AND evenement.evenement_id = " . $_iEventId;
		}
		$oDBW	  = jDb::getDbWidget() ;
		$oEvent = $oDBW->fetchFirst($zSql) ;
		return $oEvent; 
	}

	static function getEventUserWithDisponibility ($_iUtilisateurId, $_zDateDebut, $_zDateFin, $_bDisponibility = 0){
		$zSql  = "" ;
		$zSql .= " SELECT SQL_CALC_FOUND_ROWS * FROM evenement ";
		$zSql .= " INNER JOIN utilisateurs ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id ";
		$zSql .= " INNER JOIN typeevenements ON evenement.evenement_iTypeEvenementId = typeevenements.typeevenements_id ";
		$zSql .= " LEFT JOIN clients ON evenement.evenement_iStagiaire = clients.client_id ";
		$zSql .= " LEFT JOIN societe ON clients.client_iSociete = societe.societe_id ";
		//$zSql .= " WHERE evenement.evenement_iUtilisateurId = " . $_iUtilisateurId ;
		$zSql .= " WHERE evenement.evenement_iStatut = 1";
		$zSql .= ($_bDisponibility) ? " AND evenement.evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_DISPONIBLE : " AND evenement.evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE ;
		$zSql .= " AND evenement.evenement_zDateHeureDebut BETWEEN '" . $_zDateDebut . " 00:00:00' AND '" . $_zDateFin . " 23:59:59' ";
		$zSql .= " GROUP BY evenement_id "; 
		$zSql .= " ORDER BY evenement_zDateHeureDebut ASC "; 
		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;
		
		return $toResults ;
	}

	static function copyObjectEvent ($_oEvent){
		$oEventCopy = new stdClass();
		$oEventCopy->evenement_id            	= $_oEvent->evenement_id; 
		$oEventCopy->evenement_iTypeEvenementId = $_oEvent->evenement_iTypeEvenementId; 
		$oEventCopy->evenement_iUtilisateurId   = $_oEvent->evenement_iUtilisateurId; 
		$oEventCopy->evenement_zLibelle         = $_oEvent->evenement_zLibelle; 
		$oEventCopy->evenement_zDescription		= $_oEvent->evenement_zDescription ; 
		$oEventCopy->evenement_iStagiaire		= $_oEvent->evenement_iStagiaire; 
		$oEventCopy->evenement_zContactTel		= $_oEvent->evenement_zContactTel ; 
		$oEventCopy->evenement_zDateHeureDebut	= $_oEvent->evenement_zDateHeureDebut ; 
		$oEventCopy->evenement_iDuree           = $_oEvent->evenement_iDuree ; 
		$oEventCopy->evenement_iPriorite		= $_oEvent->evenement_iPriorite ; 
		$oEventCopy->evenement_iRappel          = $_oEvent->evenement_iRappel ; 
		$oEventCopy->evenement_iStatut          = $_oEvent->evenement_iStatut ; 
		$oEventCopy->utilisateur_id            	= $_oEvent->utilisateur_id ; 
		$oEventCopy->utilisateur_iTypeId		= $_oEvent->utilisateur_iTypeId ; 
		$oEventCopy->utilisateur_iCivilite		= $_oEvent->utilisateur_iCivilite ; 
		$oEventCopy->utilisateur_zNom           = $_oEvent->utilisateur_zNom ; 
		$oEventCopy->utilisateur_zPrenom		= $_oEvent->utilisateur_zPrenom ; 
		$oEventCopy->utilisateur_zMail          = $_oEvent->utilisateur_zMail ; 
		$oEventCopy->utilisateur_zLogin         = $_oEvent->utilisateur_zLogin ; 
		$oEventCopy->utilisateur_zPass          = $_oEvent->utilisateur_zPass ; 
		$oEventCopy->utilisateur_zTel           = $_oEvent->utilisateur_zTel ; 
		$oEventCopy->utilisateur_iPays          = $_oEvent->utilisateur_iPays ; 
		$oEventCopy->utilisateur_statut         = $_oEvent->utilisateur_statut ; 
		$oEventCopy->typeevenements_id          = $_oEvent->typeevenements_id ; 
		$oEventCopy->typeevenements_zLibelle	= $_oEvent->typeevenements_zLibelle ; 
		$oEventCopy->typeevenements_iDure		= $_oEvent->typeevenements_iDure ; 
		$oEventCopy->typeevenements_zCouleur	= $_oEvent->typeevenements_zCouleur ; 
		$oEventCopy->typeevenements_iStatut		= $_oEvent->typeevenements_iStatut ; 
		$oEventCopy->evenement_date            	= $_oEvent->evenement_date ; 
		$oEventCopy->evenement_heure            = $_oEvent->evenement_heure; 

		$oEventCopy->client_id					= $_oEvent->client_id; 
		$oEventCopy->client_iSociete			= $_oEvent->client_iSociete; 
		$oEventCopy->client_iCivilite           = $_oEvent->client_iCivilite; 
		$oEventCopy->client_zNom				= $_oEvent->client_zNom; 
		$oEventCopy->client_zPrenom				= $_oEvent->client_zPrenom; 
		$oEventCopy->client_zFonction           = $_oEvent->client_zFonction; 
		$oEventCopy->client_zMail				= $_oEvent->client_zMail; 
		$oEventCopy->client_zLogin				= $_oEvent->client_zLogin; 
		$oEventCopy->client_zPass				= $_oEvent->client_zPass; 
		$oEventCopy->client_zTel				= $_oEvent->client_zTel; 
		$oEventCopy->client_zPortable           = $_oEvent->client_zPortable; 
		$oEventCopy->client_zRue				= $_oEvent->client_zRue; 
		$oEventCopy->client_zVille				= $_oEvent->client_zVille; 
		$oEventCopy->client_zCP					= $_oEvent->client_zCP; 
		$oEventCopy->client_iPays				= $_oEvent->client_iPays; 
		$oEventCopy->client_iNumIndividu        = $_oEvent->client_iNumIndividu; 
		$oEventCopy->client_iStatut				= $_oEvent->client_iStatut; 

		return $oEventCopy;
	}
	static function saveEventRapid($oEvent) {
		$oDaoFact = jDao::get('commun~evenement') ;
		$oRecord = null;
		try{
			$oRecord = jDao::createRecord('commun~evenement') ;
			$oRecord->evenement_id					=null ; 
			$oRecord->evenement_iTypeEvenementId	=$oEvent->evenement_iTypeEvenementId ; 
			$oRecord->evenement_zLibelle			=$oEvent->evenement_zLibelle ;
			$oRecord->evenement_zDescription		=$oEvent->evenement_zDescription ;
			$oRecord->evenement_iUtilisateurId		=$oEvent->evenement_iUtilisateurId ;
			$oRecord->evenement_iStagiaire			=$oEvent->evenement_iStagiaire ; 
			$oRecord->evenement_zDateHeureDebut		=$oEvent->evenement_zDateHeureDebut ;
			$oRecord->evenement_zDateHeureSaisie	=$oEvent->evenement_zDateHeureSaisie ;
			$oRecord->evenement_iStatut				=$oEvent->evenement_iStatut ;
			$oRecord->evenement_iPriorite			=0 ;
			$oDaoFact->insert($oRecord) ;
			return $oRecord->evenement_id ;
		}catch (Exception $e){
			return 0 ;
		}
	}
	static function collerEvent($oEvent) {
		$oDaoFact = jDao::get('commun~evenement') ;
		$oRecord = null;
		try{
			$oRecord = jDao::createRecord('commun~evenement') ;
			$oRecord->evenement_id = null ;
			$oRecord->evenement_iTypeEvenementId = $oEvent->evenement_iTypeEvenementId;
			$oRecord->evenement_iUtilisateurId = $oEvent->evenement_iUtilisateurId;
			$oRecord->evenement_zLibelle = $oEvent->evenement_zLibelle;
			$oRecord->evenement_zDescription = $oEvent->evenement_zDescription;
			$oRecord->evenement_iStagiaire = $oEvent->evenement_iStagiaire;
			$oRecord->evenement_zContactTel = $oEvent->evenement_zContactTel;
			$oRecord->evenement_zDateHeureDebut = $oEvent->evenement_zDateHeureDebut ; 
			$oRecord->evenement_zDateHeureSaisie = $oEvent->evenement_zDateHeureSaisie;
			$oRecord->evenement_iDuree = $oEvent->evenement_iDuree;
			$oRecord->evenement_iDureeTypeId = $oEvent->evenement_iDureeTypeId;
			$oRecord->evenement_iPriorite = $oEvent->evenement_iPriorite;
			$oRecord->evenement_iRappel = $oEvent->evenement_iRappel;
			$oRecord->evenement_iTypeRappelId = $oEvent->evenement_iTypeRappelId;
			$oRecord->evenement_iStatut = $oEvent->evenement_iStatut;
			$oRecord->evenement_origine = $oEvent->evenement_origine;

			$oDaoFact->insert($oRecord) ;
			return $oRecord->evenement_id ;
		}catch (Exception $e){
			return 0 ;
		}
	}
	static function saveDescEvent($iEventId, $zDescription) {
		$oDaoFact = jDao::get('commun~evenement') ;
        $oRecord = $oDaoFact->get($iEventId) ;
		$oRecord->evenement_zDescription = $zDescription;
		$oDaoFact->update($oRecord);
		return $oRecord->evenement_id ;
	}

	static function savePopEventListing($iEventId, $iTypeEventId, $zEventDesc, $iEventStagiaireId) {
		$oDaoFact = jDao::get('commun~evenement') ;
        $oRecord = $oDaoFact->get($iEventId) ;

		$oRecord->evenement_iTypeEvenementId = $iTypeEventId;
		$oRecord->evenement_zDescription = $zEventDesc;
		$oRecord->evenement_iStagiaire = $iEventStagiaireId;

		$oDaoFact->update($oRecord);
		return $oRecord->evenement_id ;
	}

	/**
	 * Sauvegarde et modification
	 * @param array $_toParams les parametre à modifier ou à insserer
	 * @return object
	 */
	static function save($toInfos) 
	{		
			$oDaoFact = jDao::get('commun~evenement') ;
            $oRecord = null;
            $iId = isset($toInfos['evenement_id']) ? $toInfos['evenement_id'] : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~evenement') ;
				$zEvenementDateHeureDebut = "";
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
				$zEvenementDateHeureDebut = $oRecord->evenement_zDateHeureDebut;
            }
            $oRecord->evenement_iTypeEvenementId    = isset($toInfos['evenement_iTypeEvenementId']) ? $toInfos['evenement_iTypeEvenementId'] : $oRecord->evenement_iTypeEvenementId ;
            $oRecord->evenement_iUtilisateurId		= isset($toInfos['evenement_iUtilisateurId']) ? $toInfos['evenement_iUtilisateurId'] : $oRecord->evenement_iUtilisateurId ;
            $oRecord->evenement_zLibelle			= isset($toInfos['evenement_zLibelle']) ? $toInfos['evenement_zLibelle'] : $oRecord->evenement_zLibelle ;
            $oRecord->evenement_zDescription		= isset($toInfos['evenement_zDescription']) ? $toInfos['evenement_zDescription'] : $oRecord->evenement_zDescription ;
            $oRecord->evenement_iStagiaire			= isset($toInfos['evenement_iStagiaire']) ? $toInfos['evenement_iStagiaire'] : $oRecord->evenement_iStagiaire ;
            $oRecord->evenement_zContactTel			= isset($toInfos['evenement_zContactTel']) ? $toInfos['evenement_zContactTel'] : $oRecord->evenement_zContactTel ;
            if (isset($toInfos['evenement_zDateHeureDebut']) && $toInfos['evenement_zDateHeureDebut'] != "" && strpos('/', $toInfos['evenement_zDateHeureDebut'])){
            	$tzDateHeure = explode(" ", $toInfos['evenement_zDateHeureDebut']);
            	$tzDate = explode("/", $tzDateHeure[0]);
            	$oRecord->evenement_zDateHeureDebut = $tzDate[2] . "-" . $tzDate[1] . "-" . $tzDate[0] . " " . $tzDateHeure[1];
            }elseif (isset($toInfos['evenement_zDateHeureDebut']) && $toInfos['evenement_zDateHeureDebut'] != ""){
            	$tzDateHeure = explode(" ", $toInfos['evenement_zDateHeureDebut']);
            	$tzDate = explode("/", $tzDateHeure[0]);
            	if (isset ($tzDate[2])){
					$oRecord->evenement_zDateHeureDebut = $tzDate[2] . "-" . $tzDate[1] . "-" . $tzDate[0] . " " . $tzDateHeure[1];
				}else{
	            	$oRecord->evenement_zDateHeureDebut = $oRecord->evenement_zDateHeureDebut;
				}
			}else{
            	$oRecord->evenement_zDateHeureDebut = $oRecord->evenement_zDateHeureDebut;
			}
            $oRecord->evenement_zDateHeureSaisie	= isset($toInfos['evenement_zDateHeureSaisie']) ? $toInfos['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
            $oRecord->evenement_iDuree				= isset($toInfos['evenement_iDuree']) ? $toInfos['evenement_iDuree'] : $oRecord->evenement_iDuree ;
            $oRecord->evenement_iDuree				= isset($toInfos['evenement_iDuree']) ? $toInfos['evenement_iDuree'] : $oRecord->evenement_iDuree ;
            $oRecord->evenement_iDureeTypeId		= isset($toInfos['evenement_iDureeTypeId']) ? $toInfos['evenement_iDureeTypeId'] : $oRecord->evenement_iDureeTypeId ;
            $oRecord->evenement_iPriorite			= isset($toInfos['evenement_iPriorite']) ? $toInfos['evenement_iPriorite'] : $oRecord->evenement_iPriorite ;
            $oRecord->evenement_iRappel				= isset($toInfos['evenement_iRappel']) ? $toInfos['evenement_iRappel'] : $oRecord->evenement_iRappel ;
	        $oRecord->evenement_iTypeRappelId		= isset($toInfos['evenement_iTypeRappelId']) ? $toInfos['evenement_iTypeRappelId'] : $oRecord->evenement_iTypeRappelId ;
            $oRecord->evenement_iStatut				= isset($toInfos['evenement_iStatut']) ? $toInfos['evenement_iStatut'] : $oRecord->evenement_iStatut ;
            $oRecord->evenement_origine				= isset($toInfos['evenement_origine']) ? $toInfos['evenement_origine'] : $oRecord->evenement_origine ;

			jClasses::inc('evenement~utilsEvenementSrv');
			if (isset ($toInfos['x']) && $toInfos['x'] != 10){
				if($iId > 0)
				{
					self::delete($iId) ;
				}
				utilsEvenementSrv::saveEvent($oRecord, $toInfos['x']) ;
			}else{
                $oDaoFact->update($oRecord);
			}

			if (isset($toInfos['sendMail']) && $toInfos['sendMail'] == 1){
				self::sendMailEvent($oRecord);
			}
            return $oRecord ;
	}

	static function saveMultiple ($_tDate, $_tParams){
		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tdtcm_event_rdv								= explode(' ', $_tParams['dtcm_event_rdv']);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $tdtcm_event_rdv[1];
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

            //$oDaoFact->insert($oEvent) ;
			jClasses::inc('evenement~utilsEvenementSrv');
			utilsEvenementSrv::saveEvent($oEvent, $_tParams['x']) ;

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return true;
	}	

	static function saveMultipleQuotidienneParOccurence ($_tDate, $_tParams, $_oNewEvenement){

		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $tzDate[1];
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");		
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . " OR evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_DISPONIBLE.") "; 
 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					utilsEvenementSrv::saveEvent($oEvent, $_tParams['x']) ;
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return true;
	}
	
	static function saveMultipleQuotidienneParOccurenceAffectation ($_tDate, $_tParams, $_oNewEvenement){
		$tREventNonCreer = array () ;
		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $tzDate[1];
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");		
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					$tEventNonCreer = utilsEvenementSrv::saveEventAffectation($oEvent, $_tParams['x']) ;
					if (sizeof($tEventNonCreer) > 0){
						foreach ($tEventNonCreer as $oEventNonCreer){
							array_push ($tREventNonCreer, $oEventNonCreer); 
						}
					}
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return $tREventNonCreer;
	}


	static function saveMultipleQuotidienneParDateDefin ($_tDate, $_tParams, $_oNewEvenement){

		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					utilsEvenementSrv::saveEvent($oEvent, $_tParams['x']) ;
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return true;
	}	

	static function saveMultipleQuotidienneParDateDefinAffectation ($_tDate, $_tParams, $_oNewEvenement){
		$tREventNonCreer = array  (); 
		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					$tEventNonCreer = utilsEvenementSrv::saveEventAffectation($oEvent, $_tParams['x']) ;
					if (sizeof($tEventNonCreer) > 0){
						foreach ($tEventNonCreer as $oEventNonCreer){
							array_push ($tREventNonCreer, $oEventNonCreer); 
						}
					}
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return $tEventNonCreer;
	}

	static function saveMultipleHebdomadaireParOccurence ($_tDate, $_tParams, $_oNewEvenement){

		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					utilsEvenementSrv::saveEvent($oEvent, $_tParams['x']) ;
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return true;
	}

	static function saveMultipleHebdomadaireParDateDeFin ($_tDate, $_tParams, $_oNewEvenement){

		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					utilsEvenementSrv::saveEvent($oEvent, $_tParams['x']) ;
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return true;
	}

	static function saveMultipleMensuelleParOccurence ($_tDate, $_tParams, $_oNewEvenement){

		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					utilsEvenementSrv::saveEvent($oEvent, $_tParams['x']) ;
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return true;
	}

	static function saveMultipleMensuelleParDateDeFin ($_tDate, $_tParams, $_oNewEvenement){

		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
			$oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					utilsEvenementSrv::saveEvent($oEvent, $_tParams['x']) ;
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return true;
	}

	static function saveMultipleHebdomadaireParOccurenceAffectation ($_tDate, $_tParams, $_oNewEvenement){
		$tREventNonCreer = array (); 
		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					$tEventNonCreer = utilsEvenementSrv::saveEventAffectation($oEvent, $_tParams['x']) ;
					if (sizeof($tEventNonCreer) > 0){
						foreach ($tEventNonCreer as $oEventNonCreer){
							array_push ($tREventNonCreer, $oEventNonCreer); 
						}
					}
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return $tREventNonCreer;
	}

	static function saveMultipleHebdomadaireParDateDeFinAffectation ($_tDate, $_tParams, $_oNewEvenement){
		$tREventNonCreer = array (); 
		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					$tEventNonCreer = utilsEvenementSrv::saveEventAffectation($oEvent, $_tParams['x']) ;
					if (sizeof($tEventNonCreer) > 0){
						foreach ($tEventNonCreer as $oEventNonCreer){
							array_push ($tREventNonCreer, $oEventNonCreer); 
						}
					}
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return $tREventNonCreer;
	}

	static function saveMultipleMensuelleParOccurenceAffectation ($_tDate, $_tParams, $_oNewEvenement){
		$tREventNonCreer = array (); 
		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
            $oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					$tEventNonCreer = utilsEvenementSrv::saveEventAffectation($oEvent, $_tParams['x']) ;
					if (sizeof($tEventNonCreer) > 0){
						foreach ($tEventNonCreer as $oEventNonCreer){
							array_push ($tREventNonCreer, $oEventNonCreer); 
						}
					}
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return $tREventNonCreer;
	}

	static function saveMultipleMensuelleParDateDeFinAffectation ($_tDate, $_tParams, $_oNewEvenement){
		$tREventNonCreer = array (); 
		foreach ($_tDate as $oDate){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oEvent = null;
			$oEvent = jDao::createRecord('commun~evenement') ;
			$oEvent->evenement_id = NULL;
			$oEvent->evenement_iTypeEvenementId				= $_tParams['evenement_iTypeEvenementId'];
			$oEvent->evenement_iUtilisateurId				= $_tParams['evenement_iUtilisateurId'];
			$oEvent->evenement_zLibelle						= $_tParams['evenement_zLibelle'];
			$oEvent->evenement_zDescription					= $_tParams['evenement_zDescription'];
			$oEvent->evenement_iStagiaire					= $_tParams['evenement_iStagiaire'];
			$oEvent->evenement_zContactTel					= isset($_tParams['evenement_zContactTel']) ? $_tParams['evenement_zContactTel'] : NULL;
			$tzDate											= explode(' ', $oDate);
			$oEvent->evenement_zDateHeureDebut				= $oDate . " " . $_tParams['evenement_heureDebutRendezVous'].':00';
			$oEvent->evenement_zDateHeureSaisie				= isset($_tParams['evenement_zDateHeureSaisie']) ? $_tParams['evenement_zDateHeureSaisie'] : date("Y-m-d H:i:s");
			$oEvent->evenement_iDuree						= isset($_tParams['evenement_iDuree']) ? $_tParams['evenement_iDuree'] : NULL;
			$oEvent->evenement_iDureeTypeId					= isset($_tParams['evenement_iDureeTypeId']) ? $_tParams['evenement_iDureeTypeId'] : NULL;
			$oEvent->evenement_iPriorite					= isset($_tParams['evenement_iPriorite']) ? $_tParams['evenement_iPriorite'] : NULL;
			$oEvent->evenement_iRappel						= isset($_tParams['evenement_iRappel']) ? $_tParams['evenement_iRappel'] : NULL;
	        $oEvent->evenement_iTypeRappelId				= isset($_tParams['evenement_iTypeRappelId']) ? $_tParams['evenement_iTypeRappelId'] : NULL ;
			$oEvent->evenement_iStatut						= isset($_tParams['evenement_iStatut']) ? $_tParams['evenement_iStatut'] : NULL;
            $oEvent->evenement_origine					= isset($_tParams['evenement_origine']) ? $_tParams['evenement_origine'] : 2 ;

			jClasses::inc('commun~toolDate');
			$iDiff = intval(toolDate::date_diff($_oNewEvenement->evenement_zDateHeureDebut, $oEvent->evenement_zDateHeureDebut));
			if ($iDiff > 0 && $_oNewEvenement->evenement_zDateHeureDebut != $oEvent->evenement_zDateHeureDebut){
				$zdateFin = toolDate::getDateFin($oEvent) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oEvent->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oEvent->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oEvent->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oEvent->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " AND (evenement_iTypeEvenementId <> ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ." OR evenement_iTypeEvenementId <> " . ID_TYPE_EVENEMENT_DISPONIBLE . ") "; 
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
				$oDBW = jDb::getDbWidget() ;
				
				$toEvenementBase = $oDBW->fetchAll($zSql); 
				if (sizeof($toEvenementBase) == 0){
					jClasses::inc('evenement~utilsEvenementSrv');
					$tEventNonCreer = utilsEvenementSrv::saveEventAffectation($oEvent, $_tParams['x']) ;
					if (sizeof($tEventNonCreer) > 0){
						foreach ($tEventNonCreer as $oEventNonCreer){
							array_push ($tREventNonCreer, $oEventNonCreer); 
						}
					}
				}
			}

			if (isset($_tParams['sendMail']) && $_tParams['sendMail'] == 1){
				self::sendMailEvent($oEvent);
			}
		}
		return $tREventNonCreer;
	}

	/**
	 * Suppression d'un enregistrement
	 * @param int $_iId identifiant de l'objet
	 * @return boolean
	 */
	static function delete($_iId) 
	{
		$oDaoFact = jDao::get('commun~evenement') ;
        $oDaoFact->delete($_iId) ;
	}

	static function sendMailEvent ($_tEvent){
		jClasses::inc('client~clientSrv');
		jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$tzDateHeure = explode(' ', $_tEvent->evenement_zDateHeureDebut);
		$tzDate = explode('-', $tzDateHeure[0]);
		$zDate = $tzDate[2].'/'.$tzDate[1].'/'.$tzDate[0];
		$zHeure = $tzDateHeure[1];
		if (isset ($_tEvent->evenement_iStagiaire) && intval($_tEvent->evenement_iStagiaire) > 0){
			$oClient = clientSrv::getById($_tEvent->evenement_iStagiaire); 
			$oTypeEvenement = typeEvenementsSrv::getById($_tEvent->evenement_iTypeEvenementId); 
			$oUtilisateur = utilisateursSrv::getById($_tEvent->evenement_iUtilisateurId); 

			$tplMail = new jTpl();
			
			$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
			$tplMail->assign ('oClient', $oClient) ;
			$tplMail->assign ('oTypeEvenement', $oTypeEvenement) ;
			$tplMail->assign ('oUtilisateur', $oUtilisateur) ;
			$tplMail->assign ('zDate', $zDate) ;
			$tplMail->assign ('zHeure', $zHeure) ;
			$tplMail->assign ('tEvent', $_tEvent) ;

			$tpl = $tplMail->fetch ('evenement~corpsMailConfirmationCreationEvenement') ;

			mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , MAIL_OBJECT_CONFIRMATION_CREATION_EVENEMENT, $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;
		}
	}	

	static function suppressionMultipleEvent ($_zListeEvenementId){
		$zListeEvenementId = str_replace('@_@', ',', $_zListeEvenementId);
		$zQuery="DELETE FROM evenement WHERE evenement_id IN (".$zListeEvenementId.")";
		$oCnx = jDb::getConnection();
		$oRes = $oCnx->exec($zQuery);	
	}

	/*static function exportEventListing($_zExportsFullPath, $_toEvenement, $_toParams, $_toTypeEvenement = array(), $_oUtilisateur){
    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		require_once (LIB_PATH . "pear/Spreadsheet/Excel/Writer.php") ;
		$oWorkBook = new  Spreadsheet_Excel_Writer ($_zExportsFullPath) ;
		// --- Format titre

		$oTitleFormat =& $oWorkBook->addFormat () ;
		$oTitleFormat->setFontFamily ("Arial") ;
		$oTitleFormat->setSize (8) ;
		$oTitleFormat->setBold (1) ;
		$oTitleFormat->setColor('yellow');
		$oTitleFormat->setPattern (1) ;
		$oTitleFormat->setFgColor ('blue') ; 
		$oTitleFormat->setAlign ("centre") ;

		// --- Format d'entête

		$oHeaderFormat =& $oWorkBook->addFormat () ;
		$oHeaderFormat->setFontFamily ("Arial") ;
		$oHeaderFormat->setSize (10) ;
		$oHeaderFormat->setBold (1) ;
		$oHeaderFormat->setFgColor (22) ;
		$oHeaderFormat->setAlign ("centre") ;

		$oHeaderFormatEntete =& $oWorkBook->addFormat () ;
		$oHeaderFormatEntete->setFontFamily ("Arial") ;
		$oHeaderFormatEntete->setSize (10) ;
		$oHeaderFormatEntete->setBold (1) ;
		$oHeaderFormatEntete->setAlign ("centre") ;

		// --- Format de ligne
		$oLineFormatRight =& $oWorkBook->addFormat () ;
		$oLineFormatRight->setFontFamily ("Arial") ;
		$oLineFormatRight->setSize (8) ;
		$oLineFormatRight->setAlign ("right") ;

		//Date 	Durée 	Type d'événement 	Details du Stagiare 	Description de l'événement 	Actions
		$oWorkSheet =& $oWorkBook->addWorksheet (" Evenement ") ;
		$oWorkSheet->setColumn (0, 0, 50) ;//Date
		$oWorkSheet->setColumn (1, 1, 10) ;//Durée
		$oWorkSheet->setColumn (2, 2, 30) ;//Type d'événement 
		$oWorkSheet->setColumn (3, 3, 50) ;//Details du Stagiare
		$oWorkSheet->setColumn (4, 4, 50) ;//Description de l'événement

		$iLineIndex = 2 ;
		$iCol = 0;

		//ecriture de l'entete
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode(" Liste d'évènements pour " . $_oUtilisateur->utilisateur_zNom . " " . $_oUtilisateur->utilisateur_zPrenom), $oHeaderFormatEntete) ;

		$iLineIndex = 3 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode("De " . $_toParams[0]->zDateDebut . " à " . $_toParams[0]->zDateFin), $oHeaderFormatEntete) ;

		if (isset($_toParams[0]->evenement_origine) && $_toParams[0]->evenement_origine != 0){
			if ($_toParams[0]->evenement_origine == 1){
				$zOrigine = "Origine : Auto-planification"; 
			}else{
				$zOrigine = "Origine : Agenda"; 
			}
		}else{
			$zOrigine = "Origine : Tous"; 
		}
		if ($_toParams[0]->iTypeEvenement == 0){
			$zTypeEvenement = "Types d'événement : Tous les Types"; 
		}else{
			if (isset($toEvenement[0]->typeevenements_zLibelle)){
				$zTypeEvenement = "Types d'événement : " . $toEvenement[0]->typeevenements_zLibelle; 
			}elseif (isset($toTypeEvenementSelected[0]->typeevenements_zLibelle)){
				$zTypeEvenement = "Types d'événement : " . $toTypeEvenementSelected[0]->typeevenements_zLibelle; 
			}else{
				$zTypeEvenement = "Types d'événement : Tous les Types"; 
			}
		}	

		$iLineIndex = 4 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($zOrigine), $oHeaderFormatEntete) ;

		$iLineIndex = 5 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($zTypeEvenement), $oHeaderFormatEntete) ;

		$iLineIndex = 6 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode("Nombre d'événement trouvés :" . $_toEvenement['iResTotal']), $oHeaderFormatEntete) ;


		$iLineIndex = 9 ;
		$iCol = 0;

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode(" Date "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+1, utf8_decode(" Durée "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+2, utf8_decode(" Type d'événement "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+3, utf8_decode(" Details du Stagiare "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+4, utf8_decode(" Description de l'événement"), $oHeaderFormat) ;
		$iCpt = 1;
		
		$iLineIndex = 10 ;

		foreach ($_toEvenement['toListes'] as $oEvenement){
			if (floor($iCpt%2) == 1){
				$oLineFormatLeft =& $oWorkBook->addFormat () ;
				$oLineFormatLeft->setFontFamily ("Arial") ;
				$oLineFormatLeft->setSize (8) ;
				$oLineFormatLeft->setAlign ("left") ;

				$oLineFormatCenter =& $oWorkBook->addFormat () ;
				$oLineFormatCenter->setFontFamily ("Arial") ;
				$oLineFormatCenter->setSize (8) ;
				$oLineFormatCenter->setAlign ("centre") ;
			}else{
				$oLineFormatLeft =& $oWorkBook->addFormat () ;
				$oLineFormatLeft->setFontFamily ("Arial") ;
				$oLineFormatLeft->setSize (8) ;
				$oLineFormatLeft->setAlign ("left") ;
				$oLineFormatLeft->setFgColor (22) ;

				$oLineFormatCenter =& $oWorkBook->addFormat () ;
				$oLineFormatCenter->setFontFamily ("Arial") ;
				$oLineFormatCenter->setSize (8) ;
				$oLineFormatCenter->setAlign ("centre") ;
				$oLineFormatCenter->setFgColor (22) ;
			}
			$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($oEvenement->evenement_zDateJoursDeLaSemaine) . " " . utf8_decode(toolDate::toDateWebCalendarForXls($oEvenement->evenement_zDateHeureDebut)), $oLineFormatLeft) ;
			
			if (isset ($oEvenement->evenement_iDureeTypeId) && $oEvenement->evenement_iDureeTypeId == 1){
				$zDure = $oEvenement->evenement_iDuree . ' h';
			}else{
				$zDure = $oEvenement->evenement_iDuree . ' mn';
			}

			$oWorkSheet->writeString ($iLineIndex, $iCol+1, utf8_decode($zDure), $oLineFormatCenter) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol+2, utf8_decode($oEvenement->typeevenements_zLibelle), $oLineFormatCenter) ;
			if ($oEvenement->client_id > 0){
				$oWorkSheet->writeString ($iLineIndex, $iCol+3, utf8_decode('Numero :' . $oEvenement->client_id), $oLineFormatLeft) ;			

				$oWorkSheet->writeString ($iLineIndex+1, $iCol, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+2, $iCol, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+3, $iCol, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+4, $iCol, " ", $oLineFormatLeft) ;			

				$oWorkSheet->writeString ($iLineIndex+1, $iCol+1, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+2, $iCol+1, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+3, $iCol+1, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+4, $iCol+1, " ", $oLineFormatLeft) ;			

				$oWorkSheet->writeString ($iLineIndex+1, $iCol+2, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+2, $iCol+2, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+3, $iCol+2, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+4, $iCol+2, " ", $oLineFormatLeft) ;			

				$oWorkSheet->writeString ($iLineIndex+1, $iCol+4, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+2, $iCol+4, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+3, $iCol+4, " ", $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+4, $iCol+4, " ", $oLineFormatLeft) ;			

				$oWorkSheet->writeString ($iLineIndex, $iCol+3, utf8_decode('Société :' . $oEvenement->societe_zNom), $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+1, $iCol+3, utf8_decode('Nom :' . $oEvenement->client_zNom . " " . $oEvenement->client_zPrenom), $oLineFormatLeft) ;	
				$oWorkSheet->writeString ($iLineIndex+2, $iCol+3, utf8_decode('Tél :' . $oEvenement->client_zTel), $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+3, $iCol+3, utf8_decode('Portable :' . $oEvenement->client_zPortable), $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex+4, $iCol+3, utf8_decode('Tél pour le Jour :' . $oEvenement->evenement_zContactTel), $oLineFormatLeft) ;			
				$oWorkSheet->writeString ($iLineIndex, $iCol+4, utf8_decode($oEvenement->evenement_zDescription), $oLineFormatLeft) ;
				$iLineIndex = $iLineIndex+4;
			}else{
				$oWorkSheet->writeString ($iLineIndex, $iCol+3, "-", $oLineFormatCenter) ;						
				$oWorkSheet->writeString ($iLineIndex, $iCol+4, utf8_decode($oEvenement->evenement_zDescription), $oLineFormatLeft) ;
			}
			$iLineIndex++ ;
			$iCpt++;
		}

		$oWorkBook->close () ;

		if (is_file ($_zExportsFullPath) ) {
			@chmod ($_zExportsFullPath, 0666) ;			
		}
		unset ($oWorkSheet) ;
		unset ($oWorkBook) ;
	}*/

		static function exportEventListing($_zExportsFullPath, $_toEvenement, $_toParams, $_toTypeEvenement = array(), $_oUtilisateur){
    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		require_once (LIB_PATH . "pear/Spreadsheet/Excel/Writer.php") ;
		$oWorkBook = new  Spreadsheet_Excel_Writer ($_zExportsFullPath) ;
		// --- Format titre

		$oTitleFormat =& $oWorkBook->addFormat () ;
		$oTitleFormat->setFontFamily ("Arial") ;
		$oTitleFormat->setSize (8) ;
		$oTitleFormat->setBold (1) ;
		$oTitleFormat->setColor('yellow');
		$oTitleFormat->setPattern (1) ;
		$oTitleFormat->setFgColor ('blue') ; 
		$oTitleFormat->setAlign ("centre") ;

		// --- Format d'entête

		$oHeaderFormat =& $oWorkBook->addFormat () ;
		$oHeaderFormat->setFontFamily ("Arial") ;
		$oHeaderFormat->setSize (10) ;
		$oHeaderFormat->setBold (1) ;
		$oHeaderFormat->setFgColor (22) ;
		$oHeaderFormat->setAlign ("centre") ;

		$oHeaderFormat->setBottom (1) ;
		$oHeaderFormat->setTop (1) ;
		$oHeaderFormat->setLeft (1) ;
		$oHeaderFormat->setRight (1) ;

		$oHeaderFormatEntete =& $oWorkBook->addFormat () ;
		$oHeaderFormatEntete->setFontFamily ("Arial") ;
		$oHeaderFormatEntete->setSize (10) ;
		$oHeaderFormatEntete->setBold (1) ;
		$oHeaderFormatEntete->setAlign ("centre") ;

		// --- Format de ligne
		$oLineFormatRight =& $oWorkBook->addFormat () ;
		$oLineFormatRight->setFontFamily ("Arial") ;
		$oLineFormatRight->setSize (8) ;
		$oLineFormatRight->setAlign ("right") ;

		//Date 	Durée 	Type d'événement 	Details du Stagiare 	Description de l'événement 	Actions
		$oWorkSheet =& $oWorkBook->addWorksheet (" Evenement ") ;
		$oWorkSheet->setColumn (0, 0, 20) ;//Date
		$oWorkSheet->setColumn (1, 1, 10) ;//Heure
		$oWorkSheet->setColumn (2, 2, 30) ;//Type d'événement 
		$oWorkSheet->setColumn (3, 3, 100) ;//Description de l'événement
		$oWorkSheet->setColumn (4, 4, 50) ;//Description de l'événement

		$iLineIndex = 2 ;
		$iCol = 0;

		//ecriture de l'entete
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode(" Liste d'évènements pour " . $_oUtilisateur->utilisateur_zNom . " " . $_oUtilisateur->utilisateur_zPrenom), $oHeaderFormatEntete) ;

		$iLineIndex = 3 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode("De " . $_toParams[0]->zDateDebut . " à " . $_toParams[0]->zDateFin), $oHeaderFormatEntete) ;

		if (isset($_toParams[0]->evenement_origine) && $_toParams[0]->evenement_origine != 0){
			if ($_toParams[0]->evenement_origine == 1){
				$zOrigine = "Origine : Auto-planification"; 
			}else{
				$zOrigine = "Origine : Agenda"; 
			}
		}else{
			$zOrigine = "Origine : Tous"; 
		}
		if ($_toParams[0]->iTypeEvenement == 0){
			$zTypeEvenement = "Types d'événement : Tous les Types"; 
		}else{
			if (isset($_toEvenement['toListes'][0]->typeevenements_zLibelle)){
				$zTypeEvenement = "Types d'événement : " . $_toEvenement['toListes'][0]->typeevenements_zLibelle; 
			}elseif (isset($toTypeEvenementSelected[0]->typeevenements_zLibelle)){
				$zTypeEvenement = "Types d'événement : " . $toTypeEvenementSelected[0]->typeevenements_zLibelle; 
			}else{
				$zTypeEvenement = "Types d'événement : Tous les Types"; 
			}
		}			
		$iLineIndex = 4 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($zOrigine), $oHeaderFormatEntete) ;

		$iLineIndex = 5 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($zTypeEvenement), $oHeaderFormatEntete) ;

		$iLineIndex = 6 ;
		$iCol = 0;
		for($i=0;$i<=4;$i++){
		  $oWorkSheet->setMerge ($iLineIndex, $iCol,$iLineIndex, $iCol+$i);
        }
		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode("Nombre d'événement trouvés :" . $_toEvenement['iResTotal']), $oHeaderFormatEntete) ;


		$iLineIndex = 9 ;
		$iCol = 0;

		$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode(" Date "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+1, utf8_decode(" Heure "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+2, utf8_decode(" Type d'événement "), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+3, utf8_decode(" Description de l'événement"), $oHeaderFormat) ;
		$oWorkSheet->writeString ($iLineIndex, $iCol+4, utf8_decode(" Collaborateur"), $oHeaderFormat) ;
		$iCpt = 1;
		
		$iLineIndex = 10 ;
		$oLineFormatLeft =& $oWorkBook->addFormat () ;
		$oLineFormatLeft->setFontFamily ("Arial") ;
		$oLineFormatLeft->setSize (8) ;
		$oLineFormatLeft->setAlign ("left") ;
		$oLineFormatLeft->setFgColor ("white") ;

		$oLineFormatLeft->setBottom (1) ;
		$oLineFormatLeft->setTop (1) ;
		$oLineFormatLeft->setLeft (1) ;
		$oLineFormatLeft->setRight (1) ;

		$oLineFormatCenter =& $oWorkBook->addFormat () ;
		$oLineFormatCenter->setFontFamily ("Arial") ;
		$oLineFormatCenter->setSize (8) ;
		$oLineFormatCenter->setAlign ("centre") ;
		$oLineFormatCenter->setFgColor ("white") ;

		$oLineFormatCenter->setBottom (1) ;
		$oLineFormatCenter->setTop (1) ;
		$oLineFormatCenter->setLeft (1) ;
		$oLineFormatCenter->setRight (1) ;

		foreach ($_toEvenement['toListes'] as $oEvenement){
			//$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode($oEvenement->evenement_zDateJoursDeLaSemaine) . " " . utf8_decode(self::toDateWebCalendarForXls($oEvenement->evenement_zDateHeureDebut)), $oLineFormatLeft) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol, utf8_decode(self::toDateWebCalendarForXls($oEvenement->evenement_zDateHeureDebut)), $oLineFormatLeft) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol+1, utf8_decode(self::toHeureWebCalendarForXls($oEvenement->evenement_zDateHeureDebut)), $oLineFormatCenter) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol+2, utf8_decode($oEvenement->typeevenements_zLibelle), $oLineFormatCenter) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol+3, utf8_decode($oEvenement->evenement_zDescription), $oLineFormatCenter) ;
			$oWorkSheet->writeString ($iLineIndex, $iCol+4, utf8_decode($oEvenement->utilisateur_zNom . " " . $oEvenement->utilisateur_zPrenom), $oLineFormatCenter) ;

			$iLineIndex++ ;
			$iCpt++;
		}

		$oWorkBook->close () ;

		if (is_file ($_zExportsFullPath) ) {
			@chmod ($_zExportsFullPath, 0666) ;			
		}
		unset ($oWorkSheet) ;
		unset ($oWorkBook) ;
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
			
			$zDateHeurFr = $tD[2] . "/" . $tD[1] . "/" . $tD[0];
			return $zDateHeurFr;
		}
		return "";
	}

	static function toHeureWebCalendarForXls($_zDatesql){
		$_zDatesql = trim($_zDatesql);
		if(strlen($_zDatesql)>=10 && $_zDatesql!="0000-00-00 00:00:00") {
			$_tzDatesql = explode(" ", $_zDatesql);
			return $_tzDatesql[1];
		}
		return "";
	}


	static function exportIcsEventListing ($_zExportsFullPath, $_toEvenement){
        if ($_zExportsFullPath != "")
        {
            $iIcsFileFd = fopen ($_zExportsFullPath, "w+") ;
            if ($iIcsFileFd === false)
            {
                $iIcsFileFd = 0 ;
				die('Erreur lors de la création du fichier ics');
            }
			$iCsEntete = "BEGIN:VCALENDAR\n";
			//$iCsEntete .= "VERSION:2.0\n";
			$iCsEntete .= "PRODID:-//The Horde Project//Horde_iCalendar Library//EN\n";
			$iCsEntete .= "METHOD:PUBLISH\n";
			fputs ($iIcsFileFd, $iCsEntete) ;
			foreach ($_toEvenement['toListes'] as $oEvenement){
				$iCsEvent = "BEGIN:VEVENT\n";
				$iCsEvent .= "DTSTART:".self::dateToIcsCal($oEvenement->evenement_zDateHeureDebut)."\n";
				$iCsEvent .= "DTEND:".self::getDateFinByDure($oEvenement->evenement_zDateHeureDebut, $oEvenement->evenement_iDuree, $oEvenement->evenement_iDureeTypeId)."\n";
				$iCsEvent .= "DTSTAMP:".self::dateToIcsCal(date("Y-m-d h:i:s"))."\n";
				if ($oEvenement->evenement_zLibelle != ""){
					$zSummary = $oEvenement->evenement_zLibelle;
				}else{
					$zSummary = $oEvenement->typeevenements_zLibelle;	
				}
				if ($oEvenement->client_id > 0){
					$zSummary .= $oEvenement->client_zNom . " " . $oEvenement->client_zPrenom;
				}
				$iCsEvent .= "SUMMARY:".addslashes($zSummary)."\n";
				$iCsEvent .= "LOCATION:"."\n";
				$iCsEvent .= "CATEGORIES:".addslashes($oEvenement->typeevenements_zLibelle)."\n";
				$iCsEvent .= "STATUS:"."\n";
				$iCsEvent .= "TRANSP:OPAQUE\n";
				$iCsEvent .= "ORGANIZER:MAILTO:\n";
				if ($oEvenement->evenement_zDescription != ""){
					$zDescription = $oEvenement->evenement_zDescription;
				}else{
					$zDescription = $oEvenement->typeevenements_zLibelle;	
				}
				if ($oEvenement->evenement_zContactTel != ""){
					$zDescription .= " / Tel pour ce jours > " . $oEvenement->evenement_zContactTel;
				}
				if ($oEvenement->client_id > 0){
					$zDescription .= " / Stagiaire > " . $oEvenement->client_zNom . " " . $oEvenement->client_zPrenom;
					$zDescription .= " / Email > " . $oEvenement->client_zMail;
					$zDescription .= " / Tel > " . $oEvenement->client_zTel;
					$zDescription .= " / Portable > " . $oEvenement->client_zPortable;
					$zDescription .= " / Adresse > " . $oEvenement->client_zRue." ".$oEvenement->client_zVille." - ".$oEvenement->client_zCP;
					$zDescription .= " / Numero individuel > " . $oEvenement->client_iNumIndividu;
				}
				$iCsEvent .= "DESCRIPTION:".addslashes(nl2br($zDescription))."\n";
				$iCsEvent .= "END:VEVENT\n";

				fputs ($iIcsFileFd, $iCsEvent) ;
			}
			$iCsPied = "END:VCALENDAR\n";
			fputs ($iIcsFileFd, $iCsPied) ;
		}else{
			die('Erreur lors de la création du fichier ics');
		}
		return true;
	}

	static function dateToIcsCal($zDateTime) {
		$zDateTime = str_replace (" ", "T", $zDateTime);
		$zDateTime = str_replace ("-", "", $zDateTime);
		$zDateTime = str_replace (":", "", $zDateTime);
		return $zDateTime . 'Z';
	}

	static function getDateFinByDure ($_zDebut, $_iDure, $_iType){
		if (intval($_iDure) > 0){
			if ($_iType == 1){//Heure
				$zSql = "SELECT DATE_ADD('".$_zDebut."', INTERVAL ".intval($_iDure)." HOUR) as dateEnd"; 
			}else{//Minutes
				$zSql = "SELECT DATE_ADD('".$_zDebut."', INTERVAL ".intval($_iDure)." MINUTE) dateEnd"; 
			}
			$oDBW	  = jDb::getDbWidget() ;
			$oDate = $oDBW->fetchFirst($zSql) ;
			return self::dateToIcsCal($oDate->dateEnd) ;
		}else{
			return self::dateToIcsCal($_zDebut); 
		}
	}

	static function getNextEventDispo ($zDateHeureDebutEn){
		jClasses::inc('commun~toolDate');
		$iNextEvent = 0 ;
		$zDateNext = toolDate::getNextDateDispo ($zDateHeureDebutEn) ;
		if ($zDateNext != ""){
			$zSqlNextEvent = "SELECT evenement.evenement_id AS iNextEvent FROM evenement WHERE evenement.evenement_zDateHeureDebut = '".$zDateNext."' && evenement.evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_DISPONIBLE ;
			$oDBW1	= jDb::getDbWidget() ;
			$oNextEvent	= $oDBW1->fetchFirst($zSqlNextEvent); 
			if (!is_null($oNextEvent) && isset ($oNextEvent->iNextEvent) && $oNextEvent->iNextEvent > 0){
				$iNextEvent = $oNextEvent->iNextEvent ;
			}
		}
		return $iNextEvent ;
	}
	static function getNextEvent ($zDateHeureDebutEn){
		jClasses::inc('commun~toolDate');
		$iNextEvent = 0 ;
		$zDateNext = toolDate::getNextDateDispo ($zDateHeureDebutEn) ;
		if ($zDateNext != ""){
			$zSqlNextEvent = "SELECT evenement.evenement_id AS iNextEvent FROM evenement WHERE evenement.evenement_zDateHeureDebut = '".$zDateNext."'" ;
			$oDBW1	= jDb::getDbWidget() ;
			$oNextEvent	= $oDBW1->fetchFirst($zSqlNextEvent); 
			if (!is_null($oNextEvent) && isset ($oNextEvent->iNextEvent) && $oNextEvent->iNextEvent > 0){
				$iNextEvent = $oNextEvent->iNextEvent ;
			}
		}
		return $iNextEvent ;
	}
	static function insertEventAlo ($toInfos){
		$oDaoFact = jDao::get('commun~evenement') ;
		$oRecord = null;
		$oRecord = jDao::createRecord('commun~evenement') ;

		$oRecord->evenement_iTypeEvenementId    = $toInfos['evenement_iTypeEvenementId'];
		$oRecord->evenement_iUtilisateurId		= $toInfos['evenement_iUtilisateurId'];
		$oRecord->evenement_zLibelle			= $toInfos['evenement_zLibelle'] ;
		$oRecord->evenement_zDescription		= $toInfos['evenement_zDescription'] ;
		$oRecord->evenement_iStagiaire			= $toInfos['evenement_iStagiaire'] ;
		$oRecord->evenement_zContactTel			= $toInfos['evenement_zContactTel'] ;

		$tzDateHeure = explode(" ", $toInfos['evenement_zDateHeureDebut']);
		$tzDate = explode("/", $tzDateHeure[0]);
		$oRecord->evenement_zDateHeureDebut = $tzDate[2] . "-" . $tzDate[1] . "-" . $tzDate[0] . " " . $tzDateHeure[1];

		$oRecord->evenement_zDateHeureSaisie	= $toInfos['evenement_zDateHeureSaisie'] ;
		$oRecord->evenement_iDuree				= $toInfos['evenement_iDuree'] ;
		$oRecord->evenement_iDureeTypeId		= $toInfos['evenement_iDureeTypeId'] ;
		$oRecord->evenement_iPriorite			= $toInfos['evenement_iPriorite'] ;
		$oRecord->evenement_iRappel				= $toInfos['evenement_iRappel'] ;
		$oRecord->evenement_iStatut				= $toInfos['evenement_iStatut'] ;
		$oRecord->evenement_origine				= $toInfos['evenement_origine'] ;

		$oDaoFact->insert($oRecord);
	}
}
?>