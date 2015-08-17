{literal}
<script type="text/javascript">
	DD_roundies.addRule('div.formevent', '5px');
    DD_roundies.addRule('input.boutonform', '5px');

	var autoCompleteJson= function(data){
		var parsed=[];
		for (var i=0; i<data.length;i++){
			var row=data[i];
			parsed.push({
				data: row,
				value: row["societe_zNom"],
				result: row["societe_zNom"]
			});
		}
		return parsed;
	}

	$(function(){ 
		var url=j_basepath + "index.php?module=client&action=FoClient:autocompleteSociete";
		$('#client_zSociete').autocomplete(url,{
			/*mustMatch : true,*/
			minChars: 0,
			autoFill: false,
			scroll: true,
			scrollHeight: 300,
			dataType: "json" ,
			parse : autoCompleteJson,
			formatItem: function(row) {
				return row["societe_zNom"];
			}
		}).result(function(event, row, formatted){	
			if (typeof(row) == 'undefined') {		
				$('#client_iSociete').val(0);		
			} else {
				$('#client_iSociete').val(row["societe_id"]);
				$('#client_zSociete').val(row["societe_zNom"]);
			}
		}).blur(function(){
			$(this).search();
		});

	});
</script>
{/literal}
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~FoClient:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
	<input type="hidden" name="iEvenementId" id="iEvenementId" value="{$iEvenementId}" />
	<input type="hidden" name="client_id" id="client_id" value="{$iClientId}" />
	<input type="hidden" name="sendMail" id="sendMail" value="0" />
	<input type="hidden" name="client_iSociete" id="client_iSociete" value="{if $iClientId > 0}{$oClient->client_iSociete}{/if}"/>

			<h2>Création / Modification de projet</h2>
			<p class="clear">
				<label>Catégorie *</label>
				{assign $zSocieteNom = ""}
				{foreach $toSociete as $oSociete}
					{if $oSociete->societe_id == $oClient->client_iSociete}
						{assign $zSocieteNom = $oSociete->societe_zNom}
					{/if}
				{/foreach}
				<input type="text" class="text" value="{$zSocieteNom}" name="client_zSociete" id="client_zSociete" tmt:required="true">
				&nbsp;&nbsp;
				<a href="#" class="create" title="Ajouter une catégorie">
					<img src="{$j_basepath}design/front/images/design/buttons/plus.png" alt="Ajouter une catégorie">
				</a> 
			</p>
			<p class="clear">
				<label>Collaborateur</label>
				<select class="text" name="client_iUtilisateurCreateurId" id="client_iUtilisateurCreateurId" mt:required="true" tmt:invalidIndex="0">
					<option value="0">----------------------Séléctionner----------------------</option>
					{foreach $toProfesseur as $oProfesseur}
						<option value="{$oProfesseur->utilisateur_id}" {if $bEdit}{if $oClient->client_iUtilisateurCreateurId==$oProfesseur->utilisateur_id} selected=selected {/if}{else}{if $oProfesseur->utilisateur_id==$oUtilisateur->utilisateur_id} selected=selected {/if}{/if}>{$oProfesseur->utilisateur_zPrenom}&nbsp;{$oProfesseur->utilisateur_zNom}</option>
					{/foreach}
				</select>
			</p>
			<p class="civil clear">
				<label>Civilité</label>
				<select id="client_iCivilite" name="client_iCivilite" tmt:required="true" >
					<option value="1" {if $bEdit}{if $oClient->client_iCivilite == CIVILITE_HOMME}selected="selected"{/if}{else}selected="selected"{/if}>Mr</option>
					<option value="0" {if $bEdit}{if $oClient->client_iCivilite == CIVILITE_FEMME}selected="selected"{/if}{/if}>Mme</option>
					<option value="2" {if $bEdit}{if $oClient->client_iCivilite == CIVILITE_MADEMOISELLE}selected="selected"{/if}{/if}>Mlle</option>
				</select>
            </p>

			<p class="clear">
				<label>Nom *</label>
				<input class="text" type="text" name="client_zNom" id="client_zNom" value="{$oClient->client_zNom}" tmt:required="true"/>
			</p>
			<p class="clear">
				<label>Prénom </label>
				<input class="text" type="text" name="client_zPrenom" id="client_zPrenom" value="{$oClient->client_zPrenom}" />
			</p>

			<p class="clear">
				<label>Login </label>
				<input class="text" type="text" name="client_zLogin" id="client_zLogin" value="{$oClient->client_zLogin}" />
			</p>
			<p class="clear">
				<label>Mot de passe </label>
				<input class="text" type="text" name="client_zPass" id="client_zPass" value="{$oClient->client_zPass}" />
			</p>
			{*<!--<p class="clear">
				<label>Professeur *</label>
				<select id="client_iUtilisateurCreateurId" name="client_iUtilisateurCreateurId" tmt:required="true" >
					{foreach $toUtilisateur as $oUtilisateur}
						<option value="{$oUtilisateur->utilisateur_id}" {if $bEdit}{if $oClient->client_iUtilisateurCreateurId==$iUtilisateurId} selected=selected {/if}{else}{if $oUtilisateur->utilisateur_id == $iUtilisateurId}selected=selected{/if}{/if}>{$oUtilisateur->utilisateur_zNom}&nbsp;{$oUtilisateur->utilisateur_zPrenom}</option>
					{/foreach}
				</select>
            </p>-->*}
			<p class="type2 clear">
				<label>Téléphone </label>
				<input type="text" class="text" name="client_zTel" id="client_zTel" value="{$oClient->client_zTel}"/>
			</p>
			{*<!--<p class="type2 clear">
				<label>Portable</label>
				<input class="text" type="text" name="client_zPortable" id="client_zPortable" value="{$oClient->client_zPortable}" />
			</p>-->*}
			<p class="type2 clear">
				<label>Mail *</label>
				<input class="text" type="text" name="client_zMail" id="client_zMail" value="{$oClient->client_zMail}" tmt:required="true"/>
			</p>
			<p class="clear">
				<label>Fonction </label>
				<input class="text" type="text" name="client_zFonction" id="client_zFonction" value="{$oClient->client_zFonction}" />
			</p>
			<p class="clear">
				<label>Rue </label>
				<input class="text" type="text" name="client_zRue" id="client_zRue" value="{$oClient->client_zRue}" />
			</p>
			<p class="type2 clear">
				<label>Ville </label>
				<input class="text" type="text" name="client_zVille" id="client_zVille" value="{$oClient->client_zVille}" />
			</p>
			<p class="type2 clear">
				<label>Code postal </label>
				<input class="text" type="text" name="client_zCP" id="client_zCP" value="{$oClient->client_zCP}" tmt:filter="postalcode" />
			</p>
			<p class="clear">	
				<label>Pays </label>
				<select class="text" name="client_iPays" id="client_iPays">
					<option value="0">----------------------Séléctionner----------------------</option>
					{foreach $toPays as $oPays}
						<option value="{$oPays->pays_id}" {if $bEdit}{if $oClient->client_iPays==$oPays->pays_id} selected=selected {/if}{else}{if $oPays->pays_id==64} selected=selected {/if}{/if}>{$oPays->pays_zNom}</option>
					{/foreach}
				</select>
			</p>
			{*<!--<p class="clear">
				<label>Numéro Individu </label>
				<input class="text" type="text" name="client_iNumIndividu" id="client_iNumIndividu" value="{$oClient->client_iNumIndividu}" />
			</p>-->*}
			<p class="clear">
				<label>Url du blog </label>
				<input class="text" type="text" name="client_url" id="client_url" value="{$oClient->client_url}" />
			</p>
			<p class="clear">
				<label>Statut *</label>
					<input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="1" {if $bEdit}{if $oClient->client_iStatut == STATUT_PUBLIE}checked="checked"{/if}{else}checked="checked"{/if} tmt:required="true"/><span>Afficher</span><input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="2" {if $bEdit}{if $oClient->client_iStatut == STATUT_NON_PUBLIE}checked="checked"{/if}{/if} /><span>Ne pas afficher</span><input type="radio" name="client_iStatut" id="client_iStatut" class="radio" value="0" {if $bEdit}{if $oClient->client_iStatut == STATUT_DESACTIVE}checked="checked"{/if}{/if} /><span>Annuler</span>
			</p>
			<div class="input">
				<a href="{jurl 'client~FoClient:clientListing', array(), false}"><input type="button" value="Annuler" class="boutonform" /></a>
				{if $bEdit}
				<input type="button" value="Modifier" class="boutonform submitForm" />
				{else}
				<input type="button" value="Créer" class="boutonform submitForm" />
				{/if}
				<input type="button" value="Enregistrer et envoi de mail" class="boutonform longtext submitFormMail" />
			</div>
		<div class="input" style="width:480px;">
			<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
		</div>
	</form>