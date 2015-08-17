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
				<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~FoClient:getProjetListing', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
					<input type="hidden" name="evenement_id" id="evenement_id" />
					<h2>Recherche de projet</h2>
					<p class="clear">
					<label>Titre d post</label>
					<input class="text" type="text" name="zTitlePost" id="zTitlePost" value="{if isset($toParams[0]->zTitlePost) && $toParams[0]->zTitlePost != ''}{$toParams[0]->zTitlePost}{/if}" style="width:450px;" />
					</p>
					<p class="clear">
					<label>Type</label>
					<select class="text" class="js-style-me" style="width:100px;" name="zTypePost" id="zTypePost">
						<option selected="selected" value="0">-------Tous -------</option>
						<option value="1" {if isset($toParams[0]->zTypePost) && $toParams[0]->zTypePost ==1}selected=selected{/if}>Revision</option>
						<option value="2" {if isset($toParams[0]->zTypePost) && $toParams[0]->zTypePost ==2}selected=selected{/if}>Post</option>
					</select>
					</p>
					<div class="input">
					<input type="button" value="Rechercher" class="boutonform submitFormSearch" />
					</div>
				</form>
			</div>
		</div>
		<div class="content">
			<div class="formevent listeclients clear" style="width:943px">
			<form id="edit_form" method="POST" enctype="multipart/form-data" tmt:validate="true" >
						<h2>Liste des projets correspondant au critère de recherche</h2>
						<h3 class="last"><span class="title">Nombre de projets trouvés :</span> <span>{$toStagiaire['iResTotal']}</span></h3>
						<div>
							<table cellpadding="0" cellspacing="0" border="0">
								<tbody>
									<tr>
										<th class="col1">Civilité</th>
										<th class="col2">Nom</th>
										<th class="col3">Prénom</th>
										<th class="col4">Fonction</th>
										<th class="col8" style="width:50px;text-align:center;">Actions</th>
									</tr>
									{assign $i = 1}
									{foreach $toStagiaire['toListes'] as $oStagiaire}
									<tr class="extra{$i++%2+1}">
										<td class="col1">
											<span>{$oStagiaire->ID}</span>
										</td>
										<td class="col2">{$oStagiaire->post_date|date_format:'%d/%m/%Y'}</td>
										<td class="col3">{$oStagiaire->post_title}</td>
										<td class="col4">{$oStagiaire->post_type}</td>
										<td class="col8" style="width:50px;text-align:center;"><a href="{$oStagiaire->guid}" target="_blank"><img src="{$j_basepath}design/front/images/design/edit.png" title="{$oStagiaire->guid}" /></a>
										</td>
									</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
						<div class="input">
							<input type="button" value="Imprimer" class="boutonform submitForm" onclick="window.print();"/>
						</div>
				</form>
			</div>
		</div>
	</div>
</div>
{$footer}