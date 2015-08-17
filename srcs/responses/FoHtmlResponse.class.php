<?php
/**
* @package okboss
* @subpackage commun
* @version  1
* @author NEOV
*/

/**
 * Classe jResponseHtml
 */

require_once (JELIX_LIB_RESPONSE_PATH . 'jResponseHtml.class.php') ;

/**
* Classe Reponse commune surchargeant les r�ponses HTML standard
* et comprenant les traitement communs � toutes les pages du FO
*
* @package okboss
* @subpackage commun
*/
class FoHtmlResponse extends jResponseHtml
{
    public $title	= "Planning en ligne Format2+ - Agenda";

	//const MENU_ACCUEIL = 0;
	/**
	* Menu actif
	*/
	/*var $menuActif;*/
	var $addLink;	
	/**
	* Constructeur. On place ici les addJS/addCSS qui doivent �tre fait avant le traitement de l'action
	* @param array $attributes
	*/
	public function __construct ($_tAttributes = array ())
	{
        parent::__construct ($_tAttributes) ;
		global $gJConfig ;
		$this->addJSCode("var j_basepath = '" . $gJConfig->urlengine['basePath'] . "';");

		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.min.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.simpletooltip.js');
		//$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.tools.min.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/DD_roundies_0.0.2a.js');

		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/design.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/gradient.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.ui.datepicker-fr.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/commun/js/tmtValidator.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/horloge.js');

		$this->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/tmtValidator.css', array('media'=>'screen'));
		$this->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css', array('media'=>'screen'));
		$this->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css', array('media'=>'screen'));
		$this->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css', array('media'=>'screen'));
		$this->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/print-eventlisting.css', array("media"=>"print"));

		// Etat par défaut du code d'erreur
        $this->assignErrorCode () ;
        $this->assignErrorMsg () ;

    }

	/**
	* Traitements communs aux actions utilisant cette reponses
	* On place ici les addJS/addCSS qui peuvent être fait après le traitement de l'action
	*/
	protected function _commonProcess ()
	{
		global $gJConfig ;
		$this->addJSCode("var j_basepath = '" . $gJConfig->urlengine['basePath'] . "';");
		if (isset ($this->selectedDate)){
			$tDate = explode ('-',$this->selectedDate[0]); 
			$iAffichage = $this->selectedDate[1];
			$this->body->assignZone('header', 'commun~FoHeader', array('tDate'=>$tDate, 'iAffichage'=>$iAffichage));
		}else{
		$this->body->assignZone('header', 'commun~FoHeader', array());
		} 
		$this->body->assignZone('footer', 'commun~FoFooter', array());
    }

   /**
    * Assgnation d'un code d'erreur
    *
    * @param int $_iErrorCode Le code d'erreur
    */
    public function assignErrorCode ($_iErrorCode = 0)
    {
        $this->body->assign ("iErrorCode", $_iErrorCode) ;
    }

   /**
    * Assgnation d'un code d'erreur
    *
    * @param int $_iErrorCode Le code d'erreur
    */
    public function assignErrorMsg ($_zErrorMsg = "")
    {
        $this->body->assign ("zErrorMsg", $_zErrorMsg) ;
    }

}
?>