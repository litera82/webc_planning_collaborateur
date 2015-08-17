<?php
/**
* @package
* @subpackage 
* @author
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/
jClasses::inc('evenement~evenementSrv') ;
class defaultCtrl extends jController {

	public $pluginParams = array (
									'index'=>array ('auth.required'=>true),
									'reserver'=>array ('auth.required'=>true),
									'suprimer'=>array ('auth.required'=>true)
								 ) ;
    /**
    *
    */
    function index() {
		global $gJConfig ;

        $isDisponibility = $this->intParam('d', 0) ;
        $iPart = $this->intParam('iPart', 0) ;
        $p = $this->intParam('p', 1) ;
        $m = $this->intParam('m', 0) ;

		$oResp = $this->getResponse('auto');
		
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/timepicker.js');
		$oResp->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.ui.datepicker-fr.js');

        $oResp->body->assignZone('content', 'auto~content', array('isDisponibility'=>$isDisponibility, 'iPart'=>$iPart, 'p'=>$p, 'm'=>$m)) ;
        return $oResp;
    }

	function stagiaire_() {
		$conf = $GLOBALS['gJCoord']->getPlugin('auth')->config;

		$zCryptedKey = $this->param('l', '') ;
		$p = $this->intParam('p', 1) ;
		
		if ($zCryptedKey == ""){				
			sleep (intval($conf['on_error_sleep']));
            $url_return = jUrl::get($conf['after_logout'],array ('login'=>$this->param('login'), 'failed'=>1));
		}else{
			jClasses::inc('client~clientSrv');
			$oClient = clientSrv::getByCryptedKey ($zCryptedKey);
			if (is_object($oClient) && isset($oClient->client_zLogin) && isset($oClient->client_zPass)){
				$url_return = '/';

				if ($conf['after_login'] == '')
					throw new jException ('jauth~autherror.no.auth_login');

				if ($conf['after_logout'] == '')
					throw new jException ('jauth~autherror.no.auth_logout');

				if (!($conf['enable_after_login_override'] && $url_return= $this->param('auth_url_return'))){
					$url_return =  jUrl::get($conf['after_login'], array('p'=>$p));
				}
				if (!jAuth::login($oClient->client_zLogin, $oClient->client_zPass, 0)){
					sleep (intval($conf['on_error_sleep']));
					$url_return = jUrl::get($conf['after_logout'],array ('login'=>$this->param('login'), 'failed'=>1));
				}
			}else{
				sleep (intval($conf['on_error_sleep']));
				$url_return = jUrl::get($conf['after_logout'],array ('login'=>$this->param('login'), 'failed'=>1));
			}
		}
        $rep = $this->getResponse('redirectUrl');
        $rep->url = $url_return;

        return $rep;
	}

	function stagiaire() {
		$conf = $GLOBALS['gJCoord']->getPlugin('auth')->config;

		$zLogin = $this->param('x', '') ;
		$zPwd = $this->param('y', '') ;
		// p=1 test de debut de stage catriona
		// p=2 test de debut de stage farah
		// p=3 audit catriona
		// p=4 audit farah
		// p=5 ALO
		$p = $this->intParam('p', 1) ;
		
		if ($zLogin == "" && $zPwd == ""){				
			sleep (intval($conf['on_error_sleep']));
            $url_return = jUrl::get($conf['after_logout'],array ('login'=>$this->param('login'), 'failed'=>1));
		}else{
			jClasses::inc('client~clientSrv');
			$oClient = clientSrv::getClientByLoginPassword ($zLogin, $zPwd);
			if (is_object($oClient) && isset($oClient->client_zLogin) && isset($oClient->client_zPass)){
				$url_return = '/';

				if ($conf['after_login'] == '')
					throw new jException ('jauth~autherror.no.auth_login');

				if ($conf['after_logout'] == '')
					throw new jException ('jauth~autherror.no.auth_logout');

				if (!($conf['enable_after_login_override'] && $url_return= $this->param('auth_url_return'))){
					$url_return =  jUrl::get($conf['after_login'], array('p'=>$p));
				}
				if (!jAuth::login($oClient->client_zLogin, $oClient->client_zPass, 0)){
					sleep (intval($conf['on_error_sleep']));
					$url_return = jUrl::get($conf['after_logout'],array ('login'=>$this->param('login'), 'failed'=>1));
				}
			}else{
				sleep (intval($conf['on_error_sleep']));
				$url_return = jUrl::get($conf['after_logout'],array ('login'=>$this->param('login'), 'failed'=>1));
			}
		}
        $rep = $this->getResponse('redirectUrl');
        $rep->url = $url_return;

        return $rep;
	}

    /**
    *
    */
    function reserver() {
        $oResp = $this->getResponse('json');        

		jClasses::inc('client~clientSrv');
		jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('auto~importStagiaire');
        jClasses::inc('commun~toolDate');

		$iEventId = $this->intParam('id', 0) ;
        $iTelResa = $this->param('telTest', 0) ;
        $m = $this->param('m', 0) ;
        $p = $this->param('p', 1) ;

		$oCurrentEvent = evenementSrv::getById($iEventId) ;
        $oCurrentUser = jAuth::getUserSession() ;
		$zDateMysql = $oCurrentEvent->evenement_zDateHeureDebut ;
jLog::dump(var_dump($p)) ;
		$toEvents['evenement_id'] = $iEventId ;
		$toEvents['evenement_zContactTel'] = $iTelResa ;
		switch ($p){
			case 5:
				jLog::dump('5555555555') ;
				$oAlo = typeEvenementsSrv::getById(ID_TYPE_EVENEMENT_ALO);
jLog::dump($oAlo) ;			
				// ALO
				$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_ALO ;
				$toEvents['evenement_zLibelle'] = $oAlo->typeevenements_zLibelle ;
				$toEvents['evenement_zDescription'] = $oAlo->typeevenements_zLibelle ;
			case 4: 
				// AUDIT_ID_FARAH ;
				jLog::dump('444444mmmmmmmmmmmmmmmmm444444') ;
				$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_AUDIT ;
				$toEvents['evenement_zLibelle'] = 'Audit Farah' ;
				$toEvents['evenement_zDescription'] = 'Audit Farah' ;
				break;
			case 3: 
				//AUDIT_ID_CATRIONA ;
				$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_AUDIT ;
				$toEvents['evenement_zLibelle'] = 'Audit Catriona' ;
				$toEvents['evenement_zDescription'] = 'Audit Catriona' ;
				break;
			case 2: 
				//AUTOPLANNIFICATION_ID_FARAH ;
				$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_TEST_DEBUT ;
				$toEvents['evenement_zLibelle'] = 'Test de début Farah' ;
				$toEvents['evenement_zDescription'] = 'Test de début Farah' ;
				break;
			default:
				//AUTOPLANNIFICATION_ID_CATRIONA
				$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_TEST_DEBUT ;
				$toEvents['evenement_zLibelle'] = 'Test de début Catriona' ;
				$toEvents['evenement_zDescription'] = 'Test de début Catriona' ;
				break;
		}

		$toEvents['evenement_iUtilisateurId'] = $oCurrentEvent->evenement_iUtilisateurId ;
		$toEvents['evenement_iStagiaire'] = $oCurrentUser->client_id ;
		$zDateEn = $oCurrentEvent->evenement_zDateHeureDebut ;
		$tzDateHeure = explode(" ", $oCurrentEvent->evenement_zDateHeureDebut);
		$tzDate = explode("-", $tzDateHeure[0]);
		$oCurrentEvent->evenement_zDateHeureDebut = $tzDate[2] . "/" . $tzDate[1] . "/" . $tzDate[0] . " " . $tzDateHeure[1];
		$toEvents['evenement_zDateHeureDebut'] = $oCurrentEvent->evenement_zDateHeureDebut ;
		$toEvents['evenement_iDuree'] = $oCurrentEvent->evenement_iDuree ;
		$toEvents['evenement_iPriorite'] = 0 ;
		$toEvents['evenement_iRappel'] = 0 ;
		$toEvents['evenement_iStatut'] = STATUT_PUBLIE ;
		$toEvents['sendMail']=0;
		$toEvents['evenement_origine']=1;
		$toEvents['zDateMysql'] = $zDateMysql; 
jLog::dump($toEvents) ;	
		//evenementSrv::save($toEvents) ;
		if ($p == 5){
			jLog::dump('Prévoir une page auto-planification pour les ALO, sélection de 2 créneaux "disponible test" consécutifs – CODE A1(P=5)') ;
			// Prévoir une page auto-planification pour les ALO, sélection de 2 créneaux “disponible test” consécutifs – CODE A1(P=5)
			$oAlo = typeEvenementsSrv::getById(ID_TYPE_EVENEMENT_ALO);
jLog::dump($oAlo) ;			
			$toEvents['evenement_zDateHeureDebut'] = toolDate::getDateAddYYYYmmddhis($zDateEn, $oAlo->typeevenements_iDure, $oAlo->typeevenements_iDureeTypeId);
			$oActualEvent = evenementSrv::getEventByHeurDebut($toEvents['evenement_zDateHeureDebut']);
			if ($oActualEvent->evenement_id > 0 && $oActualEvent->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_DISPONIBLE){
				//evenementSrv::delete($oActualEvent->evenement_id);				
			}
jLog::dump($toEvents) ;	
			//evenementSrv::save($toEvents) ;
			$toEvents['evenement_zDateHeureDebut'] = $oCurrentEvent->evenement_zDateHeureDebut ;
		}else{
			jLog::dump('NE PAS PRÉVOIR') ;
		}
		//Stagiaire => Ajouter un champs permettant de savoir s'il a deja reservé une plage pour le test de debut ou pas
		importStagiaire::majReservationClient($toEvents['evenement_iStagiaire'], 1);
die() ;
		//Envoi de mail 
		try{
			jClasses::inc('logevent~logeventSrv');
			logeventSrv::logeventAfterAutoplannification($iEventId); 

			importStagiaire::sendMailReservation ($toEvents, $p, $m);
			importStagiaire::sendMailPourInformerReservation ($toEvents, $p, $m);
		}catch (Exception $e){
			$e->getMessage();
		}
        return $oResp;
    }
    
    /**
    *
    */
    function liberer() {
        $iEventId = $this->intParam('id', 0) ;
        $m = $this->intParam('m', 1) ;
        $p = $this->intParam('p', 1) ;

		$oCurrentEvent = evenementSrv::getById($iEventId) ;
        $oCurrentUser = jAuth::getUserSession() ;
		jClasses::inc('auto~importStagiaire');
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

        if ($iEventId)
        {
            $toEvents['evenement_id'] = $iEventId ;
        	$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_DISPONIBLE ;
        	$toEvents['evenement_iUtilisateurId'] = $oCurrentEvent->evenement_iUtilisateurId ;
        	$toEvents['evenement_zLibelle'] = '' ;
        	$toEvents['evenement_zDescription'] = '' ;
        	$toEvents['evenement_iStagiaire'] = 0 ;
            $tzDateHeure = explode(" ", $oCurrentEvent->evenement_zDateHeureDebut);
            $tzDate = explode("-", $tzDateHeure[0]);
            $oCurrentEvent->evenement_zDateHeureDebut = $tzDate[2] . "/" . $tzDate[1] . "/" . $tzDate[0] . " " . $tzDateHeure[1];
        	$toEvents['evenement_zDateHeureDebut'] = $oCurrentEvent->evenement_zDateHeureDebut ;
        	$toEvents['evenement_iDuree'] = $oCurrentEvent->evenement_iDuree ;
        	$toEvents['evenement_iPriorite'] = 0 ;
        	$toEvents['evenement_iRappel'] = 0 ;
        	$toEvents['evenement_iStatut'] = STATUT_PUBLIE ;
        	$toEvents['evenement_origine'] = 2 ;
            evenementSrv::save($toEvents) ;

			if ($p == 5){
				$oAlo = typeEvenementsSrv::getById(ID_TYPE_EVENEMENT_ALO);
				$newH = toolDate::getDateAddYYYYmmddhis($oCurrentEvent->evenement_zDateHeureDebut, $oAlo->typeevenements_iDure, $oAlo->typeevenements_iDureeTypeId);
				$oActualEvent = evenementSrv::getEventByHeurDebut($toEvents['evenement_zDateHeureDebut']);
				if ($oActualEvent->evenement_id > 0 && $oActualEvent->evenement_iTypeEvenementId == ID_TYPE_EVENEMENT_ALO){
					$iNewEventsId = $oActualEvent->evenement_id ;
					evenementSrv::delete($oActualEvent->evenement_id);				

					$toNewEvents['evenement_id'] = $iNewEventsId ;
					$toNewEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_DISPONIBLE ;
					$toNewEvents['evenement_iUtilisateurId'] = $oCurrentEvent->evenement_iUtilisateurId ;
					$toNewEvents['evenement_zLibelle'] = '' ;
					$toNewEvents['evenement_zDescription'] = '' ;
					$toNewEvents['evenement_iStagiaire'] = 0 ;
					$toNewEvents['evenement_zDateHeureDebut'] = $newH ;
					$toNewEvents['evenement_iDuree'] = $toEvents['evenement_iDuree'] ;
					$toNewEvents['evenement_iPriorite'] = 0 ;
					$toNewEvents['evenement_iRappel'] = 0 ;
					$toNewEvents['evenement_iStatut'] = STATUT_PUBLIE ;
					$toNewEvents['evenement_origine'] = 2 ;

		            evenementSrv::save($toNewEvents) ;
				}
			}
		}
		if ($oCurrentUser->client_id > 0){
			importStagiaire::majReservationClient($oCurrentUser->client_id, 0);
		}

        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'auto~default:index' ;
		$oResp->params = array ('m' => $m, 'p' => $p);

		return $oResp;
    }

	function changeTelTest() {
        $oResp = $this->getResponse('redirect') ;

		$iEventId = $this->intParam('newTelResEventId', 0) ;
        $newTelRes = $this->param('newTelRes', 0) ;
        if ($iEventId > 0)
        {
            $toEvents['evenement_id'] = $iEventId ;
        	$toEvents['evenement_zContactTel'] = $newTelRes ;
            evenementSrv::save($toEvents) ;
        }
        
        $oResp->action = 'auto~default:index' ;
        return $oResp;
    }
    
    /**
    *
    */
    function suprimer() {
        $iEventId = $this->intParam('id', 0) ;
        $oResp = $this->getResponse('text');
        try{
           evenementSrv::delete($iEventId) ; 
        }catch(exception $e){
            $e->message() ;
        }
        return $oResp;
    }

	function traitementChoix (){
        $oResp = $this->getResponse('json');        
		
		$toParams = $this->params() ;

		jClasses::inc('auto~auto.evenementSrv') ;

        $oCurrentUser = jAuth::getUserSession() ;

		//Envoi de mail 
		try{
			autoEvemenementSrv::sendMailReservationChoix ($toParams, $oCurrentUser);
		}catch (Exception $e){
			$e->getMessage();
		}
		return $oResp; 
	}
}
?>