<?php
/**
* @package   jelix_calendar
* @subpackage client
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class postsCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_POSTS, BoHtmlResponse::MENU_POSTS_LISTE) ;
		$oCritere = new stdClass ();

		$oCritere->post_status = $this->param('post_status', '', true);
		$oCritere->post_type = $this->param('post_type', '', true);
		$oCritere->post_title = $this->param('post_title', '', true);

        $oResp->body->assignZone('zContent', 'posts~BoPostsListe', array('oCritere'=>$oCritere)) ;
	
		return $oResp ;
    }
	function edit() {
		$toParams = $this->params() ;
		$oResp = $this->getResponse('BoHtml') ;
		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_POSTS, BoHtmlResponse::MENU_POSTS_LISTE) ;
		$oResp->body->assignZone('zContent', 'posts~BoPostsEdit',$toParams) ;
        return $oResp ;
    }
	function save() {
    	$toParams = $this->params() ;
        jClasses::inc('posts~wppostsSrv');
    	jClasses::inc('client~clientSrv');

        $oPosts = wppostsSrv::save($toParams) ;
		if (isset($toParams['genereProjet']) && $toParams['genereProjet'] == "on"){
			$toClient = array();
			$toClient['client_iCivilite'] = 1 ;
			$toClient['client_zNom'] = $toParams['post_title'] ;
			$toClient['client_zPrenom'] = $toParams['post_title'] ;
			$toClient['client_zLogin'] = 1 ;
			$toClient['client_zPass'] = 1 ;
			$toClient['client_testDebut'] = 0 ;
			$oClient = clientSrv::save($toClient);
			wppostsSrv::updatePostClientId($oPosts->ID, $oClient->client_id);
		}
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'posts~posts:index' ;
        return $oResp ;
    }
	function delete() {
        jClasses::inc('posts~wppostsSrv');
        wppostsSrv::delete($this->param('id', 0, true)) ;
        $oResp = $this->getResponse('redirect') ;
        $oResp->action = 'posts~posts:index' ;
        return $oResp ;
    }
	function pagePostProjet (){
        $oResp = $this->getResponse('BoHtml') ;
		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_POSTS, BoHtmlResponse::MENU_POSTS_GENERER) ;
		$res = $this->param('res', '', true);
        $oResp->body->assignZone('zContent', 'posts~BoPageGenerer', array('res'=>$res)) ;
		return $oResp ;
	}
	function pageDedoublonnerPost (){
        $oResp = $this->getResponse('BoHtml') ;
		$oResp->tiMenusActifs = array(BoHtmlResponse::MENU_POSTS, BoHtmlResponse::MENU_POSTS_DEDOUBLONNER) ;
		$res = $this->param('res', '', true);
        $oResp->body->assignZone('zContent', 'posts~BoPageDedoublonnerPost', array('res'=>$res)) ;
		return $oResp ;
	}

	function generateProjetPost (){
        $oResp = $this->getResponse('redirect') ;
        jClasses::inc('posts~wppostsSrv');
    	jClasses::inc('client~clientSrv');

        $toPosts = wppostsSrv::getPostsNoClient() ;

		foreach ($toPosts as $oPosts){
			$toClient = array();
			$toClient['client_iCivilite'] = 1 ;
			$toClient['client_zNom'] = $oPosts->post_title ;
			$toClient['client_zPrenom'] = $oPosts->post_title ;
			$toClient['client_zLogin'] = 1 ;
			$toClient['client_zPass'] = 1 ;
			$toClient['client_testDebut'] = 0 ;
			$toClient['client_iStatut'] = 1 ;
			$oClient = clientSrv::save($toClient);
			wppostsSrv::updatePostClientId($oPosts->ID, $oClient->client_id);
		}
        $oResp->action = 'posts~posts:pagePostProjet' ;
		$oResp->params = array ('res'=>1001);	

        return $oResp ;
	}
	/*function dedoublonnerPost (){
        $oResp = $this->getResponse('redirect') ;
        jClasses::inc('posts~wppostsSrv');
    	jClasses::inc('client~clientSrv');

        $toPosts = wppostsSrv::getAllPosts() ;
		foreach ($toPosts as $oPosts){
			$toSamePosts = wppostsSrv::getByTitle($oPosts->post_title, $oPosts->ID);
			if (sizeof($toSamePosts) > 0){
				foreach($toSamePosts as $oSamePosts){
					$toPostsClients = wppostsSrv::getAllPostsClients($oSamePosts->ID, $oPosts->ID);
					foreach ($toPostsClients as $oPostsClients){
						if ($oPostsClients->post_id != $oPosts->ID){
							wppostsSrv::updateEventAndDeleteClient(wppostsSrv::getPostsClients($oPosts->ID), $oPostsClients->client_id) ;
							wppostsSrv::deletePostClientByPostId ($oPostsClients->post_id) ;
						}
					}
					wppostsSrv::delete($oSamePosts->ID);
				}
			}
		}
		$oResp->action = 'posts~posts:pageDedoublonnerPost' ;
		$oResp->params = array ('res'=>1001);	

        return $oResp ;
	}*/

	function dedoublonnerPost (){
        $oResp = $this->getResponse('redirect') ;
        jClasses::inc('posts~wppostsSrv');
    	jClasses::inc('client~clientSrv');

        $toPosts = wppostsSrv::deleteOtherPosts() ;

		foreach ($toPosts as $oPosts){
			$oPostClient = wppostsSrv::getPostsClients ($oPosts->ID) ;
			if (!$oPostClient){
				// Creer client
				$toInfos = array ();
				$toInfos['client_iCivilite'] = 1;
				$toInfos['client_zNom'] = $oPosts->post_title ;
				$toInfos['client_zLogin'] = 1;
				$toInfos['client_zPass'] = 1;
				$toInfos['client_iStatut'] = 1;
				$toInfos['client_testDebut'] = 0;
				$oNewClient = clientSrv::save ($toInfos);
				if ($oNewClient->client_id){
					wppostsSrv::updatePostClientId ($oPosts->ID, $oNewClient->client_id) ;
				}
			}else{
				if ($oPosts->ID > 0){	
					$toInfos = array (); 
					$toInfos['id'] = $oPosts->ID ;
					$toInfos['post_author'] = $oPosts->post_author ;
					$toInfos['post_date'] = $oPosts->post_date ;
					$toInfos['post_date_gmt'] = $oPosts->post_date_gmt ;
					$toInfos['post_title'] = $oPosts->post_title ;
					$toInfos['post_status'] = $oPosts->post_status ;
					$toInfos['post_name'] = $oPosts->post_name ;
					$toInfos['guid'] = $oPosts->guid ;
					$toInfos['post_type'] = $oPosts->post_type ;
					$toInfos['post_content'] = $oPosts->post_content ;

			        wppostsSrv::save($toInfos) ;

					if (isset ($oPostsClient->client_id) && $oPostsClient->client_id > 0){
						$toInfos = array ();
						$toInfos['client_id'] = $oPostsClient->client_id;
						$toInfos['client_zNom'] = $oPosts->post_title ;
						clientSrv::save ($toInfos);
					}
				}
			}
		}
		wppostsSrv::truncatewpposttoinsert();
		$oResp->action = 'posts~posts:pageDedoublonnerPost' ;
		$oResp->params = array ('res'=>1001);	

        return $oResp ;
	}

}