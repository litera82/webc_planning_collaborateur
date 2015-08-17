<table width="728" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td width="20" rowspan="6"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="680" height="9" colspan="7"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_01_01_01.jpg" width="680" height="9" alt="" /></td>
        <td width="28" rowspan="6"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>

    </tr>
    <tr>
        <td width="420" height="17" bgcolor="#000000" colspan="2"></td>
        <td width="85" height="17" bgcolor="#000000"></td>
        <td width="16" height="17" bgcolor="#000000"></td>
        <td width="131" height="17" colspan="2" bgcolor="#000000"></td>
        <td width="28" height="17"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_01_01_02_05.jpg" width="28" height="17" alt="" /></td>
    </tr>
    <tr>
    	<td width="30" height="87" bgcolor="#000000" valign="top"><img width="30" height="87" border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="620" height="87" colspan="4" bgcolor="#000000"><a href="#" title="Format2+ : Plus de services, plus de resultats"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/logo.gif" width="278" height="74" alt="" /></a></td>
        <td width="30" height="87" colspan="2" bgcolor="#000000" valign="top"><img width="30" height="87" border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
    </tr>
    <tr>
        <td width="30" rowspan="2" bgcolor="#625d59" valign="top"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_02_01_01.jpg" width="30" height="419" alt="" /></td>
        <td width="620" height="23" colspan="4" valign="top" bgcolor="#00FF00"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_02_01_02_01.jpg" width="620" height="23" alt="" /></td>
        <td width="30" colspan="2" rowspan="2" bgcolor="#625d59" valign="top"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/Mails_03_02_01_03.jpg" width="30" height="419" alt="" /></td>
    </tr>

    <tr>
        <td width="620" height="418" colspan="4" valign="top" bgcolor="#FFFFFF">
        	<p style="padding:15px 40px; margin:0; text-align:justify;">
                <font face="Verdana, Geneva, sans-serif" size="2">
					{assign $zCivilite = ""}
					{assign $zCiviliteProf = ""}
					{if $oClient->client_iCivilite == CIVILITE_HOMME}{assign $zCivilite = "Mr"}{/if}
					{if $oClient->client_iCivilite == CIVILITE_MADEMOISELLE}{assign $zCivilite = "Mlle"}{/if}
					{if $oClient->client_iCivilite == CIVILITE_FEMME}{assign $zCivilite = "Mme"}{/if}

					{if $oUtilisateur->utilisateur_iCivilite == CIVILITE_FEMME}
						{assign $zCiviliteProf = "Madame"}
					{else}
						{assign $zCiviliteProf = "Monsieur"}
					{/if} 

					Bonjour <br /><br /><br />
					
					Pour votre information, {$zCivilite} {$oClient->client_zNom} a fait une reservation pour sont test de debut de stage{if isset($modifTel) && $modifTel == 1} et son numero d'appel pour le test à changer{/if}.<br /><br /><br />
					Le test oral de niveau est programmé pour le {$zDate} à {$zHeure} avec {$zCiviliteProf} {$oUtilisateur->utilisateur_zPrenom} {$oUtilisateur->utilisateur_zNom},{if isset ($zTelResa) && $zTelResa != ''} son {if isset($modifTel) && $modifTel == 1}nouveau{/if} numero d'appel est {$zTelResa}<br />{/if}
                </font>
				<br /><br /><br />
                <font face="Verdana, Geneva, sans-serif" size="2">
					Les Informations concernant le projet :<br /><br />
					Nom : {$oClient->client_zNom}<br />
					Prénom : {$oClient->client_zPrenom}<br />
					{if isset ($oClient->client_iNumIndividu) && $oClient->client_iNumIndividu != ''}
					Numero individuel : {$oClient->client_iNumIndividu}<br />
					{/if}
					{if isset ($oClient->client_zTel) && $oClient->client_zTel != ''}
					Téléphone fixe : {$oClient->client_zTel}<br />
					{/if}
					{if isset ($oClient->client_zPortable) && $oClient->client_zPortable != ''}
					Téléphone portable : {$oClient->client_zPortable}<br />
					{/if}
					{if isset ($oClient->client_zMail) && $oClient->client_zMail != ''}
					eMail : {$oClient->client_zMail}<br />
					{/if}
					{if isset ($oClient->client_zFonction) && $oClient->client_zFonction != ''}
					Fonction : {$oClient->client_zFonction}<br />
					{/if}
					{if isset ($oClient->client_zRue) || isset ($oClient->client_zVille) || isset ($oClient->client_zCP)}
					Adresse : {$oClient->client_zRue} {$oClient->client_zVille} {$oClient->client_zCP}{if isset($oPays->pays_zNom) && $oPays->pays_zNom !=''} - {$oPays->pays_zNom}{/if}<br />
					{/if}
					{if isset ($oClient->client_iSociete) && $oClient->client_iSociete > 0 && isset($oSociete->societe_id) && $oSociete->societe_id > 0}
					Catégories : {$oSociete->societe_zNom}<br />
					{/if}
                </font>
				<br /><br /><br />
				<font face="Verdana, Geneva, sans-serif" size="2">
					Ceci est un mail envoyé automatiquement depuis l'application autoplannification suite à une réservation d'un projet.
                </font>
				<br /><br /><br />
				<font face="Verdana, Geneva, sans-serif" size="2">
					Cordialement,<br />
					Webcalendar
                </font>
            </p>
		</td>
    </tr>
    <tr>
        <td width="680" height="64" colspan="7" bgcolor="#625d59" align="center" valign="middle"><font face="Verdana, Geneva, sans-serif" size="1">Ne pas r&eacute;pondre &agrave; ce courrier &eacute;lectronique</td>
    </tr>
    <tr>
        <td width="20" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="30" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="390" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="85" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="16" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="129" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="2" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>

        <td width="28" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
        <td width="28" height="1"><img border="0" style="display:block;" src="http://{$zUrlToSite}design/front/images/design/mail/spacer.gif" alt="" /></td>
    </tr>
</table>

