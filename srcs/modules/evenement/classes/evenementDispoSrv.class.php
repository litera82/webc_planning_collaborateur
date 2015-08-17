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

class evenementDispoSrv {

	static function getEventByDate ($_zDateDebut, $_iInterval, $_iUtilisateurId){
		jClasses::inc('evenement~evenementSrv');
		switch ($_iInterval){
			case 2: // 30 Minutes
				$zInterval = "30 MINUTE" ;
			break;
			case 3: // 20 Minutes
				$zInterval = "20 MINUTE" ;
			break;
			default:
				$zInterval = "60 MINUTE" ;
		}
		$zDateFin = toolDate::dateAdd($_zDateDebut, $zInterval);
		/*$zSql = "SELECT
				  COUNT(*) AS iNbreEvent
				FROM evenement
				WHERE evenement_iUtilisateurId = ".$_iUtilisateurId." 
					AND evenement_zDateHeureDebut >= '".$_zDateDebut."'
					AND evenement_zDateHeureDebut < '".$zDateFin."'";*/
					
		/*$zSql = "SELECT
				  COUNT(*) AS iNbreEvent
				FROM evenement
				WHERE evenement_iUtilisateurId = ".$_iUtilisateurId." 
					AND evenement_zDateHeureDebut BETWEEN '".$_zDateDebut."'
					AND '".$zDateFin."'"; */

			$zSql = "SELECT
				  evenement_id as id
				FROM evenement
				WHERE evenement_iUtilisateurId = ".$_iUtilisateurId." 
					AND evenement_zDateHeureDebut BETWEEN '".$_zDateDebut."'
					AND '".$zDateFin."'";
		$oDBW	  = jDb::getDbWidget() ;
		$oEventId = $oDBW->fetchFirst($zSql) ;
		if (isset($oEventId) && !is_null($oEventId) && isset($oEventId->id) && !is_null($oEventId->id) && $oEventId->id > 0){
			$oEvent = evenementSrv::getById ($oEventId->id) ;
			if ($oEvent->evenement_iDureeTypeId == 1){ // Heure
				$iTotal = $oEvent->evenement_iDuree * 60 ;
				$zInterval = intval($iTotal-1) . ' MINUTE'; 
			}else{ // Minute
				$zInterval = intval($oEvent->evenement_iDuree-1) . ' MINUTE'; 
			}
			$zDateFin = toolDate::dateAdd($_zDateDebut, $zInterval);
			$zSql = "SELECT
				  COUNT(*) AS iNbreEvent
				FROM evenement
				WHERE evenement_iUtilisateurId = ".$_iUtilisateurId." 
					AND evenement_zDateHeureDebut BETWEEN '".$_zDateDebut."'
					AND '".$zDateFin."'";
		$oDBW	  = jDb::getDbWidget() ;
		$oCount = $oDBW->fetchFirst($zSql) ;
			$iCpt = $oCount->iNbreEvent;
		}else{
			$iCpt = 0 ;
		}

		return $iCpt;
	}	


	static function insertEventDispo($iTypeEvent, $iUtilisateurId, $zDateHeureDebut, $iDure, $iDureeTypeId){
		$zQuery = "INSERT INTO evenement (evenement_iTypeEvenementId, evenement_iUtilisateurId, evenement_zLibelle, evenement_zDescription, evenement_iStagiaire, evenement_zContactTel, evenement_zDateHeureDebut, evenement_zDateHeureSaisie, evenement_iDuree, evenement_iDureeTypeId, evenement_iPriorite, evenement_iRappel, evenement_iTypeRappelId, evenement_iStatut) VALUES (".$iTypeEvent.", ".$iUtilisateurId.", '', '', 0, '', '".$zDateHeureDebut."', '".date("Y-m-d H:i:s")."', ".$iDure.", ".$iDureeTypeId.", 1, 0, NULL, 1);" ;
		$oCnx = jDb::getConnection();
		$oRes = $oCnx->exec($zQuery);	
	}

	static function generateEventDispoIndispo($iTypeEvent, $iUtilisateurId, $zDateHeureDebut, $iDure, $iDureeTypeId){
		return "INSERT INTO evenement (evenement_iTypeEvenementId, evenement_iUtilisateurId, evenement_zLibelle, evenement_zDescription, evenement_iStagiaire, evenement_zContactTel, evenement_zDateHeureDebut, evenement_zDateHeureSaisie, evenement_iDuree, evenement_iDureeTypeId, evenement_iPriorite, evenement_iRappel, evenement_iTypeRappelId, evenement_iStatut) VALUES (".$iTypeEvent.", ".$iUtilisateurId.", '', '', 0, '', '".$zDateHeureDebut."', '".date("Y-m-d H:i:s")."', ".$iDure.", ".$iDureeTypeId.", 1, 0, NULL, 1);" ;
	}

	static function insertEventDispoIndispo($zSql){
		$oCnx = jDb::getConnection();
		$oRes = $oCnx->exec($zSql);	
	}
}
?>