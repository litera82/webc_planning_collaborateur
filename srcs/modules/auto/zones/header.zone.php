<?php
/**
* @package
* @subpackage light
* @author
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

class headerZone extends jZone {
    protected $_tplname='header';

    
    protected function _prepareTpl(){
        $oCurrentUser = jAuth::getUserSession() ;
		$this->_tpl->assign('oCurrentUser', $oCurrentUser);
    }
}
?>
