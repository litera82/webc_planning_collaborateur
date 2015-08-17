<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">Gestion des projets</h1>
<h2>{if $bEdit}Edition  : {else}Nouveau {/if} {if $bEdit}{$oClient->client_zNom}{/if}</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~client:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
    <input type="hidden" name="client_id" value="{if $bEdit}{$oClient->client_id}{else}0{/if}" />
    <p class="clearfix">
        <label>Catégories *:</label>
        <span class="champ">
			<select name="client_iSociete" id="client_iSociete" tmt:message="Veuillez séléctionner la catégorie<br />" tmt:required="true" tmt:invalidIndex="0">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toSociete as $oSociete}
					<option value="{$oSociete->societe_id}" {if $bEdit}{if $oClient->client_iSociete==$oSociete->societe_id} selected=selected {/if}{/if}>{$oSociete->societe_zNom}</option>
				{/foreach}
			</select><!--&nbsp;&nbsp;&nbsp;<input type="button" value="Ajouter une société" id="addSociete" name="addSociete" class="bouton" style="font-size:9px; width:100px"-->
		</span>
    </p>
    <p class="clearfix">
        <label>Collaborateur :</label>
        <span class="champ">
			<select name="client_iUtilisateurCreateurId" id="client_iUtilisateurCreateurId" tmt:message="Veuillez séléctionner le collaborateur<br />" tmt:required="true" tmt:invalidIndex="0">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toUtilisateur as $oUtilisateur}
					<option value="{$oUtilisateur->utilisateur_id}" {if $bEdit}{if $oClient->client_iUtilisateurCreateurId==$oUtilisateur->utilisateur_id} selected=selected {/if}{/if}>{$oUtilisateur->utilisateur_zNom}&nbsp;{$oUtilisateur->utilisateur_zPrenom}</option>
				{/foreach}
			</select>
		</span>
    </p>
    <p class="clearfix">
        <label>Civilité *:</label>
        <span class="champ">
       	<input type="radio" name="client_iCivilite" id="client_iCivilite" class="radio" value="1" {if $oClient->client_iCivilite == CIVILITE_HOMME}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  Civilité"/>&nbsp;Monsieur&nbsp;<input type="radio" name="client_iCivilite" id="client_iCivilite" class="radio" value="0" {if $oClient->client_iCivilite == CIVILITE_FEMME}checked="checked"{/if} />&nbsp;Madame&nbsp;<input type="radio" name="client_iCivilite" id="client_iCivilite" class="radio" value="2" {if $oClient->client_iCivilite == CIVILITE_MADEMOISELLE}checked="checked"{/if} />&nbsp;Mademoiselle
		</span>
    </p>
	<p class="clearfix">
        <label>Nom *:</label>
        <span class="champ"><input type="text" name="client_zNom" id="client_zNom" value="{$oClient->client_zNom}" tmt:message="Veuillez remplir le champ Nom<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Prénom :</label>
        <span class="champ"><input type="text" name="client_zPrenom" id="client_zPrenom" value="{$oClient->client_zPrenom}" /></span>
    </p>
	<p class="clearfix">
        <label>Fonction :</label>
        <span class="champ"><input type="text" name="client_zFonction" id="client_zFonction" value="{$oClient->client_zFonction}" /></span>
    </p>
	<p class="clearfix">
        <label>Mail :</label>
        <span class="champ"><input type="text" name="client_zMail" id="client_zMail" value="{$oClient->client_zMail}" /></span>
    </p>
	<p class="clearfix">
        <label>Identifiant *:</label>
        <span class="champ"><input type="text" name="client_zLogin" id="client_zLogin" value="{$oClient->client_zLogin}" tmt:message="Veuillez remplir le champ Identifiant<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Mot de passe *:</label>
        <span class="champ"><input type="text" name="client_zPass" id="client_zPass" value="{$oClient->client_zPass}" tmt:message="Veuillez remplir le champ Mot de passe<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Confirmation Mot de passe *:</label>
        <span class="champ"><input type="text" name="client_zPassConfirm" id="client_zPassConfirm" value="{$oClient->client_zPass}" tmt:message="Veuillez remplir le champ confirmation Mot de passe<br />" tmt:required="true" tmt:equalto="client_zPass"/></span>
    </p>
	<p class="clearfix">
        <label>Téléphone :</label>
        <span class="champ"><input type="text" name="client_zTel" id="client_zTel" value="{$oClient->client_zTel}" /></span>
    </p>
	<p class="clearfix">
        <label>Portable :</label>
        <span class="champ"><input type="text" name="client_zPortable" id="client_zPortable" value="{$oClient->client_zPortable}" /></span>
    </p>

	<p class="clearfix">
        <label>Rue :</label>
        <span class="champ"><input type="text" name="client_zRue" id="client_zRue" value="{$oClient->client_zRue}" /></span>
    </p>

	<p class="clearfix">
        <label>Ville :</label>
        <span class="champ"><input type="text" name="client_zVille" id="client_zVille" value="{$oClient->client_zVille}" /></span>
    </p>

	<p class="clearfix">
        <label>Code postal :</label>
        <span class="champ"><input type="text" name="client_zCP" id="client_zCP" value="{$oClient->client_zCP}" tmt:filter="postalcode" /></span>
    </p>
	<p class="clearfix">
        <label>Pays :</label>
        <span class="champ"><!--input type="text" name="client_iPays" id="client_iPays" value="{$oClient->client_iPays}" /-->
			<select name="client_iPays" id="client_iPays">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toPays as $oPays}
					<option value="{$oPays->pays_id}" {if $bEdit}{if $oClient->client_iPays==$oPays->pays_id} selected=selected {/if}{/if}>{$oPays->pays_zNom}</option>
				{/foreach}
			</select>
		</span>
    </p>
	<p class="clearfix">
        <label>Numéro Individu :</label>
        <span class="champ"><input type="text" name="client_iNumIndividu" id="client_iNumIndividu" value="{$oClient->client_iNumIndividu}" /></span>
    </p>
	<p class="clearfix">
        <label>Numéro Individu :</label>
        <span class="champ"><input type="text" name="client_iRefIndividu" id="client_iRefIndividu" value="{$oClient->client_iRefIndividu}" readonly="readonly"/></span>
    </p>
	<p class="clearfix">
        <label>Url du blog :</label>
        <span class="champ"><input type="text" name="client_url" id="client_url" value="{$oClient->client_url}"/></span>
    </p>
	<p class="clearfix">
        <label>Date de création :</label>
        <span class="champ">{if $oClient->client_dateCreation !='0000-00-00 00:00:00'}{$oClient->client_dateCreation|date_format:"%d/%m/%Y %H:%M:%S"}{/if}&nbsp;</span>
    </p>
	<p class="clearfix">
        <label>Date de dernière modification :</label>
        <span class="champ">{if $oClient->client_dateMaj !='0000-00-00 00:00:00'}{$oClient->client_dateMaj|date_format:"%d/%m/%Y %H:%M:%S"}{/if}&nbsp;</span>
    </p>

    <p class="clearfix">
        <label>Statut *:</label>
        <span class="champ">
       	<input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="1" {if $oClient->client_iStatut == STATUT_PUBLIE}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  statut"/>&nbsp;PUBLIER&nbsp;<input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="2" {if $oClient->client_iStatut == STATUT_NON_PUBLIE}checked="checked"{/if} />&nbsp;NON PUBLIER&nbsp;<input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="0" {if $oClient->client_iStatut == STATUT_DESACTIVE}checked="checked"{/if} />&nbsp;DESACTIVER
        </span>
    </p>
    <p class="line_bottom">&nbsp;</p>
    <p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Enregistrer" />&nbsp;
        <input type="button" class="bouton" name="annuler" value="Annuler" onclick="location.href='{jurl 'client~client:index', array(), false}'"/>
    </p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>