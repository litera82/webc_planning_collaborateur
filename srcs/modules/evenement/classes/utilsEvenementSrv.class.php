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
class utilsEvenementSrv 
{
	
	static function saveEvent ($_oRecord, $_x){
		jClasses::inc('typeEvenement~typeEvenementsSrv');
		$zDureeTypeEvent = "";
		$zDureeEvent = "";
		$oTypeEvenementSrv = typeEvenementsSrv::getById($_oRecord->evenement_iTypeEvenementId);
		if ($oTypeEvenementSrv->typeevenements_iDureeTypeId == 1){
			$zDureeTypeEvent = $oTypeEvenementSrv->typeevenements_iDure . ' heures' ; 
		}else{
			$zDureeTypeEvent = $oTypeEvenementSrv->typeevenements_iDure . ' minutes' ; 
		}

		if ($_oRecord->evenement_iDureeTypeId == 1){
			$zDureeEvent = $_oRecord->evenement_iDuree . ' heures' ; 
		}else{
			$zDureeEvent = $_oRecord->evenement_iDuree . ' minutes' ; 
		}

		if (strval($zDureeEvent) == strval($zDureeTypeEvent)){

			$oDaoFact = jDao::get('commun~evenement') ;
			$oRecord = jDao::createRecord('commun~evenement') ;

			$oRecord->evenement_iTypeEvenementId	=	$_oRecord->evenement_iTypeEvenementId	;
			$oRecord->evenement_iUtilisateurId		=	$_oRecord->evenement_iUtilisateurId	;
			$oRecord->evenement_zLibelle			=	$_oRecord->evenement_zLibelle	;
			$oRecord->evenement_zDescription		=	$_oRecord->evenement_zDescription	;
			$oRecord->evenement_iStagiaire			=	$_oRecord->evenement_iStagiaire	;
			$oRecord->evenement_zContactTel			=	$_oRecord->evenement_zContactTel	;
			$oRecord->evenement_zDateHeureDebut		=	$_oRecord->evenement_zDateHeureDebut	;
			$oRecord->evenement_zDateHeureSaisie	=	$_oRecord->evenement_zDateHeureSaisie	;
			$oRecord->evenement_iDuree				=	$_oRecord->evenement_iDuree	;
			$oRecord->evenement_iDureeTypeId		=	$_oRecord->evenement_iDureeTypeId	;
			$oRecord->evenement_iPriorite			=	$_oRecord->evenement_iPriorite	;
			$oRecord->evenement_iRappel				=	$_oRecord->evenement_iRappel	;
			$oRecord->evenement_iTypeRappelId		=	$_oRecord->evenement_iTypeRappelId	;
			$oRecord->evenement_iStatut				=	$_oRecord->evenement_iStatut	;
			$oRecord->evenement_origine				=	$_oRecord->evenement_origine	;

			$zdateFin = toolDate::getDateFin($oRecord) ;

			$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId." "; 
			if ($zdateFin != ""&& $oRecord->evenement_zDateHeureDebut != $zdateFin){
				$zSql .= " AND evenement_zDateHeureDebut >= '".$oRecord->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
			}else{
				$zSql .= " AND evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."' "; 
			}
			$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;

			$oDBW = jDb::getDbWidget() ;
			
			$toEvenementBase = $oDBW->fetchAll($zSql); 
			if (sizeof($toEvenementBase) > 0){

				foreach ($toEvenementBase as $oEvenementBase){
					if ($oEvenementBase->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_DISPONIBLE || $oEvenementBase->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE){
						$zQuery = " DELETE FROM evenement WHERE evenement_id IN (".$oEvenementBase->evenement_id.")";
						$oCnx = jDb::getConnection();
						$oRes = $oCnx->exec($zQuery);
						$zSql = " DELETE FROM evenement 
						WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
						AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
						AND evenement_iTypeEvenementId = ".$oRecord->evenement_iTypeEvenementId ;
						$oCnx = jDb::getConnection();
						$oRes = $oCnx->exec($zSql);	
						$oDaoFact->insert($oRecord) ;
					}
				}
			}else{
				$zSql = " DELETE FROM evenement 
				WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
				AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
				AND evenement_iTypeEvenementId = ".$oRecord->evenement_iTypeEvenementId ;
				$zSql1 = " DELETE FROM evenement 
				WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
				AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
				AND (evenement_iTypeEvenementId = ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . " OR evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_DISPONIBLE . " ) " ;

				$oCnx = jDb::getConnection();
				$oRes = $oCnx->exec($zSql);	
				$oRes = $oCnx->exec($zSql1);
				
				$oDaoFact->insert($oRecord) ;
			}
		}else{

			$iDureEnMinute = 0;
			$iDureEnMinuteTypeEvent = 0;
			if ($_oRecord->evenement_iDureeTypeId == 1){
				$iDureEnMinuteEvent = intval($_oRecord->evenement_iDuree) * 60 ;
			}else{
				$iDureEnMinuteEvent = intval($_oRecord->evenement_iDuree);
			}

			if ($oTypeEvenementSrv->typeevenements_iDureeTypeId == 1){
				$iDureEnMinuteTypeEvent = intval($oTypeEvenementSrv->typeevenements_iDure) * 60 ;
			}else{
				$iDureEnMinuteTypeEvent = intval($oTypeEvenementSrv->typeevenements_iDure) ;
			}

			$iEventParTypeEnvent = ceil($iDureEnMinuteEvent / $iDureEnMinuteTypeEvent);  
			$i=1;
			while ($i<=$iEventParTypeEnvent){
				$oDaoFact = jDao::get('commun~evenement') ;
				$oRecord = jDao::createRecord('commun~evenement') ;
				
				$oDureEvent = self::getNextDateHeureEvent ($_oRecord->evenement_zDateHeureDebut, $oTypeEvenementSrv->typeevenements_iDure, $oTypeEvenementSrv->typeevenements_iDureeTypeId, $i) ; 
				
				$oRecord->evenement_iTypeEvenementId	=	$_oRecord->evenement_iTypeEvenementId	;
				$oRecord->evenement_iUtilisateurId		=	$_oRecord->evenement_iUtilisateurId	;
				$oRecord->evenement_zLibelle			=	$_oRecord->evenement_zLibelle	;
				$oRecord->evenement_zDescription		=	$_oRecord->evenement_zDescription	;
				$oRecord->evenement_iStagiaire			=	$_oRecord->evenement_iStagiaire	;
				$oRecord->evenement_zContactTel			=	$_oRecord->evenement_zContactTel	;
				$oRecord->evenement_zDateHeureDebut		=	$oDureEvent->evenement_zDateHeureDebut	;
				$oRecord->evenement_zDateHeureSaisie	=	$_oRecord->evenement_zDateHeureSaisie	;
				$oRecord->evenement_iDuree				=	$oDureEvent->evenement_iDuree ;
				$oRecord->evenement_iDureeTypeId		=	$oDureEvent->evenement_iDureeTypeId	;
				$oRecord->evenement_iPriorite			=	$_oRecord->evenement_iPriorite	;
				$oRecord->evenement_iRappel				=	$_oRecord->evenement_iRappel	;
				$oRecord->evenement_iTypeRappelId		=	$_oRecord->evenement_iTypeRappelId	;
				$oRecord->evenement_iStatut				=	$_oRecord->evenement_iStatut	;
				$oRecord->evenement_origine				=	$_oRecord->evenement_origine	;
				$zdateFin = toolDate::getDateFin($oRecord) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oRecord->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oRecord->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
					$oDBW = jDb::getDbWidget() ;
					$toEvenementBase = $oDBW->fetchAll($zSql); 

					if (sizeof($toEvenementBase) > 0){
						foreach ($toEvenementBase as $oEvenementBase){
							if ($oEvenementBase->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_DISPONIBLE || $oEvenementBase->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE){
								$zQuery = " DELETE FROM evenement WHERE evenement_id IN (".$oEvenementBase->evenement_id.")";
								$oCnx = jDb::getConnection();
								$oRes = $oCnx->exec($zQuery);

								$zSql = " DELETE FROM evenement 
								WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
								AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
								AND evenement_iTypeEvenementId = ".$oRecord->evenement_iTypeEvenementId ;
								$oCnx = jDb::getConnection();
								$oRes = $oCnx->exec($zSql);	
								$oDaoFact->insert($oRecord) ;
							}
						}
					}else{
						$zSql = " DELETE FROM evenement 
						WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
						AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
						AND evenement_iTypeEvenementId = ".$oRecord->evenement_iTypeEvenementId ;

						$zSql1 = " DELETE FROM evenement 
						WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
						AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
						AND (evenement_iTypeEvenementId = ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . " OR evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_DISPONIBLE . " ) " ;

						$oCnx = jDb::getConnection();
						$oRes = $oCnx->exec($zSql);	
						$oRes = $oCnx->exec($zSql1);
						$oDaoFact->insert($oRecord) ;
					}
				$i++;
			}
		}
	}

