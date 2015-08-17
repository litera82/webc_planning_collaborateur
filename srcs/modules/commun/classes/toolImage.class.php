<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires pour les traitements d'image
*
* @package Reghalal
* @subpackage commun
*/
class ToolImage {


	/**
    * traitement des visuels téléchargés re-dimensionnement,suppression
	* @param	string	$zAction
	* @param	string	$zFichier
	* @param	int		$iImageWidth
	* @param	int		$iImageHeight
	* @param	string	$zPathResize
	* @param	string	$zNouveauFichier
	* @return	array	$tzResult
    */
    static function traitementVisuels($zAction, $zFichier,$iImageWidth, $iImageHeight, $zPathResize, $zNouveauFichier=null, $zMethode='ratio') {
		global $gJConfig;
		switch($zAction){
			case 'suppr'://suppression des images re-dimensionnées au cas ou annulation
				$tzResult=array();
				if(count($zFichier)>0){
					for($i=0;$i<count($zFichier);$i++){
						if($zFichier[$i]!=''){
							$zFichier[$i] = basename($zFichier[$i]);
							if(file_exists($zPathResize.$zFichier[$i]) && is_file($zPathResize.$zFichier[$i]))
              					@unlink($zPathResize.$zFichier[$i]);							
						}
					}
				}
				break;
			case 'resize'://dimensionner au format exact l'image t?l?charg?e

				$tzResult = array("visuel"=>"","image"=>"");
				$zFichier = substr($zFichier[0],strlen($gJConfig->urlengine['basePath']),strlen($zFichier[0]));				
				$zExtension=explode(".",basename($zFichier));

				//jClasses::inc('commun~image') ;
				switch(strtolower($zExtension[count($zExtension)-1])){
					case 'gif':
						$zFormat='GIF';
						break;
					case 'jpeg':
					case 'jpg':
						$zFormat='JPEG';
						break;
					case 'png':
						$zFormat='PNG';
						break;
					case 'swf':
						$zFormat='SWF';
						break;
					default:
						$zFormat='';
						break;
				}
				
				$zNomfichier = '';
				for($i=0;$i<count($zExtension)-1;$i++){
					$zNomfichier.=$zExtension[$i];
					if($i<count($zExtension)-2)
					$zNomfichier.='.';
				}
				
				if (!is_null($zNouveauFichier)){
					$zNomfichier = $zNouveauFichier;
				} 

				$zVisuel = $zNomfichier."_".$iImageWidth."_".$iImageHeight.".".strtolower($zExtension[count($zExtension)-1]);
				$i=1;
				
				
				while(file_exists($zPathResize.$zVisuel) && is_file($zPathResize.$zVisuel)){
					list($nom,$ext)=explode(".",$zVisuel);
					$nom=explode('_',$nom);
					$zVisuel='';
					if(!($i>1)){
						$j=count($nom)-2;
						$k=count($nom)-1;
						for($l=0;$l<count($nom)-2;$l++) $zVisuel.=$nom[$l]."_";
					}else{
						$j=count($nom)-3;
						$k=count($nom)-2;
						for($l=0;$l<count($nom)-3;$l++) $zVisuel.=$nom[$l]."_";
					}

					$zVisuel=$zVisuel.$nom[$j]."_".$nom[$k]."_".$i.".".strtolower($ext);
					$i++;
				}

				$tzResult['image']=$zVisuel;
				
				switch($zFormat){
					case 'GIF':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){							
							//$imgF->resize($iImageWidth,$iImageHeight,'crop',true);
							$imgF->resize($iImageWidth,$iImageHeight,$zMethode,true);
							$imgF->output($zFormat,$zPathResize.$zVisuel,true);	
							$imgF->clear();
						}else{
							@copy($zFichier,$zPathResize.$zVisuel);
						}						
						
						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
					break;

					case 'JPEG':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);
						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){
							//$imgF->resize($iImageWidth,$iImageHeight,'crop',true);
							$imgF->resize($iImageWidth,$iImageHeight,$zMethode,true);

							$imgF->output($zFormat,$zPathResize.$zVisuel,true);	
							$imgF->clear();
						}else{
							@copy($zFichier,$zPathResize.$zVisuel);
						}					
						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
					break;

