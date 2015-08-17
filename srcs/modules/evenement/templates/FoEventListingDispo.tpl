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

});

function submitFormRechercheApprocheListeDispo(){
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
					$('#edit_form').attr({'action':$('#action2').val()}) ;
					$('#edit_form').submit();
				}
			}
		 });
	}else{
		$('#edit_form').attr({'action':$('#action2').val()}) ;
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
					<input type="hidden" name="action2" id="action2" value="{jurl 'evenement~FoEvenement:getEventListingDispo', array(), false}"/>
					<input type="hidden" name="urlCalculDateDiff" id="urlCalculDateDiff" value="{jurl 'evenement~FoEvenement:calculDateDiff'}"/>

					<h2>Recherche d'évènement</h2>
					<p class="civil clear">
						<label style="width:200px;">Date du</label>
						<input type="text" class="date text" id="dtcm_event_rdv" name="dtcm_event_rdv" style="width:300px;" value="{$now}" readonly="readonly"/>
					</p>
					<p class="civil clear">
						<label style="width:200px;">Jusqu'au</label>
						<input type="text" class="date1 text" id="dtcm_event_rdv1" name="dtcm_event_rdv1" value="" style="width:300px;" readonly="readonly"/>
					</p>
					<div class="input" style="margin-right:133px;width:212px;">
						<input type="button" value="Rechercher" class="boutonform" onclick="submitFormRechercheApprocheListeDispo();" />
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
