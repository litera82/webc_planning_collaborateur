<?php
/**
* @package
* @subpackage 
* @author
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/
jClasses::inc('auto~importStagiaire') ;
jClasses::inc('client~clientSrv') ;
define ('PATH_XML_STAGIAIRE', 'userFiles/xml/autoplanification/stagiaire/');
class importCtrl extends jController {
    /**
    *
    */
	function importXmlStagiaire(){
        $oResp = $this->getResponse('BoHtml') ;
        $x = $this->param('x', 0) ;
		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_IMPORT, BoHtmlResponse::MENU_IMPORT_XML_STAGIAIRE) ;
        $oResp->body->assignZone('zContent', 'auto~BoImportXmlStagiaire', array('x'=>$x)) ;
		return $oResp ;
	}

	function importerXmlStagiaire(){
        $oResp = $this->getResponse('redirect') ;
		if (isset($_FILES['xmlstagiaire']["name"])) {
			if (file_exists($_FILES['xmlstagiaire']["tmp_name"]) && is_file ($_FILES['xmlstagiaire']["tmp_name"])) {
				$zFileDest = 'stagiaire_'.date('d_m_Y_H_i_s').'.xml';
				if (!move_uploaded_file($_FILES['xmlstagiaire']["tmp_name"], PATH_XML_STAGIAIRE . $zFileDest)){
					$oResp->action = 'auto~import:importXmlStagiaire';
					$oResp->params = array('x'=>1001); //Erreur transfert fichier
				}else{
					chmod(PATH_XML_STAGIAIRE . $zFileDest, 0777);
					@unlink($_FILES['xmlstagiaire']["tmp_name"]);
					$x=importStagiaire::parseXml(PATH_XML_STAGIAIRE . $zFileDest); 
					$oResp->action = 'auto~import:importXmlStagiaire';
					$oResp->params = array('x'=>$x); //Succes import
				}
		   }else{
		        $oResp->action = 'auto~import:importXmlStagiaire';
				$oResp->params = array('x'=>1001); //Erreur transfert fichier
		   }
		}else{
			$oResp->action = 'auto~import:importXmlStagiaire';
			$oResp->params = array('x'=>1002); // Erreur upload fichier
		}
		return $oResp ;
	}

    /**
    *
    */
    function index() {
    	//si on upload un fichier xml
        if (isset($_FILES['PostedXMLStagiaire']["name"]))
        	$zPostedXMLStagiaire = file_get_contents(($_FILES['PostedXMLStagiaire']['tmp_name']));
        else //ou si on passe un string xml
        	$zPostedXMLStagiaire = stripslashes($this->param('PostedXMLStagiaire', '')) ;
        	
        $oResp = $this->getResponse('text') ;
        $oResp->content = importStagiaire::importXml($zPostedXMLStagiaire) ;
        return $oResp;
    }

    /**
    *
    */
    function loginDirect() {
        $zCryptedKey = $this->param('k', '') ;
        $oClient = clientSrv::getByCryptedKey($zCryptedKey) ;
        
        if (isset($oClient->client_zLogin))
        {
            jAuth::login($oClient->client_zLogin, $oClient->client_zPass) ;
        }
        
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = "auto~default:index" ;
        return $oResp;
    }
}