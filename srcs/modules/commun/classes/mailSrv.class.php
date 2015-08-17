<?php
/**
* @package		Reghalal
* @subpackage	commun
* @version		1
* @author		NEOV
*/


/**
* Fonctions utilitaires pour la gestion des mail
* @package Reghalal
* @subpackage commun
*/
class MailSrv {
	
	/**
	* Fonction d'envoye de mail générique
	* @param string $_zFromMail		Adresse mail de l'envoyeur
	* @param string $_zFromNom		Nom de l'envoyeur
	* @param string $_zToMail		Adresse mail du destinataire
	* @param string $_zToNom		Nom du destinataire
	* @param string $_zSujet		Sujet du Mail
	* @param string $_tplCorps		Le templates du mail
	* @param string $_zSelectorAct	Une chaîne designant le sélecteur du template du mail 
	* @param array  $_tParamCorps	Un tableau qui contient les parametres pour le templates du mail
	* @param boolean $_bHtml				Indique si le mail est Htlm ou pas
	* @param string $_zType					Type du mail
	* @param array $_tzPathAttachements		tableau des path des fichiers en piece jointe
	* @param array $_tMailCcs				tableau des adresses mail en copie
	* @param array $_tzMailBcc				tableau des adresses mail en copie caché
	* @return object $jMailer
	*/
	static function envoiEmail	($_zFromMail=NULL, $_zFromNom=NULL, $_zToMail=NULL, $_zToNom=NULL, $_zSujet=NULL, $_tplCorps='', $_zSelectorAct='', $_tParamCorps=NULL, $_bHtml=true, $_zType='', $_tzPathAttachements = array (), $_tMailCcs= array(), $_tzMailBcc=array()) {

		
		$jMailer = new jMailer() ;
		$jMailer->IsHTML($_bHtml) ;
		
		// On prepare le template pour le mail
		$tplMail = new jTpl();	
			
		if($_zSelectorAct!=''){
			$tplMail->assignZone('corpMail', $_tplCorps, $_tParamCorps) ;
			$contenuMail = $tplMail->fetch($_zSelectorAct) ; 
		} else {
			$contenuMail = $_tplCorps;
		}

		$jMailer->From		= $_zFromMail ;
		$jMailer->FromName	= $_zFromNom ;
		
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
				if ($zMailCc) {
					$jMailer->AddCC($zMailCc) ;			
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
		

		$jMailer->Subject	= $_zSujet;
		
		$jMailer->Body		= $contenuMail;
//jLog::dump($jMailer->Body); die;
//print_r($jMailer); die;
		return $jMailer->send() ;
	}
}
?>