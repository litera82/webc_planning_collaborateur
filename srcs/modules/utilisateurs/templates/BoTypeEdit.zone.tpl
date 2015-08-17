<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">{if $bEdit}Edition {else}Nouveau{/if}</h1>
<h2>{if $bEdit}Edition  : {else}Nouveau {/if} {if $bEdit}{$oType->type_zLibelle}{/if}</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'admin~typeUtilisateurs:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
    <input type="hidden" name="type_id" value="{if $bEdit}{$oType->type_id}{else}0{/if}" />
    
    <p class="clearfix">
        <label>Libelle *:</label>
        <span class="champ"><input type="text" name="type_zLibelle" id="type_zLibelle" value="{$oType->type_zLibelle}" tmt:message="Veuillez remplir le champ Libelle<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Statut :</label>
        <span class="champ">
       	<input type="radio" name="type_statut" id="type_statut" class="radio" value="1" {if $oType->type_statut == STATUT_PUBLIE}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  statut"/>&nbsp;PUBLIER&nbsp;<input type="radio" name="type_statut" id="type_statut" class="radio" value="2" {if $oType->type_statut == STATUT_NON_PUBLIE}checked="checked"{/if} />&nbsp;NON PUBLIER&nbsp;<input type="radio" name="type_statut" id="type_statut" class="radio" value="0" {if $oType->type_statut == STATUT_DESACTIVE}checked="checked"{/if} />&nbsp;DESACTIVER

        </span>
    </p>
    <p class="line_bottom">&nbsp;</p>
    <p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Enregistrer" />&nbsp;
        <input type="button" class="bouton" name="annuler" value="Annuler" onclick="location.href='{jurl 'admin~typeUtilisateurs:index', array(), false}'"/>
    </p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>