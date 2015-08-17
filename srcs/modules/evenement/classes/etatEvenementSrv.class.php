<?php
@ini_set ("memory_limit", -1) ;

class etatEvenementSrv {
	static function save ($oEtatEvenement){
		$oDaoFact = jDao::get('commun~etatevenement') ;
        $oRecord = jDao::createRecord('commun~etatevenement') ;

		$oRecord->etat_iEvenementId = $oEtatEvenement->etat_iEvenementId;
		$oRecord->etat_iTypeEtatId = $oEtatEvenement->etat_iTypeEtatId ;
		$oRecord->etat_zCommentaire = $oEtatEvenement->etat_zCommentaire ;
		$oRecord->etat_zDateSaisie = $oEtatEvenement->etat_zCommentaire ;

		$oDaoFact->insert($oRecord);
		return $oRecord->etat_id ;
	}
}
?>