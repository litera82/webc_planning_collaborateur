<?php
/**
* @package
* @subpackage light
* @author
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

jClasses::inc('auto~auto.evenementSrv') ;
jClasses::inc('commun~toolDate') ;

class contentZone extends jZone {
    protected $_tplname='content';

    
    protected function _prepareTpl(){
        $isDisponibility = $this->getParam('isDisponibility', 0) ;
        $iPart = $this->getParam('iPart', 0) ;
        $p = $this->getParam('p', 1) ;
        $m = $this->getParam('m', 0) ;
        
        $oCurrentUser = jAuth::getUserSession() ;
        $oDate = new jDateTime() ;
        $oDate->now();

		if ($iPart == 0){
	        $zDateDebut = $oDate->toString(jDateTime::DB_DFORMAT) ;
			$duration = new jDuration(array('day'=>15));
			$oDate->add($duration);
			$zDateFin = $oDate->toString(jDateTime::DB_DFORMAT); 
		}else{
	        $zCurrDate = $oDate->toString(jDateTime::DB_DFORMAT) ;
			$duration = new jDuration(array('day'=>15));
			$oDate->add($duration);
			$zDateDebut = $oDate->toString(jDateTime::DB_DFORMAT); 
			$duration1 = new jDuration(array('day'=>15));
			$oDate->add($duration1);
			$zDateFin = $oDate->toString(jDateTime::DB_DFORMAT); 
		}
		 
        // --- Voir si le stagiaire est déjà dans la base
        // --- Donc il a déjà réserver
        $oCriteria = new stdClass() ;
        $oCriteria->iStagiaire = $oCurrentUser->client_id ;
        //$oCriteria->evenement_iTypeEvenementId = ID_TYPE_EVENEMENT_TEST_DEBUT ;
        $toCriterias[0] = $oCriteria ;
        $toResults = autoEvemenementSrv::getEventAuto($toCriterias) ;
		$iIsModifiable = 1; 
		$bTestIsModifiable = false; 

		if ($toResults['iResTotal'] > 0)
        {
			$bTestIsModifiable = true;
            $this->_tplname = 'contentResa' ;
        }
        else
        {
			$toResults = autoEvemenementSrv::getUserTypeDisponibility(TYPE_UTILISATEUR_PROFESSEUR, ID_TYPE_EVENEMENT_DISPONIBLE, toolDate::getDateSuiv($zDateDebut), $zDateFin, $p) ;
			/*switch ($p){
				case 4: 
					// AUDIT_ID_FARAH ;
			        $toResults = autoEvemenementSrv::getUserTypeDisponibility(TYPE_UTILISATEUR_PROFESSEUR, ID_TYPE_EVENEMENT_AUDIT, toolDate::getDateSuiv($zDateDebut), $zDateFin, $p) ;					
					break;
				case 3: 
					//AUDIT_ID_CATRIONA ;
					$toResults = autoEvemenementSrv::getUserTypeDisponibility(TYPE_UTILISATEUR_PROFESSEUR, ID_TYPE_EVENEMENT_AUDIT, toolDate::getDateSuiv($zDateDebut), $zDateFin, $p) ;					
					break;
				case 2: 
					//AUTOPLANNIFICATION_ID_FARAH ;
					$toResults = autoEvemenementSrv::getUserTypeDisponibility(TYPE_UTILISATEUR_PROFESSEUR, ID_TYPE_EVENEMENT_DISPONIBLE, toolDate::getDateSuiv($zDateDebut), $zDateFin, $p) ;
					break;
				default:
					//AUTOPLANNIFICATION_ID_CATRIONA
					$toResults = autoEvemenementSrv::getUserTypeDisponibility(TYPE_UTILISATEUR_PROFESSEUR, ID_TYPE_EVENEMENT_DISPONIBLE, toolDate::getDateSuiv($zDateDebut), $zDateFin, $p) ;
					break;
			}*/
        }
        
        foreach ($toResults['toListes'] as $oListe)
        {
        	list($zYYYYmmdd, $zHHiiss) = explode(' ', $oListe->evenement_zDateHeureDebut) ; 
        	$tiHHiisss = explode(':', $zHHiiss) ;
        	$oListe->zDateString  = toolDate::formatToLongDate($zYYYYmmdd) ;
        	$oListe->zDateStringEn  = toolDate::formatToLongDate($zYYYYmmdd, '-', 'en') ;
			$oListe->zHeureString = $tiHHiisss[0] . 'H' . $tiHHiisss[1] ;
			$oListe->zHeureStringEn = $tiHHiisss[0] . ':' . $tiHHiisss[1] ;

			if ($bTestIsModifiable){
				$zDateJMois2 = toolDate::getIntervalDateByIntervalDay($zYYYYmmdd, 2); 
				$zCurrDate = date("Y-m-d");
				$tDate = toolDate::getDatesBetween($zDateJMois2, $zYYYYmmdd);
				$iJour = toolDate::dateDiffBySql($zYYYYmmdd, $zCurrDate);  
				if (in_array ($zCurrDate, $tDate) || $iJour <= 0) {
					$iIsModifiable = 0; 
				}
			}
        }
        
        $this->_tpl->assign('toEvents', $toResults['toListes']);
        $this->_tpl->assign('iIsModifiable', $iIsModifiable);
        $this->_tpl->assign('iNbEvent', $toResults['iResTotal']);
        $this->_tpl->assign('isDisponibility', $isDisponibility);
        $this->_tpl->assign('oCurrentUser', $oCurrentUser);
        $this->_tpl->assign('iPart', $iPart);
        $this->_tpl->assign('p', $p);
        $this->_tpl->assign('m', $m);
	}
}