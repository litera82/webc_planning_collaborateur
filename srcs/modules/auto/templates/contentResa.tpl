<script type="text/javascript">
{literal}
$(function(){
	$('#errorMsg').attr ('style', 'display:none;');
	var ancienTelResEventId = $('#ancienTelResEventId').val();
	$('#newTelRes').val(ancienTelResEventId);

	$('.modifNumTel').click(
		function (){
			$('.blocModifNumTel').attr('style', 'display:block;text-align:center;');
			var ancienTelResEventId = $('#ancienTelResEventId').val();
			$('#newTelRes').val(ancienTelResEventId);
			$('#errorMsg').attr ('style', 'display:none;');
			return  false;
		}
	);
	$('.annulerModif').click(
		function (){
			$('.blocModifNumTel').attr('style', 'display:none;');
			$('#errorMsg').attr ('style', 'display:none;');
			return  false;
		}
	);
	$('.saveModif').click(
		function (){
			if ($('#newTelRes').val() == ""){
				$('#errorMsg').attr ('style', 'display:block; color:red;');
				return false;
			}else{
				$('#formNewTelRes').submit();
			}
		}
	);
	$('.modifierNum').click(
		function (){
			var context = $("body") ;
			zMessage  = 'Vous êtes sur le point de faire de modifier le numero de téléphone de votre réservation <br/>' ;
			zMessage += 'Le confirmez vous ? / <i>Do you confirm?</i><br/><br/>' ;
			var telTestOld = $('.evenement_zContactTel').val() ;
			$('.champsTelTest').val(telTestOld) ;
			$('#validateTips', context).html(zMessage);
			$('#ModalResa', context).dialog({
				title: 'Réservation / Booking',
				modal: true,
				buttons: {
				  'Enregistrer': function() {
						$(this).dialog('close');
						var telTestNew = $('.champsTelTest').val() ;
						var id = $('.evenement_id').val() ;
						if (id > 0 && telTestOld != "" && telTestNew != ""){
							if (telTestOld != telTestNew){
								alert("Un email de confirmation vous a été envoyé\nA confirmation email has just been sent to you.");
								$.loader({width:221, height:20, content:'<img src="{/literal}{$j_basepath}design/commun/images/ajax-loader.gif"/> {literal}'});
								$.ajax({
									 type: "POST",
									 url: $("#urlTraitementModifierNum").val(),
									 data: {
										 "id":id, 
										 "telTest":telTestNew
									 },
									 dataType: "json",
									 async: false,
									 success: function(resultat){
										window.location.reload();
									 }
								});								
							}else{
								$(this).dialog('close');
							}
						}else{
							alert('Impossible de modifier le numero de téléphone\nCan not change phone number');
						}
				  },
				  'Annuler': function() {$(this).dialog('close');}
				},
				close: function() {},
				width: 'auto'
			});
		}
	);
});
{/literal}
</script>
    <h2 class="demoHeaders">Réservation pour test de début de stage / <i>Booking for test placement begins</i></h2>
    <div id="accordion">
<br />
<br />
			{*assign $oEvent = $toEvents[0]*}
			{foreach $toEvents as $oEvent}
    		<h3><a href="#">Votre réservation est prévue pour / <i>Your test is planned</i></a></h3>
            <p>
				<input type="hidden" class="urlTraitementModifierNum" id="urlTraitementModifierNum" name="urlTraitementModifierNum" value="{jurl 'auto~default:traitementModifierNum'}" />
				<input type="hidden" class="evenement_zContactTel" id="evenement_zContactTel" name="evenement_zContactTel" value="{$oEvent->evenement_zContactTel}" />
				<input type="hidden" class="evenement_id" id="evenement_id" name="evenement_id" value="{$oEvent->evenement_id}" />
				<label>Le :</label> {$oEvent->zDateString} à {$oEvent->zHeureString} / <i><label>On :</label> {$oEvent->zDateStringEn} at {$oEvent->zHeureStringEn}</i><br />
<br />
				<label>Avec / <i>With</i> :</label> 	{if $oEvent->utilisateur_iCivilite == CIVILITE_FEMME}Mme{else}Mr{/if} {$oEvent->utilisateur_zPrenom} {$oEvent->utilisateur_zNom} ({$oEvent->type_zLibelle})
<br />
				<br />Votre numéro de téléphone pour le test est le / <i>Your phone number for the test is</i> : {$oEvent->evenement_zContactTel} <button class="modifierNum" class="modifierNum">Modifier mon numero de téléphone / Change my phone number</button>
<br />
				{if isset($iIsModifiable) && $iIsModifiable == 1}<br />Pour modifier cette date de rendez-vous, / <i>To change the date of appointment,</i> <a href="{jurl 'auto~default:liberer', array('id'=>$oEvent->evenement_id, 'm'=>1, 'p'=>$p)}" title="Modifier la date de la reservation pour le test de début de stage">cliquer ici / <i>click here</i></a><br />{/if}
				<br /><b>En cas d'empêchement merci de contacter Forma2plus au  01 47 31 88 42</b>
				<br /><b><i>In case you need to postpone the test, please contact Forma2plus at 01 47 31 88 42</i></b>
<br />
			{/foreach}
            </p>
    </div>
	<div id="loader"></div>

<div style="visibility:hidden;font-family:Arial,sans-serif;">
	<div id="ModalResa">
	  <p class="validateTips" id="validateTips" style="text-align:center;"></p>
	  <form>
			<fieldset>
				<label for="name">Votre numéro de téléphone pour le test est le :</label>
				<input type="text" name="telTest" id="telTest" class="text ui-widget-content ui-corner-all champsTelTest" value="{$oEvent->evenement_zContactTel}" />, sinon, veuillez le modifier
				<br /><label for="name"><i>Your telephone number for the test is :</i></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>, if not, please correct it</i>
			</fieldset>
	  </form>
	</div>
</div>

<div id="loader"><img src="{$j_basepath}design/commun/images/ajax-loader.gif"/></div>