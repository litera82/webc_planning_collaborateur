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
					{if $oClient->client_iCivilite == CIVILITE_HOMME}{assign $zCivilite = "Mr"}{/if}
					{if $oClient->client_iCivilite == CIVILITE_MADEMOISELLE}{assign $zCivilite = "Mlle"}{/if}
					{if $oClient->client_iCivilite == CIVILITE_FEMME}{assign $zCivilite = "Mme"}{/if}

					Bonjour,<br /><br /><br />

					{$zCivilite} {$oClient->client_zNom} {$oClient->client_zPrenom} a réservé une date non planifier pour son test de début de stage.<br /><br /> 
					La raison : {if $toParams["raisonChoix"] == 1}Je suis absent sur la période proposée{else}Les créneaux proposés ne vous conviennent pas{/if}<br /><br />
					Ses choix sont les suivants: <br />
					Choix 1 : {$toParams["date1"]} de {$toParams["heureDebut1"]} à {$toParams["heureFin1"]}<br />
					{if isset($toParams["date2"]) && $toParams["date2"] != "" && isset($toParams["heureDebut2"]) && $toParams["heureDebut2"] != "" && isset($toParams["heureFin2"]) && $toParams["heureFin2"] != "" }
					Choix 2 : {$toParams["date2"]} de {$toParams["heureDebut2"]} à {$toParams["heureFin2"]}<br />
					{/if}
					{if isset($toParams["date3"]) && $toParams["date3"] != "" && isset($toParams["heureDebut3"]) && $toParams["heureDebut3"] != "" && isset($toParams["heureFin3"]) && $toParams["heureFin3"] != "" }
					Choix 3 : {$toParams["date3"]} de {$toParams["heureDebut3"]} à {$toParams["heureFin3"]}
					{/if}

					<br /><br />
					Commentaire: <br />
					{$toParams["commentChoix"]|nl2br}<br /><br />

					Les informations concernant le projet :
					{if isset($oClient->client_zTel) && $oClient->client_zTel != ""}
						<br />Tel : {$oClient->client_zTel}
					{/if} 
					{if isset($oClient->client_zPortable) && $oClient->client_zPortable != ""}
						<br />Portable : {$oClient->client_zPortable}
					{/if} 
					{if isset($oClient->client_zMail) && $oClient->client_zMail != ""}
						<br />eMail : {$oClient->client_zMail}
					{/if} 
                </font>
				<br /><br /><br />
				<font face="Verdana, Geneva, sans-serif" size="2">
					Ceci est un mail envoyé automatiquement depuis l'application autoplannification suite à une proposition de date d'un projet pour son test de debut de stage.
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

