<?php
/**
 * @package		Reghalal
 * @subpackage	commun
 * @version		1
 * @author      NEOV
 */

/**
* Fonctions utilitaires pour les fichiers
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolFichier {

	/**
	* 
	* Retourne la taille d'un fichier
	* 	en Ko, Mo, Go
	* 
	* @param  string	$_zNomFichier	nom du fichier 
	* @return string  
	*/
	static function getTailleFichier($_zNomFichier){
		$iTaille=filesize($_zNomFichier);
		if ($iTaille >= 1073741824) 
		{$zTaille = round($iTaille / 1073741824 * 100) / 100 . " Go";}
		elseif ($iTaille >= 1048576) 
		{$zTaille = round($iTaille / 1048576 * 100) / 100 . " Mo";}
		elseif ($iTaille >= 1024) 
		{$zTaille = round($iTaille / 1024 * 100) / 100 . " Ko";}
		else 
		{$zTaille = $iTaille . " o";} 

		if($iTaille==0) {$zTaille="-";}
		return str_replace('.', ',', $zTaille);
	}
	
	/**
	 * Retourne l'extension d'un fichier
	 *
	 * @param 	string	$_zNomFichier
	 * @return 	string	
	 */
	static function getExtensionFichier($_zNomFichier) {
		$zExtension=substr(strrchr($_zNomFichier, "."), 1);
		if($zExtension == false) {
        	$zExtension == '';
		}
   		return $zExtension;
	}
	/**
    * juste pour le debugage
	* @param mixed $_xVarMix
    */
    public static function writeLog ($_xVarMix)
    {
        $iLogFile = fopen (JELIX_APP_VAR_PATH . "log/cronlog.log", "a+") ;
        
        if ($iLogFile != 0)
        {            
            fputs ($iLogFile, "-- Ouverture du fichier log --" . "\n") ;
        }
       
        fputs ($iLogFile, print_r ($_xVarMix, TRUE) . "\n") ;
        
        if ($iLogFile != 0)
        {            
            fputs ($iLogFile, "-- Fermeture du fichier log --" . "\n") ; 
            @fflush ($iLogFile) ;
            @fclose ($iLogFile) ;
        }
    }

}

?>