					case 'PNG':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){
							//$imgF->resize($iImageWidth,$iImageHeight,'crop',true);
							$imgF->resize($iImageWidth,$iImageHeight,$zMethode,true);
							$imgF->output($zFormat,$zPathResize.$zVisuel,true);	
							$imgF->clear();
						}else{
							copy($zFichier,$zPathResize.$zVisuel);
						}						

						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
					break;
					case 'SWF':
						@copy($zFichier,$zPathResize.$zVisuel);
						$tzResult['visuel']=sprintf('<object width="%s" height="%s" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" id="index" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="%s" />	<param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed width="%s" height="%s" src="%s" menu="false" quality="high" bgcolor="#ffffff" name="index" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>',$iImageWidth,$iImageHeight,$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel,$iImageWidth,$iImageHeight,$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
					break;
				}
				break;
		}
		return $tzResult;
	}


	/**
     * copie d'une image a partir de son url
     * @param	string	$zFichier
	 * @param	string	$zPathResize
	 * @return	string
     */    
    static function copieImage($zFichier, $zPathResize)
    {
        $data = file_get_contents($zFichier);
		$file = fopen(JELIX_APP_WWW_PATH . $zPathResize.$zVisuel . basename($zFichier), "w+");
		fputs($file, $data);
		fclose($file);

		return basename($zFichier) ;
    }
	/**
    * traitement des visuels téléchargés re-dimensionnement,suppression
	* @param	string	$zAction
	* @param	string	$zFichier
	* @param	int		$iImageWidth
	* @param	int		$iImageHeight
	* @param	string	$zPathResize
	* @param	string	$zNouveauFichier
	* @return	array	$tzResult
    */
    static function traitementVisuelsFlux($zAction, $zFichier,$iImageWidth, $iImageHeight, $zPathResize, $zNouveauFichier=null) {

		global $gJConfig;
 
		switch($zAction){
			case 'suppr'://suppression des images re-dimensionnées au cas ou annulation
				$tzResult=array();
				if(count($zFichier)>0){
					for($i=0;$i<count($zFichier);$i++){
						if($zFichier[$i]!=''){
							$zFichier[$i] = basename($zFichier[$i]);
							if(file_exists($zPathResize.$zFichier[$i]) && is_file($zPathResize.$zFichier[$i]))
              					@unlink($zPathResize.$zFichier[$i]);							
						}
					}
				}
				break;
			case 'resize'://dimensionner au format exact l'image t?l?charg?e

				$tzResult = array("visuel"=>"","image"=>"");
				$zFichier = $zFichier[0];
				$zExtension=explode(".",basename($zFichier));

				//jClasses::inc('commun~image') ;
				switch(strtolower($zExtension[count($zExtension)-1])){
					case 'gif':
						$zFormat='GIF';
						break;
					case 'jpeg':
					case 'jpg':
						$zFormat='JPEG';
						break;
					case 'png':
						$zFormat='PNG';
						break;
					case 'swf':
						$zFormat='SWF';
						break;
					default:
						$zFormat='';
						break;
				}
				
				$zNomfichier = '';
				for($i=0;$i<count($zExtension)-1;$i++){
					$zNomfichier.=$zExtension[$i];
					if($i<count($zExtension)-2)
					$zNomfichier.='.';
				}
				
				if (!is_null($zNouveauFichier)){
					$zNomfichier = $zNouveauFichier;
				} 

				$zVisuel = $zNomfichier."_".$iImageWidth."_".$iImageHeight.".".strtolower($zExtension[count($zExtension)-1]);
				$i=1;
				
				
				while(file_exists($zPathResize.$zVisuel) && is_file($zPathResize.$zVisuel)){
					list($nom,$ext)=explode(".",$zVisuel);
					$nom=explode('_',$nom);
					$zVisuel='';
					if(!($i>1)){
						$j=count($nom)-2;
						$k=count($nom)-1;
						for($l=0;$l<count($nom)-2;$l++) $zVisuel.=$nom[$l]."_";
					}else{
						$j=count($nom)-3;
						$k=count($nom)-2;
						for($l=0;$l<count($nom)-3;$l++) $zVisuel.=$nom[$l]."_";
					}

					$zVisuel=$zVisuel.$nom[$j]."_".$nom[$k]."_".$i.".".strtolower($ext);
					$i++;
				}

				$tzResult['image']=$zVisuel;
				
				switch($zFormat){
					case 'GIF':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){							
							$imgF->resize($iImageWidth,$iImageHeight,'crop',true);
						
							$imgF->output($zFormat,JELIX_APP_WWW_PATH . $zPathResize.$zVisuel,true);	
							$imgF->clear();
						}else{
							if (!@copy($zFichier,JELIX_APP_WWW_PATH . $zPathResize.$zVisuel)){
								$data = file_get_contents($zFichier);
								$file = fopen(JELIX_APP_WWW_PATH . $zPathResize.$zVisuel . basename($zFichier), "w+");
								fputs($file, $data);
								fclose($file);							
							}
						}						
						
						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;

					case 'JPEG':
						$imgF = new ImageFilter;
						//$imgF2 = new ImageFilter;
						$imgF->loadImage($zFichier);
						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){
							$imgF->resize($iImageWidth,$iImageHeight,'crop',true);
							$imgF->output($zFormat,JELIX_APP_WWW_PATH . $zPathResize.$zVisuel,true);	
							$imgF->clear();
						}else{
							if (!@copy($zFichier,JELIX_APP_WWW_PATH . $zPathResize.$zVisuel)){
								$data = file_get_contents($zFichier);
								$file = fopen(JELIX_APP_WWW_PATH . $zPathResize.$zVisuel . basename($zFichier), "w+");
								fputs($file, $data);
								fclose($file);							
							}
						}					
						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
	

						break;

					case 'PNG':
						$imgF = new ImageFilter;
						$imgF->loadImage($zFichier);

						$tSize = $imgF->getImageSize () ;
						$width = $tSize['w'];
						$height= $tSize['h'];
						if ($iImageWidth < $tSize['w'] || $iImageHeight < $tSize['h']){
							$imgF->resize($iImageWidth,$iImageHeight,'crop',true);
							$imgF->output($zFormat,JELIX_APP_WWW_PATH . $zPathResize.$zVisuel,true);	
							$imgF->clear();
						}else{
							if (!copy($zFichier,JELIX_APP_WWW_PATH . $zPathResize.$zVisuel)){
								$data = file_get_contents($zFichier);
								$file = fopen(JELIX_APP_WWW_PATH . $zPathResize.$zVisuel . basename($zFichier), "w+");
								fputs($file, $data);
								fclose($file);							
							}
						}						

						$tzResult['visuel']=sprintf('<img src="%s" align="absmiddle">',$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;
					case 'SWF':
						@copy($zFichier,$zPathResize.$zVisuel);
						$tzResult['visuel']=sprintf('<object width="%s" height="%s" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" id="index" align="middle"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="%s" />	<param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed width="%s" height="%s" src="%s" menu="false" quality="high" bgcolor="#ffffff" name="index" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>',$iImageWidth,$iImageHeight,$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel,$iImageWidth,$iImageHeight,$gJConfig->urlengine['basePath'].$zPathResize.$zVisuel);
						break;
				}
				break;
		}
		return $tzResult;
	}

}



/**
 * Classe de traitement d'image prennant à sa charge les traitement de base afin de simplifier au maximum la génération d'image en PHP
 *
 * PHP versions 4 et 5 (GD 1.x et 2.x)
 *
 * Cette classe à été developpé dans le but de simplifier la vie de tous. Libre à vous de l'inclure dans vos projet.
 * Une seule limitation, je vous serez reconnaissant de me tenir au courant des éventuelles corrections,
 * modifications, ajouts que vous pourriez faire afin que je puisse les intégrés au projet initial.
 *
 /**
 * @package Reghalal
 * @subpackage commun
 * @version  1
 * @author NEOV
 */

	/**
	*
	* @package Reghalal
	* @subpackage commun
	*/

/**
 * Exemple d'utilisation
 * 
 * $IF=new ImageFilter;
 * $IF->loadImage('test.png');
 * $IF->resize('200%','','force',true);
 * $IF->setColorsToWork(16); // default = 256
 * $IF->sepia();
 * $IF->mosaic(3);
 * $IF3->output('PNG');
 *
 */

class ImageFilter
{
	
	/**
	* $colorsToWork :
	* détermine le nombre de couleur par défaut sur lequel travaillerons les filtres
	* Mettre une valeur faible peut avoir des comportement étonnant
	* selon la version de la librairie et de la plateforme d'execution.
	* En effet si manifestement sous Windows les couleurs sont bien choisit (l'image resultante est plutot fidèle)
	* sous Linux le choix est parfois "particulier" et il n'est pas rare d'obtenir un image au teintes
	* trop claire ou trop sombre (parfois même toutes identiques > donc plus d'image ^^)
	* 
	* $GD_VERSION : Peut prendre la valeur 1 ou 2 selon la version de la librairie GD installé sur le serveur
	* 
	*/
    var $GD_VERSION=2; 
    var $colorsToWork = 256; 

