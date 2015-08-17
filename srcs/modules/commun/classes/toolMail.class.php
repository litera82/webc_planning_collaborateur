<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/

/**
* Fonctions utilitaires pour les mails
*
* @package		Reghalal
* @subpackage	commun
*/
class ToolMail {
	
	/**
	 * Fonction permettant de verifier une adresse mail (syntaxique + existence).                   
	 * Cette fonction va verifier l'adresse mail fournie en argument       
	 * de plusieurs manières. Premi?rement a l'aide d'expressions régulière
	 * afin de vérifier la syntaxe de celle-ci.                            
	 * ensuite une verification mx afin de verifier le domaine             
	 * et puis une connection sur le serveur afin de valider l'user        
	 *                                                                     
	 * @param	string 		$_zMail 		adresse mail à verifier.   
	 * @param	boolean  	$_bMxActive		active une verification mx
	 * @return	array 	$tReturn[0]			boolean TRUE si mail existe $tReturn[1] commentaire
	 *                                                                     
	*/
	static function mailBoxExist($_zMail, $_bMxActive=false) {
		global $gJConfig ;
		//jClasses::inc ('commun~emailValidation') ;
		
		//$zMxActive = MX_ACTIVE ;

		if(!defined ("DEFAULT_MAIL_EXPEDITEUR")){
			throw new Exception("La constante DEFAULT_MAIL_EXPEDITEUR doit ?tre d?finie pour ?tre en mesure d'utiliser la m?thode mailBoxExist");
		}

		$bResultat=true;
		
		if ($_bMxActive){

			$oEmailValidator = new emailValidation () ;
			// $oEmailValidator->timeout		= 15 ;
			$oEmailValidator->timeout			= 10 ;
			$oEmailValidator->data_timeout		= 0 ;
			$oEmailValidator->localuser			= substr(DEFAULT_MAIL_EXPEDITEUR, 0, strpos(DEFAULT_MAIL_EXPEDITEUR, '@'));
			$oEmailValidator->localhost			= substr(DEFAULT_MAIL_EXPEDITEUR, strpos(DEFAULT_MAIL_EXPEDITEUR, '@')+1);
			$oEmailValidator->debug				= 0 ;
			$oEmailValidator->html_debug		= 0 ;
			$oEmailValidator->exclude_address	= "" ;
			$bResultat = $oEmailValidator->ValidateEmailBox ($_zMail) ;
			
			if($bResultat==0){
				return false;
			}else{
				return true; //On fait un return true même si le résultat est -1 (timeout)
			}
		
			return $bResultat ;
		}else{
			return true ;
		}
	
	}
	
	/**
	 * Vérifie l'adresse email (syntaxique)
	 *
	 * @param	string		$_zMail		 adresse mail à verifier
	 * @return	boolean
	 */
	static function checkMailSyntaxe ($_zMail=''){
		//jClasses::inc ('commun~emailValidation') ;
		$oEmailValidator = new emailValidation () ;
		return $oEmailValidator->ValidateEmailAddress ($_zMail) ;
	}
	
	
	/**
	* Fonction d'envoye de mail générique
	* 
	* @param string 	$_zFromMail				Adresse mail de l'envoyeur
	* @param string 	$_zFromNom				Nom de l'envoyeur
	* @param string 	$_zToMail				Adresse mail du destinataire
	* @param string 	$_zToNom				Nom du destinataire
	* @param string 	$_zSujet				Sujet du Mail
	* @param string 	$_tplCorps				Le templates du mail
	* @param string 	$_zSelectorAct			Une chaine designant le s?lecteur du template du mail 
	* @param array  	$_tParamCorps			Un tableau qui contient les parametres pour le templates du mail
	* @param boolean 	$_bHtml				Indique si le mail est Htlm ou pas
	* @param string 	$_zType				Type du mail
	* @param array 		$_tzPathAttachements	tableau des path des fichiers en piece jointe
	* @param array 		$_tMailCcs				tableau des adresses mail en copie
	* @param array 		$_tzMailBcc			tableau des adresses mail en copie caché
	* @param string 	$_zSender			sender
	* @return object $jMailer
	*/
	static function envoiEmail	($_zFromMail=NULL, $_zFromNom=NULL, $_zToMail=NULL, $_zToNom=NULL, $_zSujet=NULL, $_tplCorps='', $_zSelectorAct='', $_tParamCorps=NULL, $_bHtml=true, $_zType='', $_tzPathAttachements = array (), $_tMailCcs= array(), $_tzMailBcc=array(), $_zSender='') {

		$jMailer = new jMailer() ;
		$jMailer->IsHTML($_bHtml) ;
		$jMailer->CharSet = "iso-8859-1";
		
		$zCcc = ''; //CONTACT_MAIL_CC ;
		if ($zCcc != ''){
			$jMailer->AddBCC($zCcc) ;	
		}
		if ($_zSender != ''){
			$jMailer->Sender = $_zSender;
		}

		// On prepare le template pour le mail
		$tplMail = new jTpl();	
		
		if($_zSelectorAct!=''){
			$tplMail->assignZone('corpMail', $_tplCorps, $_tParamCorps) ;
			$contenuMail = $tplMail->fetch($_zSelectorAct) ; 
			
		} else {
			foreach($_tParamCorps as $key => $val){
				$tplMail->assign($key, $val);
			}
			
			$contenuMail = $tplMail->fetch($_tplCorps) ;  
		}
		

		$jMailer->From		= utf8_decode($_zFromMail) ;
		$jMailer->FromName	= utf8_decode($_zFromNom) ;
		
		// Si plusieurs adresse
		if (is_array($_zToMail)) {
			foreach ($_zToMail as $zMail) {
				if ($zMail) {
					$jMailer->AddAddress($zMail);
				}
			}
		}elseif ($_zToMail) {
			$jMailer->AddAddress($_zToMail);
		}

		// Fichier en PJ
		if (is_array($_tzPathAttachements)) {
			foreach ($_tzPathAttachements as $zPathAttachement) {
				if (is_file($zPathAttachement)) {
					$jMailer->AddAttachment($zPathAttachement) ;			
				}
			}
		}elseif (is_file($_tzPathAttachements)) {
			$jMailer->AddAttachment($_tzPathAttachements) ;	
		}
		
		// Mail en CC
		if (is_array($_tMailCcs)) {
			foreach ($_tMailCcs as $zMailCc) {
				/*
				if ($zMailCc) {
					$jMailer->AddCC($zMailCc) ;			
				}
				*/
				if ($zMailCc) {
					$jMailer->AddCC($zMailCc['zMailCc'], $zMailCc['zNomCc']);
				}
			}
		}elseif ($_tMailCcs) {
			$jMailer->AddCC($_tMailCcs) ;	
		}
		
		// Mail en Bcc
		if (is_array($_tzMailBcc)) {
			foreach ($_tzMailBcc as $zMailBcc) {
				if ($zMailBcc) {
					$jMailer->AddBCC($zMailBcc) ;
				}
			}
		}elseif ($_tzMailBcc) {
			$jMailer->AddBCC($_tzMailBcc) ;	
		}

		$jMailer->Subject	= utf8_decode($_zSujet);
		$jMailer->Body		= utf8_decode($contenuMail);
		return $jMailer->send() ; 
		
	}
	
	
}

