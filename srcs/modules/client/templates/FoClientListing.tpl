{literal}
<script type="text/javascript">
$( function () {
	addEvent(window, "load", tmt_validatorInit);
	$('.submitForm').click(
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
								<input class="text" type="text" name="client_zNom" id="client_zNom" value="" style="width:450px;"/>
							</p>
							<p class="clear">
								<label>Prénom</label>
								<input class="text" type="text" name="client_zPrenom" id="client_zPrenom" value="" style="width:450px;"/>
							</p>
							<p class="clear">
								<label>Catégorie</label>
								<input type="text" class="text" value="" name="client_zSociete" id="client_zSociete" style="width:450px;">
							</p>
							<p class="clear">
								<label>Collaborateur</label>
								<select class="text" name="client_iUtilisateurCreateurId" class="js-style-me" id="client_iUtilisateurCreateurId" style="width:262px;" >
								<option value="0">----------------------Séléctionner----------------------</option>
								{foreach $toProfesseur as $oProfesseur}
									<option value="{$oProfesseur->utilisateur_id}">{$oProfesseur->utilisateur_zNom} {$oProfesseur->utilisateur_zPrenom}</option>
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
							<input type="button" value="Rechercher" class="boutonform submitForm" />
							<a href="{jurl 'client~FoClient:add', array('tEvent'=>null), false}" target="_blank"><input type="button" value="Ajouter un projet" class="boutonform" /></a>
						</div>
					</form>
			</div>
		</div>
	</div>
</div>
{$footer}