    /**
	* filtre image
	*/
	function ImageFilter()
    {
        $this->resourceImage=NULL;
    }
    
	/**
	* @param int $nb
	*/
	function setColorsToWork($nb)
    {
        $this->colorsToWork=$nb;
    }
    
    /**
	* fonction clear
	*/
	function clear()
    {
        imagedestroy($this->resourceImage);
    }
    
    /**
    * Création d'une image vierge 
    * @param int $w	largeur 
    * @param int $h	hauteur
    */
    function createImage($w,$h)
    {
        $this->resourceImage = $this->imagecreate($w,$h);
    }
    
    /**
     * Chargement d'une image depuis un fichier
     * @param	string	$path
	 * @return	boolean
     */
    function loadImage($path)
    {
        $this->resourceImage = $this->loadImageFile($path);
        return is_resource($this->resourceImage);
    }
    
    /**
     * Méthode privé (pas vraiment possible en PHP4) gérant l'ouverture et la mise en mémoire d'une image depuis un fichier
     * utilisé entre autre par loadImage(...)  et Stamp(...)
     * @param string $path
	 * @return mixed
     */
    function loadImageFile($path)
    {	
        $info=getimagesize($path);
        switch($info[2])
        {
            case 3 :
                return imagecreatefrompng($path);
            case 2 :
                return imageCreateFromJpeg($path);
            case 1 :
                return imagecreatefromgif($path);
            default : 
                return false;
        }
    }
    
    /**
     * Méthode de lecture des dimension de l'image actuellement en court de traitement
     * il est aussi possible de lui passer en paramètre un objet de type image
     * @param	string  $img
	 * @return	array
     */
    function getImageSize($img=NULL)
    {
        if(is_resource($img))
        {
            return array(
                'w'=>imagesx($img),
                'h'=>imagesy($img)
            );
        }
        else
        {
            return array(
                'w'=>imagesx($this->resourceImage),
                'h'=>imagesy($this->resourceImage)
            );
        }
    }

    /**
     * Méthode de sorti
     * Il est possible de générer soit des PNG soit des JPEG (gestion du niveau de qualité)
     * l'image peut soit être envoyé soit en flux direct, soit enregistré dans un fichier
     * ex:
     * $IF->output('JPEG',NULL,NULL,80); // JPEG Q80 en flux direct
     * $IF->output('JPEG','cache.jpg',false,80); // JPEG Q80 enregistré dans cache.jpg sans écrasement si déjà existant
	 * @param	string		$type
	 * @param	string		$file
	 * @param	boolean		$overwrite
	 * @param	int			$JPG_Q
	 * @return  mixed
	 *
     */
    function output($type='PNG',$file=NULL,$overwrite=true,$JPG_Q=90)
    {
        if($file==NULL)
        {
            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
            header('Pragma: no-cache');
            switch($type)
            {
                case 'PNG' :
                    header ('Content-type: image/png');
                    imagepng($this->resourceImage);
                    return true;
                case 'JPEG' : 
                    header ('Content-type: image/jpeg');
                    imagejpeg($this->resourceImage,NULL,$JPG_Q);
                    return true;
				case 'GIF' :
				//if (function_exists("imagegif")) {
					header("Content-type: image/gif");
					imagegif($this->resourceImage);
					return true;
				//}

                default :
                    return false;
            }
        }
        else
        {
            if($overwrite or !file_exists($file))
            {
                switch($type)
                {
                    case 'PNG' :
                        return imagepng($this->resourceImage,$file);
                    case 'JPEG' : 
                        return imagejpeg($this->resourceImage,$file,$JPG_Q);
					case 'GIF' :
						return imagegif($this->resourceImage,$file);
                    default :
                        return false;
                }
            }
        }
    }
    
    /**
     * Méthode de découpe
     * ex
     * $IF->crop(10,10,50,50) // découpe l'image courante depuis le point 10,10 sur une zone de 50x50 pixel
     * l'image courante devient l'élément découpé
	 * @param	int			$X
	 * @param	int			$Y
	 * @param	int			$WIDTH
	 * @param	int			$HEIGHT
	 * @return	 boolean
     */
    function crop($X,$Y,$WIDTH,$HEIGHT)
    {
        if(min($WIDTH,$HEIGHT)==0)
            return false;

        $img2=$this->imagecreate($WIDTH,$HEIGHT);
        $this->imagecopyresampled($img2,$this->resourceImage,0,0,$X,$Y,$WIDTH,$HEIGHT,$WIDTH,$HEIGHT);
        imagedestroy($this->resourceImage);
        $this->resourceImage=$img2;
        return true;
    }
    

