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

class BoImportXmlStagiaireZone extends jZone {
    protected $_tplname='BoImportXmlStagiaire.zone';
	protected $_useCache	= false ;
    
    protected function _prepareTpl(){
		$x = $this->getParam ('x');
		$this->_tpl->assign('x', intval($x)) ;
    }
}