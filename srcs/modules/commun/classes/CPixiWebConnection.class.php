<?php

/**
* Classe de connexion au service de PIXIWEB
*
*
* @package LEGAULOIS
* @subpackage LEGAULOIS:common:includes:classes
* @author NEOV
* @version 1.0
*
*/


@ini_set ('soap.wsdl_cache_enabled', 0) ;

class CPixiWebConnection
{
    
    /**
     * @var     string      URI du WSDL de la plate-forme de webservice
     * @access  public
     */
    public $zWsdlURI ;

    /**
     * @var     SoapClient  L'objet SoapClient qui établit la connexion
     * @access  private
     */
    private $oSoapClient ;

    /**
     * Constructeur
     *
     * @param   string      $_zWsdlURI          URI du WSDL
     */
    public function __construct ($_zWsdlURI = WEBCOUPON_PIXIWEB_WSDL)
    {
        $this->zWsdlURI = $_zWsdlURI ;
		try
		{
			$this->oSoapClient = new SoapClient ($this->zWsdlURI, array ("soap_version" => SOAP_1_2)) ;
		}
		catch (SoapFault $_eSoapFault)
		{
			$this->oSoapClient = null ;
		}
    }

    /**
     * Etablissement de la connexion
     *
     * @return bool         Statut de l'établissement de connexion
     */
    public function connect ()
    {
		return true ;
	}

    /**
     * Fermeture de la connexion
     *
     */
    public function disconnect ()
    {
    }

    /**
     * Appel webservice CreateUpdateUser
     *
     * @param   CPCUser     $_oNewUser      L'objet utilisateur à créer sur la plate-forme   
     * @return  integer                      Code de retour du serveur pixiweb 
     */
    public function createUpdateUserPixiWeb ($_oNewUser)
    {

		jClasses::inc ("commun~toolDate") ;

        $iRet = 0 ;

		try
		{
		
			$toUserTab = array	(
									'partnerKey'		=> WEBCOUPON_PIXIWEB_PARTNER_KEY,
									'partnerPassword'	=> WEBCOUPON_PIXIWEB_PARTNER_PWD,
									'username'			=> $_oNewUser->login,
									'password'			=> utf8_encode ('123456'),
									'email'				=> $_oNewUser->zEmail,
									'title'				=> intval ($_oNewUser->zCivilite),
									'firstname'			=> $_oNewUser->zPrenom,
									'lastname'			=> $_oNewUser->zNom,
									'birthdate'			=> toolDate::toDateFR ($_oNewUser->zDateNaissance),
									'addressStreet'		=> (!is_null ($_oNewUser->zAdresse1) && ($_oNewUser->zAdresse1 != "")) ? $_oNewUser->zAdresse1 : "-",
									'addressStreet2'	=> (!is_null ($_oNewUser->zAdresse2) && ($_oNewUser->zAdresse2 != "")) ? $_oNewUser->zAdresse2 : "-",
									'addressPostalCode'	=> (!is_null ($_oNewUser->zCodePostal) && ($_oNewUser->zCodePostal != "")) ? $_oNewUser->zCodePostal : "-",
									'addressCity'		=> (!is_null ($_oNewUser->zVille) && ($_oNewUser->zVille != "")) ? $_oNewUser->zVille : "-",
									'addressCountry'	=> WEBCOUPON_PIXIWEB_ADDRESS_COUNTRY,
									'customProperty1'	=> $_oNewUser->zEan128,
									'customProperty2'	=> "",
									'customProperty3'	=> ""
								) ;

			$oRetourPixi = $this->oSoapClient->CreateUpdateUser ($toUserTab) ;
			$iRet = $oRetourPixi->CreateUpdateUserResult ;

		}
		catch (SoapFault $_eSoapFault)
		{
			throw new Exception ($_eSoapFault->getMessage ()) ;
		}
		
        return $iRet ;

    }    

}

?>