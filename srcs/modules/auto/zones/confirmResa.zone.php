<?php
/**
* @package
* @subpackage light
* @author
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

//jClasses::inc('auto~auto.evenementSrv') ;

jClasses::inc('auto~auto.evenementSrv') ;
jClasses::inc('utilisateurs~utilisateursSrv') ;
jClasses::inc('commun~toolDate') ;
class confirmResaZone extends jZone {
    protected $_tplname='confirmResa';

    
    protected function _prepareTpl(){
        $iEventId = $this->getParam('id', 0) ;
        
        $oCurrentUser = jAuth::getUserSession() ;
        $oEvent = autoEvemenementSrv::getById($iEventId) ;
        $oTesteur = utilisateursSrv::getById($oEvent->evenement_iUtilisateurId) ;
        $oEvent->utilisateur_iCivilite	= $oTesteur->utilisateur_iCivilite ;
        $oEvent->utilisateur_zPrenom		= $oTesteur->utilisateur_zPrenom ;
        $oEvent->utilisateur_zNom				= $oTesteur->utilisateur_zNom ;
        list($zYYYYmmdd, $zHHiiss) = explode(' ', $oEvent->evenement_zDateHeureDebut) ; 
        $tiHHiisss = explode(':', $zHHiiss) ;
        $oEvent->zDateString  = ToolDate::formatToLongDate($zYYYYmmdd) ;
        $oEvent->zHeureString = $tiHHiisss[0] . 'H' . $tiHHiisss[1] ;
        
        $this->_tpl->assign('oEvent', $oEvent);
        $this->_tpl->assign('oCurrentUser', $oCurrentUser);
        // -- 
    }
}