{literal}
<script type="text/javascript">
$( function () {
	addEvent(window, "load", tmt_validatorInit);
	$('.submitFormSearch').click(
		function(){
			var form = document.getElementById('edit_form');
			var isValid = tmt_validateForm(form);
			if(isValid){
				$('#edit_form').submit();
			}
		}
	);

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

</script>
{/literal}
<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear">
				<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~FoClient:getClientListing', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
					<input type="hidden" name="evenement_id" id="evenement_id" />
							<h2>Recherche de projet</h2>
							<p class="clear">
								<label>Nom</label>
								<input class="text" type="text" name="client_zNom" id="client_zNom" value="{if isset($toParams[0]->nom) && $toParams[0]->nom != ''}{$toParams[0]->nom}{/if}" style="width:450px;"/>
							</p>
							<p class="clear">
								<label>Prénom</label>
								<input class="text" type="text" name="client_zPrenom" id="client_zPrenom" value="{if isset($toParams[0]->prenom) && $toParams[0]->prenom != ''}{$toParams[0]->prenom}{/if}" style="width:450px;"/>
							</p>
							<p class="clear">
								<label>Catégorie</label>
								<input type="text" class="text" value="{if isset($toParams[0]->societe) && $toParams[0]->societe != ''}{$toParams[0]->societe}{/if}" name="client_zSociete" id="client_zSociete" style="width:450px;">
							</p>
							<p class="clear">
								<label>Collaborateur</label>
								<select class="text" name="client_iUtilisateurCreateurId" class="js-style-me" id="client_iUtilisateurCreateurId" style="width:262px;" >
								<option value="0">----------------------Séléctionner----------------------</option>
								{foreach $toProfesseur as $oProfesseur}
									<option value="{$oProfesseur->utilisateur_id}"
										{if isset ($toParams[0]->client_iUtilisateurCreateurId) && $toParams[0]->client_iUtilisateurCreateurId == $oProfesseur->utilisateur_id}selected="selected"{/if}	
									>{$oProfesseur->utilisateur_zNom} {$oProfesseur->utilisateur_zPrenom}</option>
								{/foreach}
								</select>
							</p>
							<p class="clear">
								<label>Projet ayant effectué un test de début de stage</label>
								<select class="text" name="client_testDebut" class="js-style-me" id="client_testDebut" style="width:100px;">
									<option value="2">Afficher tout</option>
									<option value="1">Oui</option>
									<option value="0">Non</option>
								</select>
							</p>
						<div class="input">
							<input type="button" value="Rechercher" class="boutonform submitFormSearch" />
							<a href="{jurl 'client~FoClient:add', array('tEvent'=>null), false}" target="_blank"><input type="button" value="Ajouter un projet" class="boutonform" /></a>
						</div>
					</form>
			</div>
		</div>
		<div class="content">
			<div class="formevent listeclients clear" style="width:943px">
			<form id="edit_form" method="POST" enctype="multipart/form-data" tmt:validate="true" >
						<h2>Liste des projets correspondant au critère de recherche</h2>
						{if $toParams[0]->nom != ""}<h3><span class="title">Nom : </span> <span>{$toParams[0]->nom}</span></h3>{/if}
						{if $toParams[0]->prenom != ""}<h3><span class="title">Prénom : </span> <span>{$toParams[0]->prenom}</span></h3>{/if}
						{if $toParams[0]->client_iUtilisateurCreateurId == 0}
							<h3 class="extra">Collaborateur : <span>Tous les collaborateurs</span></h3>
						{else}
							<h3><span class="title">Collaborateur :</span> <span>{$toStagiaire['toListes'][0]->utilisateur_zNom}&nbsp;{$toStagiaire['toListes'][0]->utilisateur_zPrenom}</span></h3>
						{/if}	
						<h3 class="last"><span class="title">Nombre de projets trouvés :</span> <span>{$toStagiaire['iResTotal']}</span></h3>
						<div>
							<table cellpadding="0" cellspacing="0" border="0">
								<tbody>
									<tr>
										<th class="col1">Civilité   </th>
										<th class="col2">Nom</th>
										<th class="col3">Prénom</th>
										<th class="col4">Fonction</th>
										<th class="col5">Email</th>
										<th class="col6">Tél</th>
										<th class="col7">Collaborateur</th>
										<th class="col8">Actions</th>
									</tr>
									{assign $i = 1}
									{foreach $toStagiaire['toListes'] as $oStagiaire}
									<tr class="extra{$i++%2+1}">
										<td class="col1">
											<span>
												{if $oStagiaire->client_iCivilite == CIVILITE_FEMME}Mme{/if}
												{if $oStagiaire->client_iCivilite == CIVILITE_MADEMOISELLE}Mlle{/if}
												{if $oStagiaire->client_iCivilite == CIVILITE_HOMME}Mr{/if}
											</span>
										</td>
										<td class="col2"><a href="{jurl 'client~FoClient:add', array('iClientId'=>$oStagiaire->client_id)}">{$oStagiaire->client_zNom}</a></td>
										<td class="col3">{$oStagiaire->client_zPrenom}</td>
										<td class="col4">{$oStagiaire->client_zFonction}</td>
										<td class="col5">{$oStagiaire->client_zMail}</td>
										<td class="col6">{$oStagiaire->client_zTel}</td>
										{*<!--<td class="col7">{$oStagiaire->client_zRue}&nbsp;{$oStagiaire->client_zVille}&nbsp;{$oStagiaire->client_zCP}{if isset($oStagiaire->pays_zNom)}{$oStagiaire->pays_zNom}{/if}</td>-->*}
										<td class="col7">{$oStagiaire->utilisateur_zNom}&nbsp;{$oStagiaire->utilisateur_zPrenom}</td>
										<td class="col8" style="text-align:center;">
											<a target="_blank" href="{jurl 'evenement~FoEvenement:getEventListing', array('evenement_stagiaire'=>$oStagiaire->client_id, 'iCheckDate'=>1)}" title="Voir les évenements associés au projet"><img src="{$j_basepath}design/front/images/design/calendar.png" alt="Voir les évenements associés au stagiaire" /></a>&nbsp;&nbsp;<a href="{jurl 'client~FoClient:add', array('iClientId'=>$oStagiaire->client_id)}" title="Détails du projet"><img src="{$j_basepath}design/front/images/design/edit.png" alt="Voir les détails du stagiaire" /></a>
										</td>
									</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
						<div class="input">
							<input type="button" value="Imprimer" class="boutonform submitForm" onclick="window.print();"/>
							<a href="{jurl 'client~FoClient:add', array('tEvent'=>null), false}" target="_blank"><input type="button" value="Ajouter un projet" class="boutonform" /></a>
						</div>
				</form>
			</div>
		</div>
	</div>
</div>
{$footer}