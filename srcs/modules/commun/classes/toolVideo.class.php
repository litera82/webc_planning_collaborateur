<?php
/**
* @package Reghalal
* @subpackage commun
* @version  1
* @author NEOV
*/

/**
* Fonctions utilitaires pour les videos
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolVideo {

	
	/**
	 * traitement des video et visuel video téléchargés 
	 * 
	 * @param 	string $zAction				action à faire
	 * @param 	string $zFichier			nom du fichier
	 * @param 	string $zPathResize			chemin du fichier
	 * @param 	string $zChampVideoPlayer	nom du player video
	 * @return 	array
	 */
    static function traitementVideo($zAction, $zFichier, $zPathResize, $zChampVideoPlayer = "") {
		global $gJConfig;

		switch($zAction){
			/*case 'suppr'://suppression des images re-dimensionn?es au cas o? annulation
				$tzResult=array();
				if(count($zFichier)>0){
					for($i=0;$i<count($zFichier);$i++){
						if($zFichier[$i]!=''){
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT1.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT2.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT3.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT4.basename($zFichier[$i]));
							
							if(file_exists(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i])) && is_file(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i])))
							@unlink(PATH_RESIZE_PHOTO_FORMAT5.basename($zFichier[$i]));
						}
					}
				}
				break;*/
			case 'resize'://dimensionner au format exact l'image téléchargée				
				$tzResult = array("visuel"=>"","image"=>"");
				$zFichier = substr($zFichier,strlen($gJConfig->urlengine['basePath']),strlen($zFichier));
				
				$zExtension=explode(".",basename($zFichier));
				
				switch(strtolower($zExtension[count($zExtension)-1])){				
					case 'swf':
						$format='SWF';
						break;
					case 'flv':
						$format='FLV';	
						break;
					case 'avi':
						$format='AVI';
						break;	
					default:
						$format='';
						break;
				}
				
				$zNomfichier = '';
				for($i=0;$i<count($zExtension)-1;$i++){
					$zNomfichier.=$zExtension[$i];
					if($i<count($zExtension)-2)
					$zNomfichier.='.';
				}	
				

				$zVisuel = $zNomfichier.".".strtolower($zExtension[count($zExtension)-1]);
				$i=1;

				while(file_exists($zPathResize.$zVisuel) && is_file($zPathResize.$zVisuel)){
					list($nom,$ext)=explode(".",$zVisuel);
					$nom=explode('_',$nom);
					$zVisuel='';
					if(!($i>1)){
						//$j=count($nom)-2;
						$k=count($nom)-1;
						for($l=0;$l<count($nom)-2;$l++) $zVisuel.=$nom[$l]."_";
					}else{
						//$j=count($nom)-3;
						$k=count($nom)-2;
						for($l=0;$l<count($nom)-3;$l++) $zVisuel.=$nom[$l]."_";
					}
					
					//$zVisuel=$zVisuel.$nom[$j]."_".$nom[$k]."_".$i.".".strtolower($ext);
					$zVisuel=$zVisuel.$nom[$k]."_".$i.".".strtolower($ext);
					$i++;
				}
			
				switch($format){
					case 'SWF':
						@copy($zFichier,$zPathResize.$zVisuel);
						$tzResult['image']=$zVisuel;
						$tzResult['visuel']=sprintf('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" id="index" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="%s" />	<param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="%s" menu="false" quality="high" bgcolor="#ffffff" name="index" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel,$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;
					case 'FLV':
						@copy($zFichier,$zPathResize.$zVisuel);
						$tzResult['image']=$zVisuel;
						/*$tzResult['visuel'] = sprintf('<embed width="250" height="200" wmode="transparent" type="application/x-shockwave-flash" 
											src="%sdesign/back/swf/video-player.swf" 
											pluginspage="http://www.adobe.com/go/getflashplayer" 
											flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/>
											<a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;">
											Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé
											</a>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);						
                        */
                        $tzResult['visuel'] = sprintf("<script type='text/javascript'>
                                                // <![CDATA[
                                                	var parametre = {};
                                                	parametre.allowScriptAccess = 'sameDomain';
                                                	parametre.quality = 'high';
                                                	parametre.allowFullScreen = true;
                                                	parametre.wmode = 'transparent';
                                                	
                                                	var flashvars = {};
                                                	flashvars.vid_autoStart = 0;
                                                	flashvars.showPlayer = 1;
                                                	flashvars.soundOnRollover = 0;
                                                	flashvars.sonActif = 1;
                                                	flashvars.fullScreenMode = 1;
                                                	flashvars.player_autohide = 1;
                                                	flashvars.vid_loop = 0;
                                                	flashvars.vid_scaleMode = 'maintainAspectRatio'; // 'noscale / exactFit'
                                                	flashvars.flvFile = '%s';
                                                	flashvars.defaultImage = ''; //design/front/images/data/preview/preview.jpg
                                                
                                                	
                                                	swfobject.embedSWF('%sdesign/back/swf/video-player.swf', '%s', '300', '300', '8.0.35', false, flashvars, parametre);
                                                // ]]>
                                                </script>", $gJConfig->urlengine['basePath'].$zPathResize.$zVisuel, $gJConfig->urlengine['basePath'], $zChampVideoPlayer);
                        break;	
					case 'AVI':
						@copy($zFichier,$zPathResize.$zVisuel);
						$tzResult['image']=$zVisuel;
						$tzResult['visuel'] = sprintf('<embed width="250" height="200" wmode="transparent" type="application/x-shockwave-flash" 
											src="%s/design/back/swf/flvplayer.swf" 
											pluginspage="http://www.adobe.com/go/getflashplayer" 
											flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/>
											<a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;">
											Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé
											</a>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;	
				} 
				break;
		}
		return $tzResult;
	}

   /**
     * redimensionne une vidéo de dailymotion ou youtube
     * 
     * @param 	string	$_zCodeVideo	le code de la video
     * @param	string	$_zWidth		largeur	de la video (100px ou 100%)
     * @param	string	$_zHeight		hauteur de la video
     * @return 	string
     */                         
    static function resizeVideo($_zCodeVideo, $_zWidth, $_zHeight){
        preg_match_all("/width=\"[0-9]+\"/", $_zCodeVideo, $width);
        preg_match_all("/height=\"[0-9]+\"/", $_zCodeVideo, $height);
        $newHeight = 'height="'.$_zHeight.'"';
        $newWidth = 'width="'.$_zWidth.'"';
        if (sizeof($width[0]) !=0){
            $_zCodeVideo = str_replace($width[0][1], $newWidth, $_zCodeVideo);
            $_zCodeVideo = str_replace($height[0][1], $newHeight, $_zCodeVideo);
        }
        return $_zCodeVideo;
    } 	
    
}
?>