/**
 * emailValidation.php
 *
 * @package		Reghalal
 * @subpackage	commun
 */
class emailValidation
{
	var $email_regular_expression="^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$";
	var $timeout=0;
	var $data_timeout=0;
	var $localhost="";
	var $localuser="";
	var $debug=0;
	var $html_debug=0;
	var $exclude_address="";
	var $getmxrr="GetMXRR";

	var $next_token="";
	
	/**
	* Traitement sur une chaine
	* 
	* @param string $string chaine à manipuler
	* @param string $separator séparateur
	* @return string $string chaine traitée
	*/
	Function Tokenize($string,$separator="")
	{
		if(!strcmp($separator,""))
		{
			$separator=$string;
			$string=$this->next_token;
		}
		for($character=0;$character<strlen($separator);$character++)
		{
			if(GetType($position=strpos($string,$separator[$character]))=="integer")
				$found=(IsSet($found) ? min($found,$position) : $position);
		}
		if(IsSet($found))
		{
			$this->next_token=substr($string,$found+1);
			return(substr($string,0,$found));
		}
		else
		{
			$this->next_token="";
			return($string);
		}
	}
	
	/**
	* Affichage d'un message pour debogage
	* 
	* @param string $message Message à afficher
	* @return void
	*/

	Function OutputDebug($message)
	{
		$message.="\n";
		if($this->html_debug)
			$message=str_replace("\n","<br />\n",HtmlEntities($message));
		echo $message;
		flush();
	}

	/**
	* Renvoie la donnée d'une ligne
	* 
	* @param	string	$connection La chaine à manipuler
	* @return	string	$line La ligne résultante
	*/
	Function GetLine($connection)
	{
		return stream_get_line($connection,1024);
		/*
		for($line="";;)
		{
			if(feof($connection))
				return(0);
			$line.=fgets($connection,100);
			$length=strlen($line);
			if($length>=2
			&& substr($line,$length-2,2)=="\r\n")
			{
				$line=substr($line,0,$length-2);
				if($this->debug)
					$this->OutputDebug("S $line");
				return($line);
			}
		}
		*/
	}
	
	/**
	* Ecriture d'une ligne
	* 
	* @param	string	$connection La chaine à manipuler
	* @param	string	$line La line à insérer
	* @return	int		$return Résultat de fputs($connection,"$line\r\n")
	*/
	Function PutLine($connection,$line)
	{
		if($this->debug)
			$this->OutputDebug("C $line");
		return(fputs($connection,"$line\r\n"));
	}
	
	/**
	* Validation syntaxique d'une adresse mail
	* 
	* @param	string	$email		L'adresse mail à vérifier
	* @return	int		$return		Résultat du test
	*/
	Function ValidateEmailAddress($email)
	{
		return(eregi($this->email_regular_expression,$email)!=0);
	}
	
