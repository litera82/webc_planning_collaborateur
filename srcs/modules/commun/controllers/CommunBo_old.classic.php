<?php

class CommunBoCtrl extends jController
{
    /**
    * Action générique permettant d'appeller une zone sans passer par un controlleur en particulier
    *
    * Utile pour la recherche de liste en AJAX (on a donc pas à créer une action pour chaque zone appellable en ajax)
    */
    function getZone()
    {
        $oResp = $this->getResponse('text');

        $oZone = $this->param('zone');

        if(is_null($oZone))
        {
            throw new Exception ('Parametre zone requis') ;
        }

        $tParams = $GLOBALS['gJCoord']->request->params;

        $oResp->content = jZone::get($oZone, $tParams) ;

        return $oResp;
    }


    function login ()
    {
		$toParams = $this->params () ;
		$toParams['failed'] = $this->param ('failed', 0) ;
        $oResp = $this->getResponse('html');
		$oResp->bodyTpl = 'commun~BoLogin' ;
        $oResp->body->assign($toParams);
        return $oResp ;
    }


    function logout ()
    {
    	jClasses::inc ('commun~CUtilisateurs') ;
    	CUtilisateurs::processLogout() ;

    	$zUrlReturn = jUrl::get('jauth~login:form') ;

		$oResp = $this->getResponse ('redirectUrl') ;
		$oResp->url = $zUrlReturn ;
        return $oResp ;
    }


    function loginAction ()
    {
    	$zLogin		= $this->param ('login', '') ;
        $zPass		= $this->param ('password', '') ;
        $zCurrentUrl= $this->param ('zCurrentUrl', '') ;

    	jClasses::inc ('commun~CUtilisateurs') ;

		$bError = false ;

		try
		{
			CUtilisateurs::processAuthentication($zLogin, $zPass) ;
        	$zUrlReturn = jUrl::get('annonces~BoAnnonces:listeAnnonces') ;
		}
		catch (Exception $_eException)
		{
			$bError = true ;
        	$zUrlReturn = jUrl::get('commun~CommunBo:login', array('failed'=>1)) ;
		}

		jFirePhp::MyLog($zUrlReturn);

		$oResp = $this->getResponse ('redirectUrl') ;
		$oResp->url = $zUrlReturn ;
        return $oResp ;

    }
}

?>
