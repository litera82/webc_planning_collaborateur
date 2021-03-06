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

	$('.submitFormExport').click(
		function (){
			$('#edit_formList').submit();
		}
	);

	$('.submitFormExportIcs').click(
		function (){
			document.location.href = j_basepath + "index.php?module=evenement&action=FoEvenement:exportIcsEventListing&zDateDebut=" + $('#zDateDebut').val() + "&zDateFin=" + $('#zDateFin').val() + "&iTypeEvenement=" + $('#iTypeEvenement').val() + "&iStagiaire=" + $('#iStagiaire').val();

		}
	);

	$('.date').datepicker({
		duration: '',
		showTime: false,
		showOn: 'button',
		buttonImageOnly : true,
		buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
		constrainInput: false
	});
	$('.date1').datepicker({
		duration: '',
		showTime: false,
		showOn: 'button',
		buttonImageOnly : true,
		buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
		constrainInput: false
	});
	$('.date2').datepicker({
		duration: '',
		showTime: false,
		showOn: 'button',
		buttonImageOnly : true,
		buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
		constrainInput: false
	});

	var url=j_basepath + "index.php?module=evenement&action=FoEvenement:autocompleteStagiaire";
	$('#evenement_zStagiaire').autocomplete(url,{
		/*mustMatch : true,*/
		minChars: 0,
		autoFill: false,
		scroll: true,
		scrollHeight: 300,
		dataType: "json" ,
		parse : autoCompleteJson,
		formatItem: function(row) {
			var zInfo = "" ;
			if (row["client_zNom"] !== undefined && row["societe_zNom"] != "")
			{
				zInfo += ' ' + row["client_zNom"] ;
			}
			if (row["client_zPrenom"] !== undefined && row["client_zPrenom"] != "")
			{
				zInfo += ' ' + row["client_zPrenom"] ;
			}
			if (row["client_zTel"] !== undefined && row["client_zTel"] != "")
			{
				zInfo += '&nbsp;&nbsp;[' + row["client_zTel"] + ']' ;
			}
			if (row["societe_zNom"] !== undefined && row["societe_zNom"] != "")
			{
				zInfo += '&nbsp;&nbsp;[' + row["societe_zNom"] + ']' ;
			}
			if (row["client_zVille"] !== undefined && row["client_zVille"] != "")
			{
				zInfo += '&nbsp;&nbsp;[' + row["client_zVille"] + ']' ;
			}
			return zInfo ;
		}
	}).result(function(event, row, formatted){	
		if (typeof(row) == 'undefined') {		
			$('#evenement_stagiaire').val(0);		
			$('#evenement_zStagiaire').val("");		
		} else {
			$('#evenement_stagiaire').val(row["client_id"]);
		}
	}).blur(function(){
		$(this).search();
	});

	var url1=j_basepath + "index.php?module=evenement&action=FoEvenement:autocompleteSociete";
	$('#evenement_zSociete').autocomplete(url1,{
		minChars: 0,
		autoFill: false,
		scroll: true,
		scrollHeight: 300,
		dataType: "json" ,
		parse : autoCompleteJson1,
		formatItem: function(row) {
			var zInfo = "" ;
			if (row["societe_zNom"] != ""){
				zInfo += ' ' + row["societe_zNom"] ;
			}
			return zInfo ;
		}
	}).result(function(event, row, formatted){	
		if (typeof(row) == 'undefined') {		
			$('#evenement_societe').val(0);		
			$('#evenement_zSociete').val("");		
		} else {
			$('#evenement_societe').val(row["societe_id"]);
			$('#evenement_zSociete').val(row["societe_zNom"]);		
		}
	}).blur(function(){
		$(this).search();
	});


	$('.modifierEvent').click(
		function (){
			if ($(this).attr('ieventid') > 0){
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:eventListingEditEvent", iEvenementId:$(this).attr('ieventid')}, function(row){
					$('.classEvenementId').val(row['oEvenement']['evenement_id']);
					$('#evenement_zDescription').val(row['oEvenement']['evenement_zDescription']) ;
					$('.evenement_zStagiairepop').val(row['oStagiaire']['client_zNom']+' '+row['oStagiaire']['client_zPrenom']) ;
					$('#div-stagiaire-liste').attr({'style':'display:none'}) ; 
					$('#txtmail').val(row['oStagiaire']['client_zMail']);
					$('.iStagiairePop').val(row['oStagiaire']['client_id']);
					$('#txtphone').val(row['oStagiaire']['client_zTel']);
					$('#txtsociete').val(row['oSociete']['societe_zNom']);
					$('#txtville').val(row['oStagiaire']['client_zRue']+' '+row['oStagiaire']['client_zVille']+' '+row['oStagiaire']['client_zCP']);
					$('.datedtcm_event_rdv').val(row['oEvenement']['evenement_zDateHeureDebutFr']) ;
				});		
			}else{
				alert("Erreur lors du chargement de l'evenement!!") ;
			}
		}
	);

	$('#rechercherStagiaire').click(
		function (){
			var evenement_zStagiaire = $('.evenement_zStagiairepop').val();
			if (evenement_zStagiaire == "")
			{
				evenement_zStagiaire = " "; 
			}
			if (evenement_zStagiaire != ""){
				$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:rechercherStagiaire", zStagiaire:evenement_zStagiaire}, function(datas){
					if(datas.length>0){
						$('#div-stagiaire-liste').show();
						$('#stagiaire-liste').html('');
						var html = '<option value="0">Séléctionner le projet</option>';
						for(i=0; i< datas.length; i++){
							html += '<option value="' + datas[i]["client_id"] +'">&nbsp;' + datas[i]["client_zNom"] + '&nbsp;' + datas[i]["client_zPrenom"] + '&nbsp;&nbsp;[' + datas[i]["client_zTel"] + ']&nbsp;&nbsp;[' + datas[i]["societe_zNom"] + ']&nbsp;&nbsp;[' + datas[i]["client_zVille"] + ']</option>';
						}
						$('#stagiaire-liste').html(html);
						$('#stagiaire-liste').val(0);
					}else{
						$('#div-stagiaire-liste').show();
						$('#stagiaire-liste').html('');
						var html = '<option value="0">Aucun projet</option>';
						$('#stagiaire-liste').html(html);
						$('#div-stagiaire-liste').hide();
						$('#evenement_zStagiaire').val('');
						alert('Aucun projet trouvé correspondant à votre recherche. Veuillez saisir un autre nom');
					}					 
				});
			}else{
				alert('Veuillez enter un nom du projet');
			}
			return false;
		}
	);

	$('#stagiaire-liste').click(
		function (){
			var iStagiaire = $('#stagiaire-liste').val();
			if(iStagiaire > 0){
				$('#evenement_iStagiaire').val(iStagiaire);
				$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:chargeParId", iStagiaireId:iStagiaire}, function(datas){
					$('#div-stagiaire-liste').hide();
					$('.evenement_zStagiairepop').val('');
					$('#evenement_zLibelle').val('');
					$('#evenement_zLibelle').val(datas["client_zNom"]); 

					var html = datas["client_zNom"] + ' ' + datas["client_zPrenom"];
					$('.evenement_zStagiairepop').val(html);
					$('.iStagiairePop').val(datas["client_id"]); 
					$('#p-txtville').show();
					$('#p-txtsociete').show();
					$('#p-txtphone').show();
					$('#p-txtmail').show();

					$('#txtphone').val(datas["client_zTel"]);
					$('#txtsociete').val(datas["societe_zNom"]);
					$('#txtville').val(datas["client_zVille"]);
					$('#txtmail').val(datas["client_zMail"]);
				});
			}
		}
	);

	$('.submitFormulaire').click(
		function(){
			// Les parametres 
			var dtcm_event_rdv = $('#dtcm_event_rdv').val();
			var dtcm_event_rdv1 = $('#dtcm_event_rdv1').val();
			var evenement_origine = $('#evenement_origine').val();
			var evenement_iTypeEvenementId = $('#evenement_iTypeEvenementId').val();
			var evenement_stagiaire = $('#evenement_stagiaire').val();
			// Infos event 
			var iEventId = $('.classEvenementId').val() ;
			var iTypeEventId = $('.classTypeEvenementId').val() ;
			var zEventDesc = $('.classDescription').val() ;
			var iEventStagiaireId = $('.classStagiaireId').val() ;

			if (iEventId <= 0)
			{
				alert("Erreur, impossible d'enregistrer l'evenement!!!");
			}else if (iTypeEventId == 0)
			{
				alert("Merci de selectionner le type d'evenement!!!");
			}else{
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:savePopEventListing", iEventId:iEventId, iTypeEventId:iTypeEventId, zEventDesc:zEventDesc, iEventStagiaireId:iEventStagiaireId}, function(row){
					if (row == iEventId)
					{
						window.location.href = $("#action1").val() + '&dtcm_event_rdv='+dtcm_event_rdv+'&dtcm_event_rdv1='+dtcm_event_rdv1+'&evenement_origine='+evenement_origine+'&evenement_iTypeEvenementId='+evenement_iTypeEvenementId+'&evenement_stagiaire='+evenement_stagiaire;
					}else{
						alert("Erreur lors de l'enregistrement de l'evenement!!!") ;
					}
				});		
			}
		}
	);
	$('.print').hide();
});

