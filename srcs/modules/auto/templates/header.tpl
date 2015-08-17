<div class="header">
	<p style="text-align:left;">
		<img alt="Forma2+ - Autoplannification" title="Forma2+ - Autoplannification" src="{$j_basepath}design/commun/images/logo.gif" />
	</p>
	<p style="text-align:left;">
		{assign $zCivilite = ""}
		{if $oCurrentUser->client_iCivilite == CIVILITE_HOMME}{assign $zCivilite = "Mr"}{/if}
		{if $oCurrentUser->client_iCivilite == CIVILITE_MADEMOISELLE}{assign $zCivilite = "Mlle"}{/if}
		{if $oCurrentUser->client_iCivilite == CIVILITE_FEMME}{assign $zCivilite = "Mme"}{/if}
		<span style="font-size: 11px;color:#1D5987;">Bonjour, <i>Hello,</i> {$zCivilite} {$oCurrentUser->client_zNom} {$oCurrentUser->client_zPrenom}</span> | <span><a style="color:#1D5987;font-size: 11px;" href="{jurl 'auto~default:index'}" title="Ma réservation / My reservation">Ma réservation / </i>My reservation</i></a></span> | <span><a  style="color:#1D5987;font-size: 11px;" title="Accéder à notre site / Access our website" href="http://www.forma2plus.com" target="_blank">Accéder à notre site / <i>Access our website</i></a></span> | <span><a  style="color:#1D5987;font-size: 11px;" title="Nous contacter / Contact us" href="http://www.forma2plus.com/index.php?nv=content&id=29" target="_blank">Nous contacter / <i>Contact us</i></a></span> | <span><a  style="color:#1D5987;font-size: 11px;" href="http://www.forma2plus.com/" title="Quitter / Quit">Quitter / <i>Quit</i></a></span> | <span><a  style="color:#1D5987;font-size: 11px;" href="{jurl 'jauth~login:out'}" title="Deconnexion / Deconnection">Deconnexion / <i>Deconnection</i></a></span>
	</p>
</div>