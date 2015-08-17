<?php
/**
* @package
* @subpackage light
* @author
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

jClasses::inc('evenement~evenementSrv') ;

class contentZone extends jZone {
    protected $_tplname='content';

    
    protected function _prepareTpl(){
        $isDisponibility = $this->getParam('isDisponibility', 0) ;
        
        $oCurrentUser = jAuth::getUserSession() ;
		$oCurrentSession = $_SESSION['JELIX_USER_LIGHT']; 

		if (isset($oCurrentUser->id) && isset($oCurrentSession->id) && $oCurrentUser->id == $oCurrentSession->id){
			$oDate = new jDateTime() ;
			$oDate->now();
			$oDate->sub(0, 1);
			$zDateDebut = $oDate->toString(jDateTime::DB_DFORMAT) ;
			$oDate->add(0000, 1);
			$zDateFin = $oDate->toString(jDateTime::DB_DFORMAT) ;
			$toResults = evenementSrv::getEventUserWithDisponibility($oCurrentUser->id, $zDateDebut, $zDateFin, $isDisponibility) ;
			
			$this->_tpl->assign('toEvents', $toResults['toListes']);
			$this->_tpl->assign('iNbEvent', $toResults['iResTotal']);
        }else{
			$this->_tpl->assign('toEvents', array());
			$this->_tpl->assign('iNbEvent', 0);
		}
		$this->_tpl->assign('isDisponibility', $isDisponibility);
    }
}