	 /**
     * Méthode de redimensionnement classique.
     * 
     * Les deux paramètre $WIDTH et $HEIGHT peuvent être précisé soit en pixel (70) soit en % ('70%')
     * 
     * 3 mode sont possibles : "force", "crop" ou "ratio"
     *    - force = par déformation
     *    - crop = par recadrage au centre
     *    - ratio = par conservation de l'aspect ratio ($WIDTH et $HEIGHT = Boite de travail) 
     * 
     * Le paramètre expand permet de préciser si les agrandissement sont autorisé
	 * @param	int		$WIDTH
	 * @param	int		$HEIGHT
	 * @param	string	$MODE
	 * @param	boolean	$EXPAND
     */
    function resize($WIDTH,$HEIGHT,$MODE='force',$EXPAND=false)
    {
        $info=$this->getImageSize();
        $imgWidth=$info['w'];
        $imgHeight=$info['h'];

        $ratio=$imgWidth/$imgHeight;
    
        //gestion des dimension en %
        if(strpos($WIDTH,'%',0))
            $WIDTH=$imgWidth * $WIDTH / 100;
        if(strpos($HEIGHT,'%',0))
            $HEIGHT=$imgHeight * $HEIGHT / 100;
    
        //si pas de dimension précisées alors echec
        if($WIDTH==0 && $HEIGHT==0)
            return false;
            
        //si jamais une dimension = 0 on détermine la valeur la plus approprié.
        if(min($WIDTH,$HEIGHT)==0)
        {
            switch($MODE)
            {
                case 'crop' : //on coupe en carré
                    $WIDTH=$HEIGHT=max($WIDTH,$HEIGHT);
                    break;
                    
                case 'force': //on passe en ratio pour éviter les déformation
                    $MODE='ratio';
                    
                case 'ratio' : //on prend une taille très grand pour ne pas limiter sur la cote non précisé
                    if($WIDTH==0)
                        $WIDTH=9999;
                    else
                        $HEIGHT=9999;                    
                    break;
                case 'ratio_force':
                	  $MODE='ratio_force';
                	break;
                default :
                    break;
            }
        }
        
        //on détermine les dimension du resize ($_w et $_h)
       /* if($MODE=='ratio')
        {
            $_w=99999;
            if($HEIGHT>0)
            {
                $_h=$HEIGHT;
                $_w=$_h*$ratio;
            }
            if($WIDTH>0 && $_w>$WIDTH)
            {
                $_w=$WIDTH;
                $_h=$_w/$ratio;
            }
            
            if(!$EXPAND && $_w>$imgWidth)
            {
                $_w=$imgWidth;
                $_h=$imgHeight;
            }
        }
        else
        {
            //par découpage de l'image source
            $_w=$WIDTH;
            $_h=$HEIGHT;
        }
        
        if($MODE=='force')
        {
            if(!$EXPAND && $_w>$imgWidth)
                $_w=$imgWidth;
            if(!$EXPAND && $_h>$imgHeight)                
                $_h=$imgHeight;
            
            $cropW=$imgWidth;
            $cropH=$imgHeight;
            $decalW=0;
            $decalH=0;
        }
        else //crop
        {
            //on détermine ensuite la zone d'affiche réel pour l'image
            $innerRatio=$_w/$_h;
            if($ratio>=$innerRatio)
            {
                $cropH=$imgHeight;
                $cropW=$imgHeight*$innerRatio;
                $decalH=0;                
                $decalW=round(($imgWidth-$cropW)/2);
            }
            else
            {
                $cropW=$imgWidth;
                $cropH=$imgWidth/$innerRatio;
                $decalW=0;
                $decalH=round(($imgHeight-$cropH)/2);
            }
        }*/

       switch($MODE)
        {
            case 'crop' : //on coupe en carré
                //$WIDTH=$HEIGHT=max($WIDTH,$HEIGHT);
                
                $_w=$WIDTH;
            	$_h=$HEIGHT;
            	
            	//on détermine ensuite la zone d'affiche réel pour l'image
	            $innerRatio=$_w/$_h;
	            if($ratio>=$innerRatio)
	            {
	                $cropH=$imgHeight;
	                $cropW=$imgHeight*$innerRatio;
	                $decalH=0;                
	                $decalW=round(($imgWidth-$cropW)/2);
	            }
	            else
	            {
	                $cropW=$imgWidth;
	                $cropH=$imgWidth/$innerRatio;
	                $decalW=0;
	                $decalH=round(($imgHeight-$cropH)/2);
	            }
            
	            $img2=$this->imagecreate($_w,$_h);
				$this->imagecopyresampled($img2,$this->resourceImage,0,0,$decalW,$decalH,$_w,$_h,$cropW,$cropH);
                break;
                
            case 'force': //on passe en ratio pour éviter les déformation
                //$WIDTH=$HEIGHT=max($WIDTH,$HEIGHT);
                
                $_w=$WIDTH;
            	$_h=$HEIGHT;
            	
            	if(!$EXPAND && $_w>$imgWidth)
	                $_w=$imgWidth;
	            if(!$EXPAND && $_h>$imgHeight)                
	                $_h=$imgHeight;
	            
	            $cropW=$imgWidth;
	            $cropH=$imgHeight;
	            $decalW=0;
	            $decalH=0;
            	
	            $img2=$this->imagecreate($_w,$_h);
				$this->imagecopyresampled($img2,$this->resourceImage,0,0,$decalW,$decalH,$_w,$_h,$cropW,$cropH);		
	            break;
            case 'ratio' : //on prend une taille très grand pour ne pas limiter sur la cote non précisé
                if($WIDTH==0)
                    $WIDTH=9999;
                else
                    $HEIGHT=9999;  
                    
                $_w=99999;
	            if($HEIGHT>0)
	            {
	                $_h=$HEIGHT;
	                $_w=$_h*$ratio;
	            }
	            if($WIDTH>0 && $_w>$WIDTH)
	            {
	                $_w=$WIDTH;
	                $_h=$_w/$ratio;
	            }
	            
	            if(!$EXPAND && $_w>$imgWidth)
	            {
	                $_w=$imgWidth;
	                $_h=$imgHeight;
	            }
	            
	            //on détermine ensuite la zone d'affiche réel pour l'image
	            $innerRatio=$_w/$_h;
	            if($ratio>=$innerRatio)
	            {
	                $cropH=$imgHeight;
	                $cropW=$imgHeight*$innerRatio;
	                $decalH=0;                
	                $decalW=round(($imgWidth-$cropW)/2);
	            }
	            else
	            {
	                $cropW=$imgWidth;
	                $cropH=$imgWidth/$innerRatio;
	                $decalW=0;
	                $decalH=round(($imgHeight-$cropH)/2);
	            }
	            
	            $img2=$this->imagecreate($_w,$_h);
				$this->imagecopyresampled($img2,$this->resourceImage,0,0,$decalW,$decalH,$_w,$_h,$cropW,$cropH);
                break;
            case 'ratio-force':
		        $ratio_dest = $WIDTH / $HEIGHT;
				$ratio_src = $imgWidth / $imgHeight;
		        
				if($ratio_src < $ratio_dest){
					//On resize en forcant la hauteur de l'image
					$_h =  $HEIGHT; 
					$_w = round($imgWidth * $HEIGHT/$imgHeight);
				}else{
					//On resize en forcant la hauteur de l'image
					$_w =  $WIDTH;
					$_h = round($imgHeight * $WIDTH/$imgWidth);
				}
				$decalW = round(($WIDTH-$_w)/2);
				$decalH = round(($HEIGHT-$_h)/2);
				
				$img2=imagecreatetruecolor($WIDTH,$HEIGHT);
				imagecolorallocate($img2,255,255,255);
				imagefilledrectangle($img2,0,0,$WIDTH,$HEIGHT,imagecolorexact($img2,255,255,255));		
		
				$this->imagecopyresampled($img2,$this->resourceImage, $decalW, $decalH, 0, 0, $_w, $_h, $imgWidth, $imgHeight);            	
            	break;
            default :
                break;
        }
        
		imagedestroy($this->resourceImage);
		$this->resourceImage=$img2;
        return true;
    }	

    /**
     * Méthode de remplissage (outils pot de peinture ^^)
     * $IF->fill(10,10,255,255,255) //on rempli au point 10,10 avec du blanc
	 * @param	int		$x
	 * @param	int		$y
	 * @param	int		$r
	 * @param	int		$g
	 * @param	int		$b
     */
    function fill($x,$y,$r,$g,$b)
    {
        $col=imagecolorallocate($this->resourceImage,$r,$g,$b);
        imagefill($this->resourceImage,$x,$y,$col);
    }
    
