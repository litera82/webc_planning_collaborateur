<?php
/**
* @package postali
* @subpackage responses
* @version  1
* @author Toky RABESON <t.rabeson@gmail.com>
*/


require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php') ;

class BoHtmlResponse extends jResponseHtml
{
    public $bodyTpl = 'commun~BoPageLayout' ;
    public $title	= "Planning en ligne Format2+ - Console d'administration";
    
    const MENU_ADMINISTRATEURS			= 0;  
	const MENU_TYPE_UTILISATEURS		= 1;

	const MENU_UTILISATEURS				= 2;
	const MENU_UTILISATEURS_LISTE		= 0;
	const MENU_DISPONIBILITE			= 1;
	const MENU_PLANINGPROFPAREMAIL		= 2;

	const MENU_TYPEEVENEMENT			= 3;
	const MENU_CLIENT					= 4;
	const MENU_CLIENT_LISTE				= 0;
	const MENU_CLIENT_IMPORT			= 1;
	const MENU_CLIENT_BDD				= 2;
	const MENU_EVENEMENT				= 5;
	const MENU_IMPORT					= 6;
	const MENU_IMPORT_XML_STAGIAIRE		= 0;
	const MENU_LOGEVENT					= 7;
	const MENU_LOGEVENT_DEFAULT			= 0;
	const MENU_LOGEVENT_ALL				= 1;
	const MENU_POSTS					= 8;
	const MENU_POSTS_LISTE				= 0;
	const MENU_POSTS_GENERER			= 1;
	const MENU_POSTS_DEDOUBLONNER		= 2;

	/**
	* Menu actif
	*/
	var $tiMenusActifs ;

    /**
	* Constructeur. On place ici les addJS/addCSS qui doivent être fait avant le traitement de l'action
	*/
	function __construct()
    {
        parent::__construct() ;

        // Include your common CSS and JS files here
        global $gJConfig ;  
		$this->addJSCode("var j_basepath = '" . $gJConfig->urlengine['basePath'] . "';");

    	$this->addCSSLink($gJConfig->urlengine['basePath'] . 'design/back/css/common.css');
		$this->addCSSLink($gJConfig->urlengine['basePath'] . 'design/back/css/tmtValidator.css');
       	$this->addCSSLink($gJConfig->urlengine['basePath'] . 'design/back/css/flexigrid/flexigrid.css');
		$this->addCSSLink($gJConfig->urlengine['basePath'] . 'design/back/css/calendar-aiw.css');

		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/commun/js/jquery.js');
		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/commun/js/jquery.tablesorter.js');
		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/commun/js/sortableListWithPagination.js');
		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/back/js/flexigrid.js');


		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/commun/js/tmtValidator.js');
		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/back/js/design_bo.js');
		$this->addJSLink($gJConfig->urlengine['basePath'] . 'FCKeditor/fckeditor.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/back/js/ajaxupload.js') ;
		
        $this->addJSLink($gJConfig->urlengine['basePath'] . 'design/back/js/flexigrid.js');
		//$this->addJSLink ($gJConfig->urlengine['basePath'] . 'ckfinder/ckfinder.js') ;

		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/commun/js/calendar.js') ;
		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/commun/js/calendar-fr.js') ;
		$this->addJSLink($gJConfig->urlengine['basePath'] . 'design/commun/js/calendar-setup.js') ;		

    }

    protected function doAfterActions()
    {
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..
        
        //global $gJConfig;

		if (!isset($this->tiMenusActifs))
        {
			throw new Exception("Les menus actifs de la réponse HTML BoHtml doivent être définit");
		}
		if (!is_array($this->tiMenusActifs))
        {
			throw new Exception("Les menus actifs de la réponse HTML BoHtml doivent être un tableau");
		}

        // Header, Left et footer
		$this->body->assignZone('zHeader',	'commun~BoHeader') ;
		$this->body->assignZone('zLeft',	'commun~BoLeft', array ('tiMenusActifs'=>$this->tiMenusActifs)) ;
		$this->body->assignZone('zFooter',	'commun~BoFooter') ;
		
		// Content if not exist
		$this->body->assignIfNone('zContent','<p>no content</p>') ;
    }
}
