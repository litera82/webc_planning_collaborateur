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

	var url=j_basepath + "index.php?module=evenement&action=FoEvenement:autocompleteStagiaire";
	$('#evenement_zStagiaire').autocomplete(url,{
		minChars: 0,
		autoFill: false,
		scroll: true,
		scrollHeight: 300,
		dataType: "json" ,
		parse : autoCompleteJson,
		formatItem: function(row) {
			return row["client_zNom"] + ' ' + row["client_zPrenom"] +'&nbsp;&nbsp;[' + row["client_zTel"] + ']'+'&nbsp;&nbsp;[' + row["societe_zNom"] + ']'+'&nbsp;&nbsp;[' + row["client_zVille"] + ']';
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
</script>
{/literal}
<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear">
					<form id="edit_form" action="#" method="POST" enctype="multipart/form-data" tmt:validate="true" >
					<input type="hidden" name="evenement_id" id="evenement_id" />
					<input type="hidden" name="action1" id="action1" value="{jurl 'evenement~FoEvenement:getEventListing', array(), false}"/>
					<input type="hidden" name="urlCalculDateDiff" id="urlCalculDateDiff" value="{jurl 'evenement~FoEvenement:calculDateDiff'}"/>
					<h2>Recherche d'évènement</h2>
					<p class="civil clear">
						<label style="width:200px;">Date du</label>
						<input type="text" class="date text" id="dtcm_event_rdv" name="dtcm_event_rdv" style="width:100px;" value="{$now}" readonly="readonly"/>
					</p>
					<p class="civil clear">
						<label style="width:200px;">Jusqu'au</label>
						<input type="text" class="date1 text" id="dtcm_event_rdv1" name="dtcm_event_rdv1" value="" style="width:100px;" readonly="readonly"/>
					</p>
					<p class="clear">
						<label style="width:200px;">Origine</label>
						<select class="text"  style="width:300px;" name="evenement_origine" id="evenement_origine" >
							<option value="0">----------------------------------Tous----------------------------------</option>
							<option value="1">Auto-planification</option>
							<option value="2">Agenda</option>
						</select>
					</p>
					<p class="clear">
						<label style="width:200px;">Type de l'évènement </label>
						<select class="text" style="width:300px;" name="evenement_iTypeEvenementId" id="evenement_iTypeEvenementId" >
						<option value="0">-----------------------------Séléctionner-----------------------------</option>
						{foreach $toTypeEvenement as $oTypeEvenement}
							{if $oTypeEvenement->typeevenements_id != ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE || $oTypeEvenement->typeevenements_id != ID_TYPE_EVENEMENT_DISPONIBLE}
								<option value="{$oTypeEvenement->typeevenements_id}">{$oTypeEvenement->typeevenements_zLibelle}</option>
							{/if}
						{/foreach}
						</select>
					</p>
					<p class="clear">
						<label style="width:200px;">Projet</label>
						<input type="hidden" name="evenement_stagiaire" id="evenement_stagiaire" value="0" />
						<input style="width:300px;" type="text" class="text" name="evenement_zStagiaire" id="evenement_zStagiaire"/>
					</p>
					<div class="input" style="width:145px;">
						<input type="button" value="Rechercher" class="boutonform" onclick="submitFormRecherche();" />
					</div>
					<div class="input" style="width:480px;">
						<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
					</div>
			</form>
			</div>
		</div>
	</div>
</div>
{$footer}