    /**
     * Méthode de correction de la luminosité et du contraste
     * $IF->lightContrast(10,50) // L et C de -100 à 100
     * @param	int		$L
	 * @param	int		$C
	 * @return	boolean
     */
    function lightContrast($L=0,$C=0)
    {
        if($this->GD_VERSION==2)
            imagetruecolortopalette($this->resourceImage, true, $this->colorsToWork);

        $numColors = imagecolorstotal($this->resourceImage);
        
        for ($x=0; $x<$numColors; $x++)
        {
            $src_colors     = imagecolorsforindex($this->resourceImage,$x);
            $r=$src_colors["red"];
            $g=$src_colors["green"];
            $b=$src_colors["blue"];
            
            //Contraste:
            $r = round($r + $C / 100 * ($r-127));
            $g = round($g + $C / 100 * ($g-127));
            $b = round($b + $C / 100 * ($b-127));
            
            //Luminosité :
            $r = round($r * ( 1 + $L / 100));
            $g = round($g * ( 1 + $L / 100));
            $b = round($b * ( 1 + $L / 100));
            
            $r = max(0,min(255,$r));
            $g = max(0,min(255,$g));
            $b = max(0,min(255,$b));
            
            imagecolorset($this->resourceImage,$x,$r,$g,$b);
        }
        $this->palettedToTrueColor();
        return true;
    }
    
    /**
     * Génère le négatif de l'image courante
     * @return boolean
     */
    function negative()
    {
        if($this->GD_VERSION==2)
            imagetruecolortopalette($this->resourceImage, true, $this->colorsToWork);

        $numColors = imagecolorstotal($this->resourceImage);
        
        for ($x=0; $x<$numColors; $x++)
        {
            $src_colors     = imagecolorsforindex($this->resourceImage,$x);
            $r              = min(255,255-$src_colors["red"]);
            $g              = min(255,255-$src_colors["green"]);
            $b              = min(255,255-$src_colors["blue"]);
            imagecolorset($this->resourceImage,$x,$r,$g,$b);
        }
        $this->palettedToTrueColor();
        return true;
    }
    
    /**
     * Modification de teinte sur l'image courante
     * whiteness = correction de la luminosité (un décalage peut être introduit lors du changement de teinte)
     * decal R,G,B = décalage de teinte sur les 3 cannaux
     * @param	int		$whiteness
	 * @param	int		$decalR
	 * @param	int		$decalG
	 * @param	int		$decalB
	 * @return	boolean
     */
    function colorize($whiteness, $decalR, $decalG, $decalB)
    {
        if($this->GD_VERSION==2)
            imagetruecolortopalette($this->resourceImage, true, $this->colorsToWork);
            
        $numColors = imagecolorstotal($this->resourceImage);
        
        for ($x=0; $x<$numColors; $x++)
        {
            $src_colors     = imagecolorsforindex($this->resourceImage,$x);
            $luminance        = ($src_colors["red"]+$src_colors["green"]+$src_colors["blue"])/3;
            $r              = max(0,min(255,$src_colors["red"]+$decalR));
            $g              = max(0,min(255,$src_colors["green"]+$decalG));
            $b              = max(0,min(255,$src_colors["blue"]+$decalB));

            $luminance2        = ($r+$g+$b)/3;
            $r                = max(0,min(255,$r*($luminance/$luminance2)+3+$whiteness));
            $g                = max(0,min(255,$g*($luminance/$luminance2)+3+$whiteness));
            $b                = max(0,min(255,$b*($luminance/$luminance2)+3+$whiteness));

            imagecolorset($this->resourceImage,$x,$r,$g,$b);
        }
        $this->palettedToTrueColor();
        return true;
    }
    
    /**
     * Désaturation de l'image courante
     * @param	int		$taux
	 * @return	boolean
     */
    function grayscale($taux=1)
    {
        if($this->GD_VERSION==2)
            imagetruecolortopalette($this->resourceImage, true, $this->colorsToWork);
            
        $numColors = imagecolorstotal($this->resourceImage);
        
        for ($x=0; $x<$numColors; $x++)
        {
            $src_colors     = imagecolorsforindex($this->resourceImage,$x);
            $new_color      = min(255, abs( ( $src_colors["red"] + $src_colors["green"] + $src_colors["blue"] ) / 3 ) + 3 );
            $r                = min(255, abs( $src_colors["red"]   * (1 - $taux)  + $new_color * $taux ) );
            $g                = min(255, abs( $src_colors["green"] * (1 - $taux)  + $new_color * $taux ) );
            $b                = min(255, abs( $src_colors["blue"]  * (1 - $taux)  + $new_color * $taux ) );
            imagecolorset($this->resourceImage,$x,$r,$g,$b);
        }
        $this->palettedToTrueColor();
        return true;
    }
    
    /**
     * Modification de teinte pré-réglé pour réaliser l'effet sepia
     *
     */
    function sepia()
    {
        $this->grayscale();
        return $this->colorize(10, 255, 60, -10);
    }
    
    /**
     * Pixelisation de l'image courante à la taille choisit
     * Par défault la mosaic sera carré $w=$h mais il est possible en précisant 2 paramètre de générer	  des blocs réctangulaires
     * @param	int		$wSize
	 * @param	int		$hSize
	 * @return	boolean
     */
    function mosaic($wSize,$hSize=0)
    {
        if($hSize<=0)
        {
            $hSize=$wSize;
        }

        if($wSize>0 and $hSize>0)
        {
            $info=$this->getImageSize();
            $imgWidth=$info['w'];
            $imgHeight=$info['h'];
            
            $imgWidth2=floor($imgWidth/$wSize);
            $imgHeight2=floor($imgHeight/$hSize);
            
            $img2=$this->imagecreate($imgWidth2,$imgHeight2);
            imagecopyresized($img2,$this->resourceImage,0,0,0,0,$imgWidth2,$imgHeight2,$imgWidth,$imgHeight);
            imagecopyresized($this->resourceImage,$img2,0,0,0,0,$imgWidth,$imgHeight,$imgWidth2-1,$imgHeight2-1);
            imagedestroy($img2);

            return true;
        }
        else
        {
            return false;
        }
    }
    
