<?php
/**
* @package   jelix_calendar
* @subpackage evenement
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class logeventCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        jClasses::inc('logevent~logeventSrv');
        $bSave = logeventSrv::logevent() ;
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_LOGEVENT, BoHtmlResponse::MENU_LOGEVENT_DEFAULT) ;
		$oResp->body->assignZone('zContent', 'logevent~confirmationlogEvent', array('bSave'=>$bSave)) ;
        return $oResp ;
    }

	function logEventWithParam (){
        $oResp = $this->getResponse('BoHtml') ;
		$bSave = $this->param('bSave', 0, true);
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_LOGEVENT, BoHtmlResponse::MENU_LOGEVENT_ALL) ;
		$oResp->body->assignZone('zContent', 'logevent~BoLogEventWithParamPage', array('bSave'=>$bSave)) ;

		return $oResp ;
	}

	function logEvent() {
        jClasses::inc('logevent~logeventSrv');
		echo $debut = $this->param('zDateDebut', null, true);
		$fin = $this->param('zDateFin', null, true);
		$table = $this->param('t', 1, true);
        $bSave = logeventSrv::logevent($debut, $fin, $table) ;
        $oResp = $this->getResponse('redirect') ;
		$oResp->action = 'logevent~logevent:logEventWithParam' ;
		$oResp->params = array ('bSave'=>$bSave);	
        return $oResp ;
    }

    /*function confirmationlogEvent() {
		$bSave = $this->params() ;
		$oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_LOGEVENT) ;
		$oResp->body->assignZone('zContent', 'logevent~confirmationlogEvent', array('bSave'=>$bSave)) ;
        return $oResp ;
    }*/

}

?>