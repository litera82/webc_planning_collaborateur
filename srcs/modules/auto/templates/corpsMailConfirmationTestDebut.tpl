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
					{if $oClient->client_iCivilite == CIVILITE_MADEMOISELLE}{assign $zCivilite = "Mlle / Mrs"}{/if}
					{if $oClient->client_iCivilite == CIVILITE_FEMME}{assign $zCivilite = "Mme / Mrs"}{/if}

					{if $oUtilisateur->utilisateur_iCivilite == CIVILITE_FEMME}
						{assign $zCiviliteProf = "Madame"}
					{else}
						{assign $zCiviliteProf = "Monsieur"}
					{/if} 

					Bonjour, <i>Hello</i>, {$zCivilite} {$oClient->client_zNom},<br /><br /><br />
					Votre test oral de niveau est programmé pour le {$zDateFr} à {$zHeure} avec {$zCiviliteProf} {$oUtilisateur->utilisateur_zPrenom} {$oUtilisateur->utilisateur_zNom},{if isset ($zTelResa) && $zTelResa != ''} elle vous appellera au {$zTelResa}<br />{/if}
					<br /><br /><br />	
					<i>Your oral assessment test is planned on {$zDateEn} at {$zHeure} with {$zCiviliteProf} {$oUtilisateur->utilisateur_zPrenom} {$oUtilisateur->utilisateur_zNom},{if isset ($zTelResa) && $zTelResa != ''} who will call you at {$zTelResa}<br />{/if}</i>
				</font>
				<br /><br />
				<font face="Verdana, Geneva, sans-serif" size="2">
					Vous pouvez cliquer <a href="{$zUrlModif}" style="color:#1D5987;">ici</a> pour modifier votre réservation.
                </font>
				<br />
				<font face="Verdana, Geneva, sans-serif" size="2">
					<i>You can click <a href="{$zUrlModif}" style="color:#1D5987;">here</a> to modify your booking.</i> 
                </font>
				<br /><br /><br />
                <font face="Verdana, Geneva, sans-serif" size="2">
					Pour mémoire, votre compte d'accès est le suivant / <i>As a reminder, your access account is the following</i> :<br /><br />
					Identifiant / <i>Login</i> : {$oClient->client_zLogin}<br /><br />
					Mot de passe / <i>Password</i> : {$oClient->client_zPass}<br /><br />
                </font>
				
				<br /><br /><br />
				<font face="Verdana, Geneva, sans-serif" size="2">
					A très bientôt, / <i>Best regards,</i><br /><br />
					Equipe Planning en ligne/Forma2+ / <i>The Forma2plus online planning team</i> 
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