	/**
	* Application d'un tampon sur l'image courante.
	*
	* le tampon peut être soit un objet image (généré ou non via la classe ImageFilter), soit un fichier 
	* (meilleur méthode pour préserver les couleurs et transparence)
	*
	* x et y détermine le point d'aplication du tampon
	* 
	* 6 mode  de fusion sont disponibles (tous les mode respecte la couche alpha - si présent)
	*    - normal = Pix1 <= Pix2
	*    - multiply = Pix1 <= Pix1*Pix2/255 (si Pix2 = 255 = Blanc pas de changement)
	*    - add = Pix1 <= Pix1+Pix2
	*    - difference = Pix1 <= Abs(Pix1-Pix2)
	*    - lighten = Pix1 <= max(Pix1,Pix2)
	*    - darken = Pix1 <= min(Pix1,Pix2)
	* @param	string	$img
	* @param	int		$x
	* @param	int		$y
	* @param	string	$mode
	* @return	boolean
	*/
    function stamp($img,$x,$y,$mode='normal')
    {
        $deleteRes=false;
        if(!is_resource($img))
        {
            $img=$this->loadImageFile($img);
            $deleteRes=true;
        }
        $this->palettedToTrueColor();
        $info=$this->getImageSize($img);
        
        if($mode=='normal')
        {
			
			//$image = imagecreatefrompng(UPLOAD_MEDIA_BO.$file_resized);
			$image = imagecreatefromgif($img);
			
			$newImage = imagecreatetruecolor($info['w'], $info['h']);
			
			//$background = imagecolorallocate($newImage,$r,$g,$b);
			//$background = imagecolorallocate($newImage,149,10,213);
			
			$background = imagecolorallocate($newImage,148,10,212);
			//$background = imagecolorallocate($newImage,0,0,0);
			
			
			//imagefilledrectangle($image,0,0,60,145,imagecolorexact($image,255,255,255));
			//$image2 = imagecreatefrompng(UPLOAD_MEDIA_BO.$file_resized);
			imagecopymerge($newImage,$newImage,0,0,0,0,$info['w'], $info['h'],256);
			//imagecopy($newImage,$newImage,0,0,0,0,60,145);
			
			
			
			//$background = imagecolorallocate($newImage,0,0,0);
			//imagefill($newImage, 0, 0, $background);
			
			
			//imagefill($newImage, 0, 0, $couleur);
			
			//imagecopyresized($newImage, $image, 0, 0, 0, 0, $info['w'], $info['h'],$info['w'], $info['h']);
			imagecopyresampled($newImage, $image, 0, 0, 0, 0, $info['w'], $info['h'], $info['w'], $info['h']);
			
			
			
			imagecolortransparent($newImage, $background );
			
			imagecopymerge($newImage,$newImage,0,0,0,0,$info['w'], $info['h'],100);
			//imageline($newImage,4, 0, 4,145, $background);
			
			
			
			imagetruecolortopalette($newImage,true, 256);
			imageinterlace($newImage);
			
			//imagepng($newImage,UPLOAD_MEDIA_BO.$file_resized);
			//imagegif($newImage,UPLOAD_MEDIA_BO.$file_resized);
			//imagedestroy($newImage);  
						
           // imagecopyresampled($this->resourceImage,$img,$x,$y,0,0,$info['w'],$info['h'],$info['w'],$info['h']);
        }
        else
        {
            $infOrg=$this->getImageSize($this->resourceImage);
            $img2=$this->imagecreate($infOrg['w'],$infOrg['h']);
            imagecopy($img2,$this->resourceImage,0,0,0,0,$infOrg['w'],$infOrg['h']);

            switch($mode)
            {
                case 'multiply':
                    for($px=0;$px<$info['w'];$px++)
                    {
                        for($py=0;$py<$info['h'];$py++)
                        {
                            $rgb1=imagecolorat($this->resourceImage,$px+$x,$py+$y);
                            $rgb1=imagecolorsforindex($this->resourceImage,$rgb1);
                            $rgb2=imagecolorat($img,$px,$py);
                            $rgb2=imagecolorsforindex($img,$rgb2);
                                
                            $r=min(255,max(0,$rgb1['red']   * max($rgb2['red']   , 2*$rgb2['alpha']) /255 ));
                            $g=min(255,max(0,$rgb1['green'] * max($rgb2['green'] , 2*$rgb2['alpha']) /255 ));
                            $b=min(255,max(0,$rgb1['blue']  * max($rgb2['blue']  , 2*$rgb2['alpha']) /255 ));
                            
                            $cols[$r][$g][$b]=imagecolorallocate($img2,$r,$g,$b);
                            imagesetpixel($img2,$px+$x,$py+$y,$cols[$r][$g][$b]);
                        }
                    }
                    break;
                    
                case 'add':
                    for($px=0;$px<$info['w'];$px++)
                    {
                        for($py=0;$py<$info['h'];$py++)
                        {
                            $rgb1=imagecolorat($this->resourceImage,$px+$x,$py+$y);
                            $rgb1=imagecolorsforindex($this->resourceImage,$rgb1);
                            $rgb2=imagecolorat($img,$px,$py);
                            $rgb2=imagecolorsforindex($img,$rgb2);
                                
                            $r=min(255,max(0,$rgb1['red']   + $rgb2['red']   * (127-$rgb2['alpha'])/127  ));
                            $g=min(255,max(0,$rgb1['green'] + $rgb2['green'] * (127-$rgb2['alpha'])/127  ));
                            $b=min(255,max(0,$rgb1['blue']  + $rgb2['blue']  * (127-$rgb2['alpha'])/127  ));
                            
                            $cols[$r][$g][$b]=imagecolorallocate($img2,$r,$g,$b);
                            imagesetpixel($img2,$px+$x,$py+$y,$cols[$r][$g][$b]);
                        }
                    }
                    break;
    
                case 'difference':
                    for($px=0;$px<$info['w'];$px++)
                    {
                        for($py=0;$py<$info['h'];$py++)
                        {
                            $rgb1=imagecolorat($this->resourceImage,$px+$x,$py+$y);
                            $rgb1=imagecolorsforindex($this->resourceImage,$rgb1);
                            $rgb2=imagecolorat($img,$px,$py);
                            $rgb2=imagecolorsforindex($img,$rgb2);
                                
                            $r=min(255,max(0, abs($rgb1['red']   - $rgb2['red']   * (127-$rgb2['alpha'])/127 ) ));
                            $g=min(255,max(0, abs($rgb1['green'] - $rgb2['green'] * (127-$rgb2['alpha'])/127 ) ));
                            $b=min(255,max(0, abs($rgb1['blue']  - $rgb2['blue']  * (127-$rgb2['alpha'])/127 ) ));
                            
                            $cols[$r][$g][$b]=imagecolorallocate($img2,$r,$g,$b);
                            imagesetpixel($img2,$px+$x,$py+$y,$cols[$r][$g][$b]);
                        }
                    }
                    break;
                    
                case 'lighten':
                    for($px=0;$px<$info['w'];$px++)
                    {
                        for($py=0;$py<$info['h'];$py++)
                        {
                            $rgb1=imagecolorat($this->resourceImage,$px+$x,$py+$y);
                            $rgb1=imagecolorsforindex($this->resourceImage,$rgb1);
                            $rgb2=imagecolorat($img,$px,$py);
                            $rgb2=imagecolorsforindex($img,$rgb2);
                                
                            $r=max($rgb1['red'],$rgb2['red']);
                            $g=max($rgb1['green'],$rgb2['green']);
                            $b=max($rgb1['blue'],$rgb2['blue']);
                            
                            $cols[$r][$g][$b]=imagecolorallocate($img2,$r,$g,$b);
                            imagesetpixel($img2,$px+$x,$py+$y,$cols[$r][$g][$b]);
                        }
                    }
                    break;
                    
                case 'darken':
                    for($px=0;$px<$info['w'];$px++)
                    {
                        for($py=0;$py<$info['h'];$py++)
                        {
                            $rgb1=imagecolorat($this->resourceImage,$px+$x,$py+$y);
                            $rgb1=imagecolorsforindex($this->resourceImage,$rgb1);
                            $rgb2=imagecolorat($img,$px,$py);
                            $rgb2=imagecolorsforindex($img,$rgb2);
                                
                            $r=min($rgb1['red'],$rgb2['red']);
                            $g=min($rgb1['green'],$rgb2['green']);
                            $b=min($rgb1['blue'],$rgb2['blue']);
                            
                            $cols[$r][$g][$b]=imagecolorallocate($img2,$r,$g,$b);
                            imagesetpixel($img2,$px+$x,$py+$y,$cols[$r][$g][$b]);
                        }
                    }
                    break;
            }
            imagedestroy($this->resourceImage); //on supprime l'image d'origine
            $this->resourceImage=$img2; // et on la remplace par la version modifié
        }
        if($deleteRes)
        {
            //on supprime l'image temporaire
            imagedestroy($img);
        }
        return true;
    }
    