var autoCompleteJson = function(data){
	var parsed=[];
	for (var i=0; i<data.length;i++){
		var row=data[i];
		parsed.push({
			data: row,
			value: row["client_zNom"] + ' ' + row["client_zPrenom"]+' (' + row["client_zTel"] + ')',
			result: row["client_zNom"] + ' ' + row["client_zPrenom"]
		});
	}
	return parsed;
}
var autoCompleteJson1 = function(data){
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

function addIdEventToDelete (_iEventId){
	var iEventIdChecked = $('.suppr_'+_iEventId).attr('checked')?1:0;
	if (iEventIdChecked == 1){
		if ($('#eventToDelete').val() == "")
		{
			$('#eventToDelete').val(_iEventId); 
		}else{
			var newVal = $('#eventToDelete').val() + '@_@' + _iEventId; 
			$('#eventToDelete').val(newVal);
		}
	}else{
		if ($('#eventToDelete').val() != "")
		{
			var val=$('#eventToDelete').val(); 
			var tVal = val.split('@_@');
			for(i=0; i<tVal.length; i++){
				if (tVal[i] == _iEventId){
					tVal.splice(i, 1);
				}
			}
			tVal.sort();
			var zNewVal = ""; 
			for(i=0; i<tVal.length; i++){
				if (tVal[i] != ""){
					if (zNewVal == ""){
						zNewVal = tVal[i];
					}else{
						zNewVal = zNewVal + "@_@" + tVal[i];
					}
				}
			}
			$('#eventToDelete').val(zNewVal); 
		}
	}	
}

function suppressionMultipleEvent (){
	document.location.href = j_basepath + "index.php?module=evenement&action=FoEvenement:suppressionMultipleEvent&zListeEvenementId=" + $('#eventToDelete').val() + "&zDateDebut=" + $('#zDateDebut').val() + "&zDateFin=" + $('#zDateFin').val() + "&iTypeEvenement=" + $('#iTypeEvenement').val() + "&iStagiaire=" + $('#iStagiaire').val();
}
function addEventEventlisting (){
	document.location.href = $('#urlAddEvent').val() + "&prec=1&debut="+$('#dtcm_event_rdv').val()+"&fin="+$('#dtcm_event_rdv1').val();
}
function submitFormRecherche(){
	var fin = $('#dtcm_event_rdv1').val();
	var debut = $('#dtcm_event_rdv').val();
	var zUrl = $('#urlCalculDateDiff').val();
	if($('#dtcm_event_rdv1').val()!=""){
		$.ajax({
			type: "POST",
			url: zUrl,
			data: {
				'zDebut':debut,
				'zFin':fin
			},
			success: function(response){
				if (response < 0)
				{
					alert('La date de début doit être antérieur à la date de fin');
				}else{
					$('#edit_form').attr({'action':$('#action1').val()}) ;
					$('#edit_form').submit();
				}
			}
		 });
	}else{
		$('#edit_form').attr({'action':$('#action1').val()}) ;
		$('#edit_form').submit();
	}
	return false;
}
function imprimerEventListing(){
$('.print').show();
window.print();
$('.print').hide();
}
</script>
{/literal}
<div class="main-page noPrint">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear">
				<form id="edit_form" action="#" method="POST" enctype="multipart/form-data" tmt:validate="true">
					<input type="hidden" name="action1" id="action1" value="{jurl 'evenement~FoEvenement:getEventListing', array(), false}"/>
					<input type="hidden" name="urlAddEvent" id="urlAddEvent" value="{jurl 'evenement~FoEvenement:add', array(), false}"/>
					<input type="hidden" name="evenement_id" id="evenement_id" />
					<input type="hidden" name="urlCalculDateDiff" id="urlCalculDateDiff" value="{jurl 'evenement~FoEvenement:calculDateDiff'}"/>
					<h2>Recherche d'évènement</h2>
					<p class="civil clear">
						<label style="width:200px;">Date du</label>
						<input type="text" class="date text" id="dtcm_event_rdv" name="dtcm_event_rdv" style="width:100px;" value="{if isset ($toParams[0]->zDateDebut)}{$toParams[0]->zDateDebut}{/if}" readonly="readonly"/>
					</p>
					<p class="civil clear">
						<label style="width:200px;">Jusqu'au</label>
						<input type="text" class="date text" id="dtcm_event_rdv1" name="dtcm_event_rdv1" style="width:100px;" value="{if isset ($toParams[0]->zDateFin)}{$toParams[0]->zDateFin}{/if}" readonly="readonly"/>
					</p>
					<p class="clear">
						<label style="width:200px;">Origine</label>
						<select class="text"  style="width:300px;" name="evenement_origine" id="evenement_origine" >
							<option value="0">----------------------------------Tous----------------------------------</option>
							<option value="1" {if isset ($toParams[0]->evenement_origine) && $toParams[0]->evenement_origine == 1}selected="selected"{/if}>Auto-planification</option>
							<option value="2" {if isset ($toParams[0]->evenement_origine) && $toParams[0]->evenement_origine == 2}selected="selected"{/if}>Agenda</option>
						</select>
					</p>
					<p class="clear">
						<label style="width:200px;">Type de l'évènement </label>
						<select class="text" style="width:300px;" name="evenement_iTypeEvenementId" id="evenement_iTypeEvenementId" >
						<option value="0">-----------------------------Séléctionner-----------------------------</option>
						{foreach $toTypeEvenement as $oTypeEvenement}
							{if $oTypeEvenement->typeevenements_id != ID_TYPE_EVENEMENT_DISPONIBLE}
								<option value="{$oTypeEvenement->typeevenements_id}" {if isset ($toParams[0]->iTypeEvenement) && $toParams[0]->iTypeEvenement == $oTypeEvenement->typeevenements_id}selected="selected"{/if}>{$oTypeEvenement->typeevenements_zLibelle}</option>
							{/if}
						{/foreach}
						</select>
					</p>
					<p class="clear">
						<label style="width:200px;">Projet</label>
						<input type="hidden" name="evenement_stagiaire" id="evenement_stagiaire" value="{if isset ($toParams[0]->evenement_stagiaire)}{$toParams[0]->evenement_stagiaire}{/if}" />
						<input style="width:300px;" type="text" class="text" name="evenement_zStagiaire" id="evenement_zStagiaire" value="{if isset ($toParams[0]->evenement_zStagiaire)}{$toParams[0]->evenement_zStagiaire}{/if}"/>
					</p>
					<div class="input" style="width:295px;">
						<input type="button" value="Ajouter un évènement" class="boutonform" onclick="addEventEventlisting();" />
						<input type="button" value="Rechercher" class="boutonform" onclick="submitFormRecherche();" />
					</div>
					<div class="input" style="width:480px;">
						<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
					</div>
			</form>
			</div>
		</div>
		<div class="content">
			<div class="formevent listeclients clear" style="width:943px">
			<form id="edit_formList" action="{jurl 'evenement~FoEvenement:exportEventListing', array(), false}" method="POST" enctype="multipart/form-data">
			<input type="hidden" value="" id="eventToDelete" name="eventToDelete"/>
			<input type="hidden" value="{$toParams[0]->zDateDebut}" id="zDateDebut" name="zDateDebut"/>
			<input type="hidden" value="{$toParams[0]->zDateFin}" id="zDateFin" name="zDateFin"/>
			<input type="hidden" value="{$toParams[0]->iTypeEvenement}" id="iTypeEvenement" name="iTypeEvenement"/>
			<input type="hidden" value="{$toParams[0]->iStagiaire}" id="iStagiaire" name="iStagiaire"/>
			<input type="hidden" value="{$toParams[0]->evenement_origine}" id="evenement_stagiaire " name="evenement_origine"/>
			<input type="hidden" value="{$toParams[0]->iCheckDate}" id="iCheckDate" name="iCheckDate"/>

			<h2>Liste d'évènements pour {$oUtilisateur->utilisateur_zNom} {$oUtilisateur->utilisateur_zPrenom}</h2>
			{if isset($toParams[0]->iCheckDate) && $toParams[0]->iCheckDate == 0}
			<h3><span class="title">De</span> <span>{$toParams[0]->zDateDebut}</span> <span class="title">à</span> <span>{$toParams[0]->zDateFin}</span></h3>
			{/if}
			{if isset($toParams[0]->evenement_origine) && $toParams[0]->evenement_origine != 0}
				{if $toParams[0]->evenement_origine == 1}
				<h3><span class="title">Origine : </span><span>Auto-planification</span></h3>
				{else}
				<h3><span class="title">Origine : </span><span>Agenda</span></h3>
				{/if}
			{else}
				<h3><span class="title">Origine : </span><span>Tous</span></h3>
			{/if}	
			
			{if $toParams[0]->iTypeEvenement == 0}
				<h3><span class="title">Types d'événement : </span><span>Tous les Types</span></h3>
			{else}
				<h3><span class="title">Types d'événement : </span><span>{if isset($toEvenement[0]->typeevenements_zLibelle)}{$toEvenement[0]->typeevenements_zLibelle}{else}{$toTypeEvenementSelected[0]->typeevenements_zLibelle}{/if}</span></h3>
			{/if}
			{if isset($toParams[0]->evenement_zSociete) && $toParams[0]->evenement_zSociete != ""}
				<h3><span class="title">Catégorie : </span><span>{$toParams[0]->evenement_zSociete}</span></h3>
			{/if}
			<h3 class="last"><span class="title">Nombre d'événement trouvés :</span> <span>{$iResTotal}</span></h3>

			<div class="tabevent">
				<table cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<th class="col1" style="width:180px;"><span>Date</span></th>
							<th class="col2" style="width:auto;">Durée</th>
							<th class="col3" style="width:auto;">Type d'événement</th>
							<th class="col4" style="width:auto;">Projet</th>
							<th class="col5" style="width:90px;text-align:center;">Actions</th>
						</tr>
						{assign $i = 1}
						{foreach $toEvenement as $oEvenement}
						<tr class="extra{$i++%2+1}" style="height: 30px;">
							<td class="col1">
								<span>
									<a href="#" class="modifierEvent" ieventid="{$oEvenement->evenement_id}">
										{$oEvenement->evenement_zDateJoursDeLaSemaine}&nbsp;
										{$oEvenement->evenement_zDateHeureDebut|date_format:'%d/%m/%Y %H:%M'}
									</a>
								</span>
							</td>
							<td class="col2" style="">&nbsp;&nbsp;{$oEvenement->typeevenements_iDure} {if $oEvenement->typeevenements_iDureeTypeId == 1}h{else}mn{/if}</td>
							<td class="col3" style="width:auto;">{$oEvenement->typeevenements_zLibelle}</td>
							<td class="col4" style="width:auto;">
								{if isset($oEvenement->oWpPost->ID) && $oEvenement->oWpPost->ID > 0}
									<a href="{$oEvenement->oWpPost->guid}" id="imgInfoStagiaire" title="Detail du Projets" target="_blank">{$oEvenement->oWpPost->post_title}</a>
								{else}
									<p style="text-align:center;"> - </p>
								{/if}
							</td>
							<td class="col5" style="width:90px;text-align:center;">
								<input type="checkbox" class="suppr_{$oEvenement->evenement_id}" name="suppr_{$oEvenement->evenement_id}" id="suppr_{$oEvenement->evenement_id}" onclick="javascript:addIdEventToDelete({$oEvenement->evenement_id})">&nbsp;&nbsp;&nbsp;<a href="{jurl 'evenement~FoEvenement:deleteEvent', array('iEvenementId'=>$oEvenement->evenement_id, 'iOption'=>1, 'zDateDebut'=>$toParams[0]->zDateDebut, 'zDateFin'=>$toParams[0]->zDateFin, 'iTypeEvenement'=>$toParams[0]->iTypeEvenement, 'iStagiaire'=>$toParams[0]->iStagiaire), false}"><img src="{$j_basepath}design/front/images/design/pictos/sub.png" alt="Supprimer" title="Supprimer" border="0" /></a>&nbsp;&nbsp;&nbsp;<a target="_blank" href="{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>$oEvenement->evenement_id, 'zDate'=>$oEvenement->evenement_zDateDebut, 'iTime'=>$oEvenement->evenement_zHeureDebut), false}"><img src="{$j_basepath}design/front/images/design/pictos/edit.png" alt="Editer" title="Editer" border="0" /></a>
							</td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
			<div class="input" style="width:600px;">
				<input type="button" value="Imprimer" class="boutonform submitForm" onclick="javascript:imprimerEventListing();"/>
				<input type="button" value="Supprimer les évènements séléctionnés" class="boutonform" onclick="javascript:suppressionMultipleEvent()"/>
				<input type="button" value="Exporter vers Excel" class="boutonform submitForm submitFormExport"/>
				<input type="button" value="Exporter vers Outlook" class="boutonform submitForm submitFormExportIcs"/>
			</div>
		</p>
	</form>
			</div>
		</div>
	</div>
</div>

<!--PRINT-->
	<div class="tabevent print">
		<table>
			<tbody>
				<tr style="background-color:red;">
					<th class="col1" style="width:180px;"><span>Date</span></th>
					<th class="col2" style="width:50px;">Durée</th>
					<th class="col3" style="width:auto;">&nbsp;&nbsp;&nbsp;&nbsp;Type d'événement</th>
					<th class="col4" style="width:auto;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;Stagiares</th>
					<th class="col5" style="width:auto;">Description de l'événement</th>
				</tr>
				{assign $i = 1}
				{foreach $toEvenementPrint as $oEvenement}
				<tr class="extra{$i++%2+1}" style="height: 30px;background-color:#899EB0;" >
					<td class="col1">
						<span>
							{$oEvenement->evenement_zDateJoursDeLaSemaine}&nbsp;
							{$oEvenement->evenement_zDateHeureDebut|date_format:'%d/%m/%Y %H:%M'}
						</span>
					</td>
					<td class="col2" style="width:50px;">&nbsp;&nbsp;{$oEvenement->typeevenements_iDure} {if $oEvenement->typeevenements_iDureeTypeId == 1}h{else}mn{/if}</td>
					<td class="col3" style="width:auto;">&nbsp;&nbsp;&nbsp;&nbsp;{if $oEvenement->typeevenements_id == ID_TYPE_EVENEMENT_DISPONIBLE || $oEvenement->typeevenements_id == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE}<p style="text-align:center;">-------</p>{else}{$oEvenement->typeevenements_zLibelle}{/if}</td>
					<td class="col4" style="width:auto;">
						{if $oEvenement->client_id > 0}
							<a href="#">&nbsp;&nbsp;&nbsp;&nbsp;{$oEvenement->client_zNom} {$oEvenement->client_zPrenom}</a>
							<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;Numero :</strong> {$oEvenement->client_id}<br /> 
							{if isset($oEvenement->societe_zNom) && $oEvenement->societe_zNom !=""}
								<strong>&nbsp;&nbsp;&nbsp;&nbsp;Catégorie :</strong> {$oEvenement->societe_zNom}<br />
							{/if}  
							{if isset($oEvenement->client_zTel) && $oEvenement->client_zTel !=""}
								<strong>&nbsp;&nbsp;&nbsp;&nbsp;Tél :</strong> {$oEvenement->client_zTel}<br />
							{/if}
							{if isset($oEvenement->client_zPortable) && $oEvenement->client_zPortable !=""}
								<strong>&nbsp;&nbsp;&nbsp;&nbsp;Portable :</strong> {$oEvenement->client_zPortable}<br />
							{/if}  
							{if isset($oEvenement->evenement_zContactTel) && $oEvenement->evenement_zContactTel !=""}
								<strong>&nbsp;&nbsp;&nbsp;&nbsp;Tél pour le Jour :</strong> {$oEvenement->evenement_zContactTel}</p>
							{/if}
						{else}
							<p style="text-align:center;">-------</p>
						{/if}
					</td>
					<td class="col5"style="width:auto;">{$oEvenement->evenement_zDescription}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
<!--PRINT-->
{$footer}
<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>

<!--POPUOP-->
<div class="pop-up popModifierEvent formevent clear" id="periodepop" style="background-color:#E9E9E9; display: block; top: 149px; left: 707.5px;width:600px;">
<form id="edit_form" action="#" >
	<input class="classEvenementId" type="hidden" name="evenement_id" id="evenement_id" value="0" />
	<h2>Création / Modification d’évènement</h2>
	<a class="fermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
	<p class="clear"><br /></p> 
	<p class="clear">
		<label>Types d’évènement *</label>
		<select name="evenement_iTypeEvenementId" class="text classTypeEvenementId" id="evenement_iTypeEvenementId">
			<option value="0">----------------------Séléctionner----------------------</option>
			{foreach $toTypeEvenement as $oTypeEvenement}
				<option value="{$oTypeEvenement->typeevenements_id}" {if isset($oEvenement->typeevenements_id) && $oEvenement->typeevenements_id == $oTypeEvenement->typeevenements_id}selected="selected"{/if}>{$oTypeEvenement->typeevenements_zLibelle}</option>
			{/foreach}
		</select>
	</p>
	<p class="clear">
		<label>Description</label>
		<textarea style="height:auto" name="evenement_zDescription" id="evenement_zDescription" class="classDescription"></textarea>
	</p> 
	<p class="clear">
		<label>Projet</label>
		<input type="hidden" class="iStagiairePop classStagiaireId" name="evenement_iStagiaire" id="evenement_iStagiaire" value="0" />
		<input type="hidden" name="urlTraitementStagiaireRecherche" id="urlTraitementStagiaireRecherche" value="{jurl 'client~FoClient:rechercherStagiaire'}" />
		<input type="hidden" name="urlAjoutStagiaire" id="urlAjoutStagiaire" value="" />
		<input style="width:296px;" type="text" class="text evenement_zStagiairepop" name="evenement_zStagiaire" id="evenement_zStagiaire" value="" />
		&nbsp;<a href="#" title="Rechercher" id="rechercherStagiaire">
			<img src="{$j_basepath}design/front/images/design/rechercher.png" alt="Ajouter un projet" />
		</a>
	</p>
	<p class="clear" id="div-stagiaire-liste">
		<label for="dtcm_event_project">&nbsp;</label>
		<select style="width:400px;" name="stagiaire-liste" id="stagiaire-liste" size="5" url="">
			<option></option>
		</select>
	</p>
	<p class="clear" id="p-txtmail"> 
		<label>Email</label>
		<input type="text" name="txtmail" id="txtmail" class="text" readonly="readonly"/>
	</p> 
	<p class="clear" id="p-txtphone"> 
		<label>Téléphone</label>
		<input type="text" name="txtphone" id="txtphone" class="text" readonly="readonly"/>
	</p> 
	<p class="clear" id="p-txtsociete"> 
		<label>Catégorie</label>
		<input type="text" name="txtsociete" id="txtsociete" class="text" readonly="readonly"/>
	</p> 
	<p class="clear" id="p-txtville"> 
		<label>Adresse</label>
		<input type="text" name="txtville" id="txtville" class="text" readonly="readonly"/>
	</p> 

	<p class="rdv clear">
		<label>Rendez vous</label>
		<input type="text" class="datedtcm_event_rdv text" id="dtcm_event_rdv" readonly="readonly" name="dtcm_event_rdv" value=""/>
	</p> 
	<div class="input">
		<a href="#" class="fermerPop"><input type="button" value="Annuler" class="boutonform" /></a>
		<input type="button" value="Modifier" class="boutonform submitFormulaire" />
	</div>
	<div class="input" style="width:480px;">
		<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
	</div>

</form>
</div>

<!--POPUOP-->