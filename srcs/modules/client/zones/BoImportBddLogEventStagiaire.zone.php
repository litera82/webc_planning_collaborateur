<?php
/**
* @package
* @subpackage light
* @author
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

class BoImportBddLogEventStagiaireZone extends jZone {
    protected $_tplname='BoImportBddLogEventStagiaire.zone';
	protected $_useCache	= false ;
    
    protected function _prepareTpl(){
		$x = $this->getParam ('x');
		$this->_tpl->assign('x', intval($x)) ;
    }
}