	static function getNextDateHeureEvent ($zDateHeureDebut, $iDure, $iDureeTypeId=1, $iCptr){
		$oDureEvent = new stdClass ();	
		if ($iCptr == 1){
			$oDureEvent->evenement_zDateHeureDebut		= $zDateHeureDebut ; 
		}else{
			$zSql = "SELECT DATE_ADD('".$zDateHeureDebut."', INTERVAL ".intval(($iCptr-1)*$iDure)."" ; 
			if ($iDureeTypeId == 1){
				$zSql .= " HOUR) AS zDate";	
			}else{
				$zSql .= " MINUTE) AS zDate";	
			}

			$oDBW		= jDb::getDbWidget() ;
			$oDate		= $oDBW->fetchFirst($zSql); 
		
			$oDureEvent->evenement_zDateHeureDebut		= $oDate->zDate ; 
		}
		$oDureEvent->evenement_iDuree				= $iDure ; 
		$oDureEvent->evenement_iDureeTypeId			= $iDureeTypeId ; 

		return $oDureEvent ;
	}


	static function saveEventAffectation ($_oRecord, $_x){

		$tEventNonCreer = array ();
		jClasses::inc('typeEvenement~typeEvenementsSrv');
		$zDureeTypeEvent = "";
		$zDureeEvent = "";
		$oTypeEvenementSrv = typeEvenementsSrv::getById($_oRecord->evenement_iTypeEvenementId);
		if ($oTypeEvenementSrv->typeevenements_iDureeTypeId == 1){
			$zDureeTypeEvent = $oTypeEvenementSrv->typeevenements_iDure . ' heures' ; 
		}else{
			$zDureeTypeEvent = $oTypeEvenementSrv->typeevenements_iDure . ' minutes' ; 
		}

		if ($_oRecord->evenement_iDureeTypeId == 1){
			$zDureeEvent = $_oRecord->evenement_iDuree . ' heures' ; 
		}else{
			$zDureeEvent = $_oRecord->evenement_iDuree . ' minutes' ; 
		}

		if (strval($zDureeEvent) == strval($zDureeTypeEvent)){
			$oDaoFact = jDao::get('commun~evenement') ;
			$oRecord = jDao::createRecord('commun~evenement') ;

			$oRecord->evenement_iTypeEvenementId	=	$_oRecord->evenement_iTypeEvenementId	;
			$oRecord->evenement_iUtilisateurId		=	$_oRecord->evenement_iUtilisateurId	;
			$oRecord->evenement_zLibelle			=	$_oRecord->evenement_zLibelle	;
			$oRecord->evenement_zDescription		=	$_oRecord->evenement_zDescription	;
			$oRecord->evenement_iStagiaire			=	$_oRecord->evenement_iStagiaire	;
			$oRecord->evenement_zContactTel			=	$_oRecord->evenement_zContactTel	;
			$oRecord->evenement_zDateHeureDebut		=	$_oRecord->evenement_zDateHeureDebut	;
			$oRecord->evenement_zDateHeureSaisie	=	$_oRecord->evenement_zDateHeureSaisie	;
			$oRecord->evenement_iDuree				=	$_oRecord->evenement_iDuree	;
			$oRecord->evenement_iDureeTypeId		=	$_oRecord->evenement_iDureeTypeId	;
			$oRecord->evenement_iPriorite			=	$_oRecord->evenement_iPriorite	;
			$oRecord->evenement_iRappel				=	$_oRecord->evenement_iRappel	;
			$oRecord->evenement_iTypeRappelId		=	$_oRecord->evenement_iTypeRappelId	;
			$oRecord->evenement_iStatut				=	$_oRecord->evenement_iStatut	;
			$oRecord->evenement_origine				=	$_oRecord->evenement_origine	;

			$zdateFin = toolDate::getDateFin($oRecord) ;

			$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId." "; 
			if ($zdateFin != ""&& $oRecord->evenement_zDateHeureDebut != $zdateFin){
				$zSql .= " AND evenement_zDateHeureDebut >= '".$oRecord->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
			}else{
				$zSql .= " AND evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."' "; 
			}
			$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;

			$oDBW = jDb::getDbWidget() ;
			
			$toEvenementBase = $oDBW->fetchAll($zSql); 
			if (sizeof($toEvenementBase) > 0){
				foreach ($toEvenementBase as $oEvenementBase){
					if ($oEvenementBase->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_DISPONIBLE || $oEvenementBase->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE){
						$zQuery = " DELETE FROM evenement WHERE evenement_id IN (".$oEvenementBase->evenement_id.")";
						$oCnx = jDb::getConnection();
						$oRes = $oCnx->exec($zQuery);
						$zSql = " DELETE FROM evenement 
						WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
						AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
						AND evenement_iTypeEvenementId = ".$oRecord->evenement_iTypeEvenementId ;
						$oCnx = jDb::getConnection();
						$oRes = $oCnx->exec($zSql);	
						$oDaoFact->insert($oRecord) ;
					}
				}
			}else{
				$zSql = " DELETE FROM evenement 
				WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
				AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
				AND evenement_iTypeEvenementId = ".$oRecord->evenement_iTypeEvenementId ;
				$zSql1 = " DELETE FROM evenement 
				WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
				AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
				AND (evenement_iTypeEvenementId = ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . " OR evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_DISPONIBLE . " ) " ;

				$oCnx = jDb::getConnection();
				$oRes = $oCnx->exec($zSql);	
				$oRes = $oCnx->exec($zSql1);
				
				$oDaoFact->insert($oRecord) ;
			}
		}else{

			$iDureEnMinute = 0;
			$iDureEnMinuteTypeEvent = 0;
			if ($_oRecord->evenement_iDureeTypeId == 1){
				$iDureEnMinuteEvent = intval($_oRecord->evenement_iDuree) * 60 ;
			}else{
				$iDureEnMinuteEvent = intval($_oRecord->evenement_iDuree);
			}

			if ($oTypeEvenementSrv->typeevenements_iDureeTypeId == 1){
				$iDureEnMinuteTypeEvent = intval($oTypeEvenementSrv->typeevenements_iDure) * 60 ;
			}else{
				$iDureEnMinuteTypeEvent = intval($oTypeEvenementSrv->typeevenements_iDure) ;
			}

			$iEventParTypeEnvent = ceil($iDureEnMinuteEvent / $iDureEnMinuteTypeEvent);  
			$i=1;
			while ($i<=$iEventParTypeEnvent){
				$oDaoFact = jDao::get('commun~evenement') ;
				$oRecord = jDao::createRecord('commun~evenement') ;
				
				$oDureEvent = self::getNextDateHeureEvent ($_oRecord->evenement_zDateHeureDebut, $oTypeEvenementSrv->typeevenements_iDure, $oTypeEvenementSrv->typeevenements_iDureeTypeId, $i) ; 
				
				$oRecord->evenement_iTypeEvenementId	=	$_oRecord->evenement_iTypeEvenementId	;
				$oRecord->evenement_iUtilisateurId		=	$_oRecord->evenement_iUtilisateurId	;
				$oRecord->evenement_zLibelle			=	$_oRecord->evenement_zLibelle	;
				$oRecord->evenement_zDescription		=	$_oRecord->evenement_zDescription	;
				$oRecord->evenement_iStagiaire			=	$_oRecord->evenement_iStagiaire	;
				$oRecord->evenement_zContactTel			=	$_oRecord->evenement_zContactTel	;
				$oRecord->evenement_zDateHeureDebut		=	$oDureEvent->evenement_zDateHeureDebut	;
				$oRecord->evenement_zDateHeureSaisie	=	$_oRecord->evenement_zDateHeureSaisie	;
				$oRecord->evenement_iDuree				=	$oDureEvent->evenement_iDuree ;
				$oRecord->evenement_iDureeTypeId		=	$oDureEvent->evenement_iDureeTypeId	;
				$oRecord->evenement_iPriorite			=	$_oRecord->evenement_iPriorite	;
				$oRecord->evenement_iRappel				=	$_oRecord->evenement_iRappel	;
				$oRecord->evenement_iTypeRappelId		=	$_oRecord->evenement_iTypeRappelId	;
				$oRecord->evenement_iStatut				=	$_oRecord->evenement_iStatut	;
				$oRecord->evenement_origine				=	$_oRecord->evenement_origine	;
				$zdateFin = toolDate::getDateFin($oRecord) ;

				$zSql = "SELECT * FROM evenement WHERE evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId." "; 
				if ($zdateFin != ""&& $oRecord->evenement_zDateHeureDebut != $zdateFin){
					$zSql .= " AND evenement_zDateHeureDebut >= '".$oRecord->evenement_zDateHeureDebut."' AND evenement_zDateHeureDebut < '".$zdateFin."'" ;
				}else{
					$zSql .= " AND evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."' "; 
				}
				$zSql .= " GROUP BY evenement_id ORDER BY evenement_id" ;
					$oDBW = jDb::getDbWidget() ;
					$toEvenementBase = $oDBW->fetchAll($zSql); 

					if (sizeof($toEvenementBase) > 0){
						foreach ($toEvenementBase as $oEvenementBase){
							if ($oEvenementBase->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_DISPONIBLE || $oEvenementBase->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE){
								$zQuery = " DELETE FROM evenement WHERE evenement_id IN (".$oEvenementBase->evenement_id.")";
								$oCnx = jDb::getConnection();
								$oRes = $oCnx->exec($zQuery);

								$zSql = " DELETE FROM evenement 
								WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
								AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
								AND evenement_iTypeEvenementId = ".$oRecord->evenement_iTypeEvenementId ;
								$oCnx = jDb::getConnection();
								$oRes = $oCnx->exec($zSql);	
								$oDaoFact->insert($oRecord) ;
							}
						}
					}else{
						$zSql = " DELETE FROM evenement 
						WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
						AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
						AND evenement_iTypeEvenementId = ".$oRecord->evenement_iTypeEvenementId ;

						$zSql1 = " DELETE FROM evenement 
						WHERE evenement_zDateHeureDebut = '".$oRecord->evenement_zDateHeureDebut."'
						AND evenement_iUtilisateurId = ".$oRecord->evenement_iUtilisateurId."
						AND (evenement_iTypeEvenementId = ".ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . " OR evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_DISPONIBLE . " ) " ;

						$oCnx = jDb::getConnection();
						$oRes = $oCnx->exec($zSql);	
						$oRes = $oCnx->exec($zSql1);
						$oDaoFact->insert($oRecord) ;
					}
				$i++;
			}
		}
		return $tEventNonCreer ;
	}
}
?>