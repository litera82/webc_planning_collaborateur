<?php
/**
* @package     commun
* @subpackage  response
* @author      Neov
*/

require_once (LIB_PATH . 'json/JSON.php') ;


/**
* Json response pour les flash (ne renvoit jamais des et�te HTTP 500)
* @package  commun
* @subpackage response
* @see jResponse
*/

final class encodedJsonResponse extends jResponse {
	
	protected $_acceptSeveralErrors=false;

    /**
     * datas in PHP you want to send
     * @var mixed
     */
    public $datas = null ;

    public function output () {

        global $gJCoord ;
        global $gJConfig ; 

        if ($this->hasErrors ()) return false ;

		// Pb de caract�res accentu�s, on force � UTF-8 quelque soit la config
		$this->_httpHeaders['Content-Type'] = 'text/plain;charset=UTF-8' ;

		$oServiceJson = new Services_JSON (SERVICES_JSON_LOOSE_TYPE) ;

		$oContent = $oServiceJson->encode ($this->datas) ;
		if ($this->hasErrors ()) return false ;

		// Pb de caract�res accentu�s - on encode en UTF-8
//		$oContent = utf8_encode ($oContent) ;

		$this->sendHttpHeaders () ;

		echo $oContent ;

		return true ;

    }

	public function outputErrors () {

		global $gJCoord ;

		$tzMessages = array () ;
		if (count ($gJCoord->errorMessages)) {
			$oError = $gJCoord->errorMessages[0] ;
			$tzMessages['errorCode'] = $oError[1] ;
			$tzMessages['errorMessage'] = '[' . $oError[0] . '] ' . $oError[2] . ' (file: ' . $oError[3] . ', line: ' . $oError[4] . ')' ;
		}
		else {
			$tzMessages['errorMessage'] = 'Unknow error' ;
			$tzMessages['errorCode'] = -1 ;
		}

		$this->_httpHeaders['Content-Type'] = "text/plain" ;
		
		$oServiceJson = new Services_JSON (SERVICES_JSON_LOOSE_TYPE) ;
		$oContent = $oServiceJson->encode ($tzMessages) ;
		$this->_httpHeaders['Content-length'] = strlen ($oContent) ;
		$this->sendHttpHeaders () ;

		echo $oContent ;

	}

}

?>