    /**
     * On repasse en mode couleur vrai (24bits)
     * !!! peut entrainer la suppression de la couche alpha
     *
     */
    function palettedToTrueColor()
    {
        $info=$this->getImageSize();
        $img2=$this->imagecreate($info['w'],$info['h']);
        $this->imagecopyresampled($img2,$this->resourceImage,0,0,0,0,$info['w'],$info['h'],$info['w'],$info['h']);
        imagedestroy($this->resourceImage);
        $this->resourceImage=$img2;
    }
    
    /**
     * Application d'un filtre 3x3
     * en fonction des paramètres il est alors possible de réaliser des passes hauts, passes bas,	    etc...
	 * @param int $matrix
     */
    function applyMatrix3x3($matrix)
    {
        $info=$this->getImageSize();
        $img2=$this->imagecreate($info['w']-2,$info['h']-2);
        
        for($x=1;$x<$info['w']-1;$x++)
        {
            for($y=1;$y<$info['h']-1;$y++)
            {
                $r=$g=$b=0;
    
                for($Mx=0;$Mx<3;$Mx++)
                {
                    for($My=0;$My<3;$My++)
                    {
                        $rgb=imagecolorat($this->resourceImage,$x-1+$Mx,$y-1+$My);
                    
                        $r += $matrix[$Mx][$My]*(($rgb >> 16) & 0xFF); //r
                        $g += $matrix[$Mx][$My]*(($rgb >>  8) & 0xFF); //g
                        $b += $matrix[$Mx][$My]*(($rgb      ) & 0xFF); //b
                    }
                }
                $r=min(255,max(0,$r));
                $g=min(255,max(0,$g));
                $b=min(255,max(0,$b));
                
                if(!isset($cols[$r][$g][$b]))
                {
                    //on minimise les allocation de couleur
                    $cols[$r][$g][$b]=imagecolorallocate($img2,$r,$g,$b);
                }
                imagesetpixel($img2,$x-1,$y-1,$cols[$r][$g][$b]);
            }
        }
        imagedestroy($this->resourceImage);
        $this->resourceImage=$img2;
    }
    
    /**
     * Utilisation du filtre matriciel pour faire un floutage (passe bas)
     * @param	int		$k
     */
    function Blur($k=1)
    {
        $k*=1/9;
        return $this->applyMatrix3x3(array(
                                        array($k,$k,$k),
                                        array($k,1-9*$k,$k),
                                        array($k,$k,$k)
                                    ));
    }

    /**
     * Utilisation du filtre matriciel pour une detection de contour
     * @param	int		$k
     */
    function Edge($k=1)
    {
        return $this->applyMatrix3x3(array(
                                        array(0,-$k,0),
                                        array(-$k,1-$k,$k),
                                        array(0,$k,0)
                                    ));
    }

    /**
     * Méthode basé sur le filtre matriciel optimisé pour réaliser une accentuation de détail (passe haut)
     * @param	int		$k
     */
    function Sharpen($k=1)
    {
        $info=$this->getImageSize();
        
        $img2=$this->imagecreate($info['w']-2,$info['h']-2);
        for($x=1;$x<$info['w']-1;$x++)
        {
            for($y=1;$y<$info['h']-1;$y++)
            {
                $rgb[1][0]=imagecolorat($this->resourceImage,$x,$y-1);
                $rgb[0][1]=imagecolorat($this->resourceImage,$x-1,$y);
                $rgb[1][1]=imagecolorat($this->resourceImage,$x,$y);
                $rgb[2][1]=imagecolorat($this->resourceImage,$x+1,$y);
                $rgb[1][2]=imagecolorat($this->resourceImage,$x,$y+1);

                $r =      -$k *(($rgb[1][0] >> 16) & 0xFF) +
                         -$k *(($rgb[0][1] >> 16) & 0xFF) +
                    (1+4*$k) *(($rgb[1][1] >> 16) & 0xFF) +
                         -$k *(($rgb[2][1] >> 16) & 0xFF) +
                         -$k *(($rgb[1][2] >> 16) & 0xFF) ;

                $g =      -$k *(($rgb[1][0] >> 8) & 0xFF) +
                         -$k *(($rgb[0][1] >> 8) & 0xFF) +
                    (1+4*$k) *(($rgb[1][1] >> 8) & 0xFF) +
                         -$k *(($rgb[2][1] >> 8) & 0xFF) +
                         -$k *(($rgb[1][2] >> 8) & 0xFF) ;

                $b =      -$k *($rgb[1][0] & 0xFF) +
                         -$k *($rgb[0][1] & 0xFF) +
                    (1+4*$k) *($rgb[1][1] & 0xFF) +
                         -$k *($rgb[2][1] & 0xFF) +
                         -$k *($rgb[1][2] & 0xFF) ;

                $r=min(255,max(0,$r));
                $g=min(255,max(0,$g));
                $b=min(255,max(0,$b));

                if(!$cols[$r][$g][$b])
                {
                    //on minimise les allocation de couleur
                    $cols[$r][$g][$b]=imagecolorallocate($img2,$r,$g,$b);
                }
                imagesetpixel($img2,$x-1,$y-1,$cols[$r][$g][$b]);
            }
        }
        imagedestroy($this->resourceImage);
        $this->resourceImage=$img2;
        return true;
    }
    