	/**
	* Validation de l'existence d'une adresse mail (test de l'host)
	* 
	* @param	string	$email		L'adresse mail à vérifier
	* @param	string	&$hosts		Host à vérifier
	* @return	int		$hosts		Résultat du test
	*/
	Function ValidateEmailHost($email,&$hosts)
	{
		if(!$this->ValidateEmailAddress($email))
			return(0);
		$user=$this->Tokenize($email,"@");
		$domain=$this->Tokenize("");
		$hosts=$weights=array();
		$getmxrr=$this->getmxrr;
		if(function_exists($getmxrr)
		&& $getmxrr($domain,$hosts,$weights))
		{
			$mxhosts=array();
			for($host=0;$host<count($hosts);$host++)
				$mxhosts[$weights[$host]]=$hosts[$host];
			KSort($mxhosts);
			for(Reset($mxhosts),$host=0;$host<count($mxhosts);Next($mxhosts),$host++)
				$hosts[$host]=$mxhosts[Key($mxhosts)];
		}
		else
		{
			if(strcmp($ip=@gethostbyname($domain),$domain)
			&& (strlen($this->exclude_address)==0
			|| strcmp(@gethostbyname($this->exclude_address),$ip)))
				$hosts[]=$domain;
		}
		return(count($hosts)!=0);
	}

	/**
	* Vérification de chaque ligne de données
	* 
	* @param	string	$connection		Données
	* @param	string	$code Code
	* @return	int		$return			Résultat du test
	*/
	Function VerifyResultLines($connection,$code)
	{
		while(($line=$this->GetLine($connection)))
		{
			if(!strcmp($this->Tokenize($line," "),$code))
				return(1);
			if(strcmp($this->Tokenize($line,"-"),$code))
				return(0);
		}
		return(-1);
	}
	
	/**
	* Validation définitive d'une adresse mail (syntaxique + existence)
	* 
	* @param	string	$email		E-mail à vérifier
	* @return	int		$return		Résultat du test
	*/
	Function ValidateEmailBox($email)
	{
		if(!$this->ValidateEmailHost($email,$hosts))
			return(0);
		if(!strcmp($localhost=$this->localhost,"")
		&& !strcmp($localhost=getenv("SERVER_NAME"),"")
		&& !strcmp($localhost=getenv("HOST"),""))
		   $localhost="localhost";
		if(!strcmp($localuser=$this->localuser,"")
		&& !strcmp($localuser=getenv("USERNAME"),"")
		&& !strcmp($localuser=getenv("USER"),""))
		   $localuser="root";
		for($host=0;$host<count($hosts);$host++)
		{
			$domain=$hosts[$host];
			if(ereg('^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$',$domain))
				$ip=$domain;
			else
			{
				if($this->debug)
					$this->OutputDebug("Resolving host name \"".$hosts[$host]."\"...");
				if(!strcmp($ip=@gethostbyname($domain),$domain))
				{
					if($this->debug)
						$this->OutputDebug("Could not resolve host name \"".$hosts[$host]."\".");
						continue;
				}
			}
			if(strlen($this->exclude_address)
			&& !strcmp(@gethostbyname($this->exclude_address),$ip))
			{	
				if($this->debug)
				$this->OutputDebug("Host address of \"".$hosts[$host]."\" is the exclude address");
				continue;
			}
			if($this->debug)
				$this->OutputDebug("Connecting to host address \"".$ip."\"...");
			if(($connection=($this->timeout ? @fsockopen($ip,25,$errno,$error,$this->timeout) : @fsockopen($ip,25))))
			{
				$timeout=($this->data_timeout ? $this->data_timeout : $this->timeout);
				if($timeout
				&& function_exists("socket_set_timeout"))
					socket_set_timeout($connection,$timeout,0);
				if($this->debug)
					$this->OutputDebug("Connected.");
				if($this->VerifyResultLines($connection,"220")>0
				&& $this->PutLine($connection,"HELO $localhost")
				&& $this->VerifyResultLines($connection,"250")>0
				&& $this->PutLine($connection,"MAIL FROM: <$localuser@$localhost>")
				&& $this->VerifyResultLines($connection,"250")>0
				&& $this->PutLine($connection,"RCPT TO: <$email>")
				&& ($result=$this->VerifyResultLines($connection,"250"))>=0)
				{
					if($result
					&& $this->PutLine($connection,"DATA"))
						$result=($this->VerifyResultLines($connection,"354")!=0);
					if($this->debug)
						$this->OutputDebug("This host states that the address is ".($result ? "" : "not ")."valid.");
					fclose($connection);
					if($this->debug)
						$this->OutputDebug("Disconnected.");
					return($result);
				}
				if($this->debug)
					$this->OutputDebug("Unable to validate the address with this host.");
				fclose($connection);
				if($this->debug)
					$this->OutputDebug("Disconnected.");
			}
			else
			{
				if($this->debug)
					$this->OutputDebug("Failed.");
			}
		}
		return(-1);
	}
};
?>