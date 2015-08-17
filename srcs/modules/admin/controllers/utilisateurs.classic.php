<?php
/**
* @package   jelix_calendar
* @subpackage admin
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class utilisateursCtrl extends jController {
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_UTILISATEURS, BoHtmlResponse::MENU_UTILISATEURS_LISTE) ;
        $oResp->body->assignZone('zContent', 'utilisateurs~BoUtilisateursListe') ;
        return $oResp ;
    }
	function edit() {
		global $gJConfig ;
		$toParams = $this->params() ;
        $oResp = $this->getResponse('BoHtml') ;
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/jquery-1.9.1.min.js');
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/jquery.multiselects.js');
		$oResp->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/fullcalendar.css');
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/jquery-ui-1.10.2.custom.min.js');
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/fullcalendar/fullcalendar.js');

		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_UTILISATEURS, BoHtmlResponse::MENU_UTILISATEURS_LISTE) ;
        $oResp->body->assignZone('zContent', 'utilisateurs~BoUtilisateursEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ; 
        jClasses::inc('utilisateurs~utilisateursSrv');
        jClasses::inc('utilisateurs~utilisateursIndisponibiliteSrv');
        jClasses::inc('utilisateurs~utilisateursdisponibiliteSrv');

		$oUtilisateurs = utilisateursSrv::save($toParams) ;
		if ((isset($toParams['dispo']) && $toParams['dispo'] != '') || (isset($toParams['indispo']) && $toParams['indispo'] != '')){
			utilisateursdisponibiliteSrv::deleteDisponibiliteUtilisateur($oUtilisateurs->utilisateur_id) ;
			//[dispo] => 1 - 07:00 - 12:00 > 1 - 14:00 - 18:00 > 2 - 09:00 - 13:20 > 2 - 15:20 - 18:20 > 
			//[indispo] => 1 - 12:00 - 14:00 > 2 - 07:00 - 09:00 > 2 - 13:20 - 15:20 > 
			if (isset($toParams['dispo']) && $toParams['dispo'] != ''){
				$tDispo = explode (" > ", $toParams['dispo']);
				unset($tDispo[sizeof($tDispo)-1]);
				foreach($tDispo as $oDispo){
					$tD = array () ;
					$toDispo = explode(' - ', $oDispo); 
					$tD['utilisateursdisponibilite_utilisateur'] = $oUtilisateurs->utilisateur_id ;
					$tD['utilisateursdisponibilite_jour'] = $toDispo[0] ;
					$tD['utilisateursdisponibilite_type'] = ID_TYPE_EVENEMENT_DISPONIBLE ;
					$tD['utilisateursdisponibilite_debut'] = $toDispo[1].':00' ;
					$tD['utilisateursdisponibilite_fin'] = $toDispo[2].':00' ;
					utilisateursdisponibiliteSrv::save ($tD);
				}
			}
			if (isset($toParams['indispo']) && $toParams['indispo'] != ''){
				$tIndispo = explode (" > ", $toParams['indispo']);
				unset($tIndispo[sizeof($tIndispo)-1]);
				foreach($tIndispo as $oIndispo){
					$tI = array () ;
					$toIndispo = explode(' - ', $oIndispo); 
					$tI['utilisateursdisponibilite_utilisateur'] = $oUtilisateurs->utilisateur_id ;
					$tI['utilisateursdisponibilite_jour'] = $toIndispo[0] ;
					$tI['utilisateursdisponibilite_type'] = ID_TYPE_EVENEMENT_INDISPONIBLE ;
					$tI['utilisateursdisponibilite_debut'] = $toIndispo[1].':00' ;
					$tI['utilisateursdisponibilite_fin'] = $toIndispo[2].':00' ;
					utilisateursdisponibiliteSrv::save ($tI);
				}
			}

		}

		$oResp = $this->getResponse('redirect') ;
        $oResp->action = 'admin~utilisateurs:index' ;
        return $oResp ;
    }
	function delete() {
        jClasses::inc('utilisateurs~utilisateursSrv');
        utilisateursSrv::delete($this->intParam('iUtilisateurId', 0, true)) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'admin~utilisateurs:index' ;
        return $oResp ;
    }

	function getJsonEvents(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iUtilisateurId = $this->param('iUtilisateurId', "", true);

		jClasses::inc('utilisateurs~utilisateursdisponibiliteSrv');
		jClasses::inc('commun~toolDate') ;

		$toUtilisateursDisponibilite = array();
		$zEvents = '' ;

		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?7:$num_day)+1,$year);
		$zDatedeb      = toolDate::toDateSQL(date('d-m-Y', $premier_jour)); 

		$tDateListe = toolDate::getListeDateSemaineSansWE($zDatedeb); 
		if ($iUtilisateurId > 0){
			$toParams['utilisateursdisponibilite_utilisateur'] = $iUtilisateurId ;
			$toUtilisateursDisponibilite = utilisateursdisponibiliteSrv::listCriteria($toParams);
			if (isset($toUtilisateursDisponibilite['toListes']) && sizeof($toUtilisateursDisponibilite['toListes']) > 0){
				foreach ($toUtilisateursDisponibilite['toListes'] as $oUtilisateursDisponibilite){
					switch($oUtilisateursDisponibilite->utilisateursdisponibilite_jour){
						case 1: 
							$oUtilisateursDisponibilite->utilisateursdisponibilite_date = $tDateListe[0];
							break; //Lundi
						case 2: 
							$oUtilisateursDisponibilite->utilisateursdisponibilite_date = $tDateListe[1];
							break; //Mardi
						case 3: 
							$oUtilisateursDisponibilite->utilisateursdisponibilite_date = $tDateListe[2];
							break; //Mercredi
						case 4: 
							$oUtilisateursDisponibilite->utilisateursdisponibilite_date = $tDateListe[3];
							break; //Jeudi
						default: //Vendredi
							$oUtilisateursDisponibilite->utilisateursdisponibilite_date = $tDateListe[4];
					} 
				}
				foreach ($toUtilisateursDisponibilite['toListes'] as $oUtilisateursDisponibilite){
					$zTitle = "Indisponible" ; 
					if ($oUtilisateursDisponibilite->utilisateursdisponibilite_type == ID_TYPE_EVENEMENT_DISPONIBLE){
						$zTitle = "Disponible" ;
					}
					list($year, $month, $day) = explode('-', $oUtilisateursDisponibilite->utilisateursdisponibilite_date); 
					$finalM = intval($month)-1;
					list($hourD, $minD, $secD) = explode(':', $oUtilisateursDisponibilite->utilisateursdisponibilite_debut); 
					list($hourF, $minF, $secF) = explode(':', $oUtilisateursDisponibilite->utilisateursdisponibilite_fin); 
					if ($zEvents != ''){
						$zEvents .= ',';
					}
					$zEvents .= '{"title":"'.$zTitle.'", "start": new Date('.$year.','.$finalM.','.$day.','.$hourD.','.$minD.','.$secD.'), "end": new Date('.$year.','.$finalM.','.$day.','.$hourF.','.$minF.','.$secF.'), "allDay":false}';
				}
			}
		}
		$oRep->datas = $zEvents;
		return $oRep;
	}
}