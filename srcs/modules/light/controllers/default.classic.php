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

	public $pluginParams = array (	'*'=>array ('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $isDisponibility = $this->intParam('d', 0) ;
        $oResp = $this->getResponse('light');
        $oResp->body->assignZone('content', 'light~content', array('isDisponibility'=>$isDisponibility)) ;
        return $oResp;
    }
    
    /**
    *
    */
    function liberer() {
        $iEventId = $this->intParam('id', 0) ;
        $oCurrentEvent = evenementSrv::getById($iEventId) ;
        $oCurrentUser = jAuth::getUserSession() ;
        $oResp = $this->getResponse('json');
        try{
        	$toEvents['evenement_id'] = $iEventId ;
        	$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_DISPONIBLE ;
        	$toEvents['evenement_iUtilisateurId'] = $oCurrentUser->id ;
        	$toEvents['evenement_zLibelle'] = 'Disponible' ;
        	$toEvents['evenement_zDescription'] = 'Disponible' ;
        	$toEvents['evenement_iStagiaire'] = NULL ;
        	$toEvents['evenement_zDateHeureDebut'] = $oCurrentEvent->evenement_zDateHeureDebut ;
        	$toEvents['evenement_iDuree'] = $oCurrentEvent->evenement_iDuree ;
        	$toEvents['evenement_iPriorite'] = 0 ;
        	$toEvents['evenement_iRappel'] = 0 ;
        	$toEvents['evenement_iStatut'] = STATUT_PUBLIE ;
          evenementSrv::save($toEvents) ; 
        }catch(exception $e){
            $e->message() ;
        }
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
}
?>
