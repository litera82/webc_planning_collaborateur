<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">{if $bEdit}Edition {else}Nouveau{/if}</h1>
<h2>{if $bEdit}Edition  : {else}Nouveau {/if} {if $bEdit}{$oUtilisateurs->utilisateur_zNom}{/if}</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'admin~utilisateurs:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
    <input type="hidden" name="utilisateur_id" value="{if $bEdit}{$oUtilisateurs->utilisateur_id}{else}0{/if}" />
    <p class="clearfix">
        <label>Type *</label>
        <span class="champ">
            <select name="utilisateur_iTypeId" id="utilisateur_iTypeId" style="width:140px" tmt:invalidvalue="0" tmt:message="Veuillez selectionner un type<br />" tmt:required="true">
                <option value="0">Choisir le type</option>
                {foreach $toTypes as $oType}
                    <option value="{$oType->type_id}" {if($oType->type_id == $oUtilisateurs->utilisateur_iTypeId)} selected=selected {/if}>{$oType->type_zLibelle}</option>
                {/foreach}
            </select>
        </span>
    </p>
	<p class="clearfix">
        <label>Identifiant *:</label>
        <span class="champ"><input type="text" name="utilisateur_zLogin" id="utilisateur_zLogin" value="{$oUtilisateurs->utilisateur_zLogin}" tmt:message="Veuillez remplir le champ Mot de passe<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Mot de passe *:</label>
        <span class="champ"><input type="password" name="utilisateur_zPass" id="utilisateur_zPass" value="{$oUtilisateurs->utilisateur_zPass}" tmt:message="Veuillez remplir le champ mot de passe<br />" tmt:required="true"/></span>
    </p>
    <p class="clearfix">
        <label>Confirmation *:</label>
        <span class="champ"><input type="password" name="password1" id="password1" value="{if $bEdit}{$oUtilisateurs->utilisateur_zPass}{/if}" tmt:equalto="utilisateur_zPass" tmt:message="Veuillez verifier la confirmation de votre mot de passe<br />" tmt:required="true" /></span>
    </p>
    <p class="clearfix">
        <label>Nom *:</label>
        <span class="champ"><input type="text" name="utilisateur_zNom" id="utilisateur_zNom" value="{$oUtilisateurs->utilisateur_zNom}" tmt:message="Veuillez remplir le champ Nom<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Prénom(s) :</label>
        <span class="champ"><input type="text" name="utilisateur_zPrenom" id="utilisateur_zPrenom" value="{$oUtilisateurs->utilisateur_zPrenom}" /></span>
    </p>
	<p class="clearfix">
        <label>Civilité *:</label>
		<span class="champ">
			<select name="utilisateur_iCivilite" id="utilisateur_iCivilite">
				<option value="0" {if $bEdit}{if $oUtilisateurs->utilisateur_iCivilite == CIVILITE_FEMME} selected=selected {/if}{/if}>Femme</option>
				<option value="1" {if $bEdit}{if $oUtilisateurs->utilisateur_iCivilite == CIVILITE_HOMME} selected=selected {/if}{/if}>Homme</option>
				<option value="2" {if $bEdit}{if $oUtilisateurs->utilisateur_iCivilite == CIVILITE_MADEMOISELLE} selected=selected {/if}{/if}>Mademoiselle</option>
			</select>
        </span>		
    </p>
	<p class="clearfix">
        <label>Mail *:</label>
        <span class="champ"><input type="text" name="utilisateur_zMail" id="utilisateur_zMail" value="{$oUtilisateurs->utilisateur_zMail}" tmt:pattern="email" tmt:message="Veuillez entrer un adresse mail valide<br />" tmt:required="true" tmt:pattern="email"/></span>
    </p>
	<p class="clearfix">
        <label>Télèphone *:</label>
        <span class="champ"><input type="text" name="utilisateur_zTel" id="utilisateur_zTel" value="{$oUtilisateurs->utilisateur_zTel}" tmt:message="Veuillez remplir le champ Télèphone<br />" tmt:required="true" tmt:filter="phonenumber"/></span>
    </p>
	<p class="clearfix">
        <label>Pays *:</label>
        <span class="champ">
			<select name="utilisateur_iPays" id="utilisateur_iPays"  tmt:message="Veuillez séléctionner la société<br />" tmt:required="true" tmt:invalidIndex="0">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toPays as $oPays}
					<option value="{$oPays->pays_id}" {if $bEdit}{if $oUtilisateurs->utilisateur_iPays==$oPays->pays_id} selected=selected {/if}{/if}>{$oPays->pays_zNom}</option>
				{/foreach}
			</select>
		</span>
    </p>
	<p class="clearfix">
        <label>Plage horaire *:</label>
        <span class="champ">
			<select name="utilisateur_plageHoraireId" id="utilisateur_plageHoraireId"  tmt:message="Veuillez séléctionner la plage horaire par defaut<br />" tmt:required="true" tmt:invalidIndex="0">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toPlageHoraire as $oPlageHoraire}
					<option value="{$oPlageHoraire->plagehoraire_id}" 
						{if $bEdit}
							{if $oUtilisateurs->utilisateur_plageHoraireId==$oPlageHoraire->plagehoraire_id} selected=selected {/if}
						{else}
							{if $oPlageHoraire->plagehoraire_id == 1}selected=selected{/if}
						{/if}	
						>{$oPlageHoraire->plagehoraire_libelle}</option>
				{/foreach}
			</select>
		</span>
    </p>

    <p class="clearfix">
        <label>Statut :</label>
        <span class="champ">
       	<input type="radio" name="utilisateur_statut" id="utilisateur_statut" class="radio" value="1" {if $oUtilisateurs->utilisateur_statut == STATUT_PUBLIE}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  statut"/>&nbsp;PUBLIER&nbsp;<input type="radio" name="utilisateur_statut" id="utilisateur_statut" class="radio" value="2" {if $oUtilisateurs->utilisateur_statut == STATUT_NON_PUBLIE}checked="checked"{/if} />&nbsp;NON PUBLIER&nbsp;<input type="radio" name="utilisateur_statut" id="utilisateur_statut" class="radio" value="0" {if $oUtilisateurs->utilisateur_statut == STATUT_DESACTIVE}checked="checked"{/if} />&nbsp;DESACTIVER
        </span>
    </p>
    <p class="line_bottom">&nbsp;</p>
    <p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Enregistrer" />&nbsp;
        <input type="button" class="bouton" name="annuler" value="Annuler" onclick="location.href='{jurl 'admin~utilisateurs:index', array(), false}'"/>
    </p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>