    /**
     * Opération de mirroir sur l'image (horizontale ou vertical)
     * type de flip h > horizontal, v > vertical
     * @param	string	$flip
	 * @return	boolean
     */
    function flip($flip='h')
    {
        $info = $this->getImageSize();
        $img2 = $this->imagecreate($info['w'],$info['h']);
        
        if ( $flip == 'v' )
        {
            for ( $i=0; $i<$info['h']; $i++ ) imagecopy($img2,$this->resourceImage,0,$info['h']-1-$i,0,$i,$info['w'],1);
        }
        elseif ( $flip == 'h' )
        {
            for ( $i=0; $i<$info['w']; $i++ ) imagecopy($img2,$this->resourceImage,$info['w']-1-$i,0,$i,0,1,$info['h']);
        }
        else return false;
        
        imagedestroy($this->resourceImage);
        $this->resourceImage=$img2;
        
        return true;
    }
    
    /**
     * Opération rotation
     * angle de rotation (90°, 180°, 270°)
     * @param int $angle
	 * @return boolean
     */
    function rotation($angle=90)
    {
        $this->palettedToTrueColor();
        
        $info = $this->getImageSize();
        $img2 = $angle == 180 ? $this->imagecreate($info['w'],$info['h']) : $this->imagecreate($info['h'],$info['w']);
        
        switch ( $angle )
        {
            case 180 :
                for ( $j=0; $j<$info['h']; $j++)
                {
                    for ( $i=0; $i<$info['w']; $i++ )
                    {
                        $c = imagecolorat($this->resourceImage,$i,$j);
                        imagesetpixel($img2,$info['w']-$i-1,$info['h']-$j-1,$c);
                    }
                }                
            break;
            
            case 270 :
                for ( $j=0; $j<$info['h']; $j++ )
                {
                    for ( $i = $info['w']; $i>=0; $i-- )
                    {
                        $c = imagecolorat($this->resourceImage,$i,$j);
                        imagesetpixel($img2,$j,$info['w']-$i-1,$c);
                    }
                }
            break;
            
            case 90 :
                for ( $i = 0; $i<$info['w']; $i++ )
                {
                    for ( $j=0; $j<$info['h']; $j++ )
                    {
                        $c  = imagecolorat($this->resourceImage,$i,$j);
                        imagesetpixel($img2,$info['h']-$j-1,$i,$c);
                    }
                }
            break;
            
            default:
                return false;
            break;
        }
        
        imagedestroy($this->resourceImage);
        $this->resourceImage=$img2;
        
        return true;
    }    
    
    /**
     * Méthode permettant l'execution automatique d'une série de méthode.
     *
     * $commands=array(
     *     array('filter'=>'loadImage','params'=>array('test.png')),
     *    array('filter'=>'resize','params'=>array('200%','','force',true)),
     *    array('filter'=>'sepia','params'=>array()),
     *    array('filter'=>'mosaic','params'=>array(3)),
     *    array('filter'=>'flip','params'=>array('h')),
     *    array('filter'=>'stamp','params'=>array('testStamp.png',0,0)),
     *    array('filter'=>'rotation','params'=>array('90')),
     *    array('filter'=>'stamp','params'=>array('test.png',0,0,'multiply')),
     *    array('filter'=>'lightContrast','params'=>array(0,100)),
     *    array('filter'=>'output','params'=>array('PNG'))
     * );
     *
     * $IF->batchFilter($commands);
     * @param	array	$commands
	 * @return	boolean
     */
    function batchFilter($commands)
    {
        foreach($commands as $command)
        {
            if(method_exists($this,$command['filter']))
            {
                 call_user_method_array($command['filter'],$this,$command['params']);
            }
        }
        return true;
    }
    
    /**
     * Méthode de redimesionnement selon la version de librairie GD (1 ou 2)
     * GD 1.x ne gérant pas les images 24bits, elle ne fait pas de ré-échantillonnage sur les redimensionnemnt
	 * @param int $out
	 * @param int $in
	 * @param int $dstX
	 * @param int $dstY
	 * @param int $srcX
	 * @param int $srcY
	 * @param int $dstW
	 * @param int $dstH
	 * @param int $srcW
	 * @param int $srcH
     */
    function imagecopyresampled($out, $in, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH)
    {
        if($this->GD_VERSION==2)
            return imagecopyresampled($out, $in, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
        else
            return imagecopyresized($out, $in, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
    }

    /**
     * Méthode de création d'image  selon la version de librairie GD (1 ou 2)
     * GD 1.x ne gère pas les images en 24bits on crée alors une image 256 couleurs
     * @param int $w
	 * @param int $h
     */    
    function imagecreate($w,$h)
    {
		if($this->GD_VERSION==2) {
            $img = imagecreatetruecolor($w,$h);
            imagefill($img,0,0,imagecolorallocate ($img, 255, 255, 255));
            return $img;
		}else{
            return imagecreate($w,$h);
		}
    }	
    
    
    function ratio_force($width_dest,$height_dest)
    {       
    	$info=$this->getImageSize();
        $width_src=$info['w'];
        $height_src=$info['h'];            
        
        $ratio_dest = $width_dest / $height_dest;
		$ratio_src = $width_src / $height_src;
        
		if($ratio_src < $ratio_dest){
			//On resize en forcant la hauteur de l'image
			$height_tmp =  $height_dest; 
			$width_tmp = round($width_src * $height_dest/$height_src);
		}else{
			//On resize en forcant la hauteur de l'image
			$width_tmp =  $width_dest;
			$height_tmp = round($height_src * $width_tmp/$width_src);
		}
		
        $imgfinal=imagecreatetruecolor($width_dest,$height_dest);
		imagecolorallocate($imgfinal,255,255,255);
		imagefilledrectangle($imgfinal,0,0,$width_dest,$height_dest,imagecolorexact($imgfinal,255,255,255));		

		imagecopyresampled($imgfinal,$this->resourceImage, round(($width_dest-$width_tmp)/2), round(($height_dest-$height_tmp)/2), 0, 0, $width_tmp, $height_tmp, $width_src, $height_src);
		
		imagedestroy($this->resourceImage);
        $this->resourceImage=$imgfinal;
        return true;		
    }
    
}
?>