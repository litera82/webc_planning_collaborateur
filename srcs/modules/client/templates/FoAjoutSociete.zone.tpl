{literal}
<script type="text/javascript">
</script>
{/literal}
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~FoSociete:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
			<h2>Création / Modification d'une catégorie</h2>
			<p class="clear">
				<label>Catégorie *</label>
				<input class="text" type="text" name="societe_zNom" id="societe_zNom" value="" tmt:message="Veuillez remplir le champ Raison sociale" tmt:required="true"/>
			</p	>
			<p class="clear">
				<label>Statut *</label>
				<input type="radio" name="societe_iStatut" id="societe_iStatut" class="radio" value="1" />&nbsp;PUBLIER&nbsp;<input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="2" />&nbsp;NON PUBLIER&nbsp;<input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="0" />&nbsp;DESACTIVER
			</p>
			<div class="input">
				<a href="{jurl 'client~FoClient:add', array(), false}"><input type="button" value="Annuler" class="boutonform" /></a>
				{if $bEdit}
				<input type="button" value="Modifier" class="boutonform submitForm" />
				{else}
				<input type="button" value="Créer" class="boutonform submitForm" />
				{/if}
			</div>
	</form>
