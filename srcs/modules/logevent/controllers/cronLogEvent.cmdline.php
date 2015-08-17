<?php
/**
* @package concours
* @subpackage projet
* @version  1
* @author NEOV
*/

/**
* Controleur pour les taches CRON
* @package concours
* @subpackage projet
*/
@ini_set ("memory_limit", "-1") ;
@set_time_limit(3600);

class cronLogEventCtrl extends jControllerCmdLine {
    public $pluginParams = array(
        '*'=>array('auth.required'=>false)
    );
    
    /**
    * Exportation donnée evenements 
	* /kunden/homepages/41/d371880585/htdocs/webcalendar/srcs/scripts/cmdline.php logevent~cronLogEvent:cronLogEvent
	*/
	function cronLogEvent() {
		global $gJCoord;
		global $gJConfig;
		$oRep = $this->getResponse('cmdline');
        jClasses::inc('logevent~logeventSrv');
        logeventSrv::logevent() ;
		return $oRep;
	}
	/**
	*
	* /kunden/homepages/41/d371880585/htdocs/webcalendar/srcs/scripts/cmdline.php logevent~cronLogEvent:cronPortefeuilleprof
	*/
	function cronPortefeuilleprof() {
		global $gJCoord;
		global $gJConfig;
		$oRep = $this->getResponse('cmdline');
		jClasses::inc('client~clientXmlSaveSrv');
		$iRet = clientXmlSaveSrv::saveClientDepuisBddLogevent();
		return $oRep;
	}
}
?>