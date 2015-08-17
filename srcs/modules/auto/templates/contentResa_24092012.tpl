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
});
{/literal}
</script>
    <h2 class="demoHeaders">Réservation pour test de début de stage / <i>Booking for test placement begins</i></h2>
    <div id="accordion">
<br />
<br />
			{assign $oEvent = $toEvents[0]}
    		<h3><a href="#">Votre réservation est prévue pour / <i>Your test is planned</i></a></h3>
            <p>
				<label>Le :</label> {$oEvent->zDateString} à {$oEvent->zHeureString} / <i><label>On :</label> {$oEvent->zDateStringEn} at {$oEvent->zHeureStringEn}</i><br />
<br />
				<label>Avec / <i>With</i> :</label> 	{if $oEvent->utilisateur_iCivilite == CIVILITE_FEMME}Mme{else}Mr{/if} {$oEvent->utilisateur_zPrenom} {$oEvent->utilisateur_zNom} ({$oEvent->type_zLibelle})
<br />
				<br />Votre numéro de téléphone pour le test est le / <i>Your phone number for the test is</i> : {$oEvent->evenement_zContactTel}
<br />
				{if isset($iIsModifiable) && $iIsModifiable == 1}<br />Pour modifier cette date de rendez-vous, / <i>To change the date of appointment,</i> <a href="{jurl 'auto~default:liberer', array('id'=>$oEvent->evenement_id, 'm'=>1, 'p'=>$p)}" title="Modifier la date de la reservation pour le test de début de stage">cliquer ici / <i>click here</i></a><br />{/if}
				<br /><b>En cas d'empêchement merci de contacter Forma2plus au  01 47 31 88 42</b>
				<br /><b><i>In case you need to postpone the test, please contact Forma2plus at 01 47 31 88 42</i></b>
<br />

            </p>
    </div>
	<div id="loader"></div>
<div id="loader"><img src="{$j_basepath}design/commun/images/ajax-loader.gif"/></div>