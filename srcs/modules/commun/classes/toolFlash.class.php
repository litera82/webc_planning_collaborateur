<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires pour les flashs
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolFlash {
	
	/**
	* Fonction Encodage pour le flash :
	* Appel : 	$var = "...";
	*		 	echo "var=".flash_encode($var);
	*
	* @param 	string		$strToEncode
	* @return 	string
	*/
	static function encodeStringToFlashEncoded($strToEncode){
		$character = array("%","&");
		$unicode = array("%25","%26");
		$textresult = str_replace($character, $unicode, $strToEncode);

		$character = array("ƒ", "„", "…", "†", "‡", "ˆ", "‰", "Š", "‹", "Œ", "‘", "’", "“", "”", "•", "–", "—", "˜", "™", "š", "›", "œ", "Ÿ", "¡", "¢", "£", "¤", "¥", "¦", "§", "¨", "©", "ª", "«", "¬", "­", "®", "¯", "°", "±", "²", "³", "´", "µ", "¶", "·", "¸", "¹", "º", "»", "¼", "½", "¾", "¿", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "×", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "Þ", "ß", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "÷", "ø", "ù", "ú", "û", "ü", "ý", "þ", "ÿ", 
		"€", "<", ">", "/", "\\", '"', "\r", "\n", "+", "-");

		$unicode = array("%C6%92", "%E2%80%9E", "%E2%80%A6", "%E2%80%A0", "%E2%80%A1", "%CB%86", "%E2%80%B0", "%C5%A0", "%E2%80%B9", "%C5%92", "%E2%80%98", "%E2%80%99", "%E2%80%9C", "%E2%80%9D", "%E2%80%A2", "%E2%80%93", "%E2%80%94", "%CB%9C", "%E2%84%A2", "%C5%A1", "%E2%80%BA", "%C5%93", "%C5%B8", "%C2%A1", "%C2%A2", "%C2%A3", "%C2%A4", "%C2%A5", "%C2%A6", "%C2%A7", "%C2%A8", "%C2%A9", "%C2%AA", "%C2%AB", "%C2%AC", "%C2%AD", "%C2%AE", "%C2%AF", "%C2%B0", "%C2%B1", "%C2%B2", "%C2%B3", "%C2%B4", "%C2%B5", "%C2%B6", "%C2%B7", "%C2%B8", "%C2%B9", "%C2%BA", "%C2%BB", "%C2%BC", "%C2%BD", "%C2%BE", "%C2%BF", "%C3%80", "%C3%81", "%C3%82", "%C3%83", "%C3%84", "%C3%85", "%C3%86", "%C3%87", "%C3%88", "%C3%89", "%C3%8A", "%C3%8B", "%C3%8C", "%C3%8D", "%C3%8E", "%C3%8F", "%C3%90", "%C3%91", "%C3%92", "%C3%93", "%C3%94", "%C3%95", "%C3%96", "%C3%97", "%C3%98", "%C3%99", "%C3%9A", "%C3%9B", "%C3%9C", "%C3%9D", "%C3%9E", "%C3%9F", "%C3%A0", "%C3%A1", "%C3%A2", "%C3%A3", "%C3%A4", "%C3%A5", "%C3%A6", "%C3%A7", "%C3%A8", "%C3%A9", "%C3%AA", "%C3%AB", "%C3%AC", "%C3%AD", "%C3%AE", "%C3%AF", "%C3%B0", "%C3%B1", "%C3%B2", "%C3%B3", "%C3%B4", "%C3%B5", "%C3%B6", "%C3%B7", "%C3%B8", "%C3%B9", "%C3%BA", "%C3%BB", "%C3%BC", "%C3%BD", "%C3%BE", "%C3%BF", 
		"%E2%82%AC", "%3C", "%3E", "%2F", "%5C", "%22", "%0D", "%0A", "%2B", "%2D");

		return(str_replace($character, $unicode, $textresult));
				
	}


	/**
    * traitement des video et visuel video téléchargés 
	* re-dimensionnement,suppression
	* @param	string		$zAction
	* @param	string		$zFichier
	* @param	string		$zPathResize
	* @return	array		$result
    */
    static function traitementVideo($zAction, $zFichier, $zPathResize) {
		$zTmpFichier = $zFichier;
		global $gJConfig;
		jClasses::inc('commun~toolImage') ;

		switch($zAction){
			case 'suppr'://suppression des images re-dimensionn?es au cas o? annulation
				$result=array();
				if(count($zFichier)>0){
					for($i=0;$i<count($zFichier);$i++){
						if($zFichier[$i]!=''){
							if(file_exists($zPathResize.basename($zFichier[$i])) && is_file($zPathResize.basename($zFichier[$i])))
							@unlink($zPathResize.basename($zFichier[$i]));
						}
					}
				}
				break;
			case 'resize'://dimensionner au format exact l'image t?l?charg?e
				$result=array("visuel"=>"","image"=>"");
				$zFichier = substr($zFichier[0],strlen($gJConfig->urlengine['basePath']),strlen($zFichier[0]));				
				$extension=explode(".",basename($zFichier));
				switch(strtolower($extension[count($extension)-1])){
					case 'gif':
						$format='GIF';
						break;
					case 'jpeg':
					case 'jpg':
						$format='JPEG';
						break;
					case 'png':
						$format='PNG';
						break;
					case 'swf':
						$format='SWF';
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
				$nomfichier='';
				for($i=0;$i<count($extension)-1;$i++){
					$nomfichier.=$extension[$i];
					if($i<count($extension)-2)
					$nomfichier.='.';
				}
				
				$visuel=$nomfichier."_" . PATH_VISUEL_PUBLICITE_FLASH_WIDTH . "_" . PATH_VISUEL_PUBLICITE_FLASH_HEIGHT . ".".strtolower($extension[count($extension)-1]); 

				$i=1;
				while(file_exists($zPathResize.$visuel) && is_file($zPathResize.$visuel)){
					list($nom,$ext)=explode(".",$visuel);
					$nom=explode('_',$nom);
					$visuel='';
					if(!($i>1)){
						$j=count($nom)-2;
						$k=count($nom)-1;
						for($l=0;$l<count($nom)-2;$l++) $visuel.=$nom[$l]."_";
					}else{
						$j=count($nom)-3;
						$k=count($nom)-2;
						for($l=0;$l<count($nom)-3;$l++) $visuel.=$nom[$l]."_";
					}

					$visuel=$visuel.$nom[$j]."_".$nom[$k]."_".$i.".".strtolower($ext);
					$i++;
				}

				$result['image']=$visuel;

				$zBrowser = self::navigateur();

				switch($format){
					case 'GIF':
					break;
					case 'JPEG':
					break;
					case 'PNG':
					break;
					case 'SWF':
						@copy($zFichier,$zPathResize.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="250" height="200" wmode="transparent" type="application/x-shockwave-flash" src="%s" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="image=%s&file=%s&autostart=false&showstop=true&usefullscreen=false"/><a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;">Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé</a>', $gJConfig->urlengine['basePath'].$zPathResize.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="250" height="200"><param name="movie" value="%s" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /></object>', $gJConfig->urlengine['basePath'].$zPathResize.$visuel);					
							}
						break;
					case 'FLV':
						@copy($zFichier, $zPathResize.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="250" height="200" wmode="transparent" type="application/x-shockwave-flash" src="%sdesign/back/swf/flvplayer.swf" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/> <a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;"> Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé </a>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathResize.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="250" height="200"><param name="movie" value="%sdesign/back/swf/flvplayer.swf" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /><param name="flashvars" value="image=&file=%s&autostart=false&showstop=true&usefullscreen=false" /></object>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathResize.$visuel);					
							}
						break;	
					case 'AVI':
						@copy($zFichier,$zPathResize.$visuel);
							if ($zBrowser == 'ff'){
								$result['visuel'] = sprintf('<embed name="video" id="video" width="250" height="200" 
													wmode="transparent" type="application/x-shockwave-flash" 
													src="%sdesign/back/swf/flvplayer.swf" 
													pluginspage="http://www.adobe.com/go/getflashplayer" 
													flashvars="image=&file=%s&autostart=false&showstop=true&usefullscreen=false"/>
													<a target="_blank" href="http://www.macromedia.com/go/getflashplayer" style="display: none;">
													Pour visualiser la vidéo, vous devez avoir le dernier plugin Flash, et avoir Javascript activé
													</a>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathResize.$visuel);
							}else{
								$result['visuel'] = sprintf('<object id="video" name="video" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="250" height="200"><param name="movie" value="%sdesign/back/swf/flvplayer.swf" /><param name="bgcolor" value="#ffffff" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="align" value="middle" /><param name="play" value="true" /><param name="loop" value="false" /><param name="type" value="application/x-shockwave-flash" /><param name="pluginspage" value="http://www.adobe.com/go/getflashplayer" /><param name="wmode" value="transparent" /><param name="menu" value="false" /><param name="flashvars" value="image=&file=%s&autostart=false&showstop=true&usefullscreen=false" /></object>',$gJConfig->urlengine["basePath"],$gJConfig->urlengine['basePath'].$zPathResize.$visuel);					
							}
						break;	
				} 
				break;
		}
		return $result;
	}

	/**
	* identification du navigateur utilisé
	* @return string $browser
	*/
	static function navigateur (){
		 if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') ){
			 if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape') ){
				 $browser = 'Netscape (Gecko/Netscape)';
			 }
			 else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') ){
				$browser = 'ff';
			 }else{
				 $browser = 'ff';
			 }
		 }
		 else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ){
			 if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') ){
				 $browser = 'op';
			 }else{
				 $browser = 'ie';
			 }
		 }else{
			$browser = 'ff';
		 }
		 return $browser; 	
	} 

}
?>