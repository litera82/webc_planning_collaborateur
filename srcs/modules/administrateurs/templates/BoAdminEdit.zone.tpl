<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">{if $bEdit}Edition {else}Nouveau{/if}</h1>
<h2>{if $bEdit}Edition  : {else}Nouveau {/if} {if $bEdit}{$oAdmin->admin_zNom}{/if}</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'admin~administrateurs:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
    <input type="hidden" name="admin_id" value="{if $bEdit}{$oAdmin->admin_id}{else}0{/if}" />
    <p class="clearfix">
        <label>Identifiant *:</label>
        <span class="champ"><input type="text" name="admin_zLogin" id="admin_zLogin" value="{$oAdmin->login}" tmt:message="Veuillez remplir le champ Mot de passe<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Mot de passe *:</label>
        <span class="champ"><input type="password" name="admin_zPass" id="admin_zPass" value="{$oAdmin->password}" tmt:message="Veuillez remplir le champ mot de passe<br />" tmt:required="true"/></span>
    </p>
    <p class="clearfix">
        <label>Confirmation *:</label>
        <span class="champ"><input type="password" name="password1" id="password1" value="{if $bEdit}{$oAdmin->password}{/if}" tmt:equalto="admin_zPass" tmt:message="Veuillez verifier la confirmation de votre mot de passe<br />" tmt:required="true"/></span>
    </p>
    <p class="clearfix">
        <label>Nom *:</label>
        <span class="champ"><input type="text" name="admin_zNom" id="admin_zNom" value="{$oAdmin->admin_zNom}" tmt:message="Veuillez remplir le champ Nom<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Prénom(s) :</label>
        <span class="champ"><input type="text" name="admin_zPrenom" id="admin_zPrenom" value="{$oAdmin->admin_zPrenom}" /></span>
    </p>
	<p class="clearfix">
        <label>Civilité *:</label>
		<span class="champ">
       	<input type="radio" name="admin_civilite" id="admin_civilite" class="radio" value="1" {if $oAdmin->admin_civilite == CIVILITE_HOMME}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  Civilité"/>&nbsp;homme&nbsp;<input type="radio" name="admin_civilite" id="admin_civilite" class="radio" value="0" {if $oAdmin->admin_civilite == CIVILITE_FEMME}checked="checked"{/if} />&nbsp;femme
        </span>		
    </p>
	<p class="clearfix">
        <label>Mail *:</label>
        <span class="champ"><input type="text" name="admin_zMail" id="admin_zMail" value="{$oAdmin->admin_zMail}" tmt:pattern="email" tmt:message="Veuillez entrer un adresse mail valide<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Télèphone *:</label>
        <span class="champ"><input type="text" name="admin_zTel" id="admin_zTel" value="{$oAdmin->admin_zTel}" tmt:message="Veuillez remplir le champ Télèphone<br />" tmt:required="true"/></span>
    </p>
    <p class="clearfix">
        <label>Statut :</label>
        <span class="champ">
       	<input type="radio" name="admin_iStatut" id="admin_iStatut" class="radio" value="1" {if $oAdmin->admin_iStatut == STATUT_PUBLIE}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  statut"/>&nbsp;PUBLIER&nbsp;<input type="radio" name="admin_iStatut" id="admin_iStatut" class="radio" value="2" {if $oAdmin->admin_iStatut == STATUT_NON_PUBLIE}checked="checked"{/if} />&nbsp;NON PUBLIER&nbsp;<input type="radio" name="admin_iStatut" id="admin_iStatut" class="radio" value="0" {if $oAdmin->admin_iStatut == STATUT_DESACTIVE}checked="checked"{/if} />&nbsp;DESACTIVER
        </span>
    </p>
    <p class="line_bottom">&nbsp;</p>
    <p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Enregistrer" />&nbsp;
        <input type="button" class="bouton" name="annuler" value="Annuler" onclick="location.href='{jurl 'admin~administrateurs:index', array(), false}'"/>
    </p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>