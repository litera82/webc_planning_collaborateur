<script type="text/javascript">
{literal}
function autoreload(context, iProv){
	if (iProv != 0)
	{
		window.location.reload();
	}
    $('.reserver', context).click(function(){
        var id = $(this).attr('eventId') ;
        var m = $(this).attr('m') ;
        var p = $(this).attr('p') ;
		var telTest = $('.champsTelTest').val() ;
		var dateEvent = $(this).attr('dateEvent');
		var heureEvent = $(this).attr('heureEvent');
		var dateEventEn = $(this).attr('dateEventEn');
		var heureEventEn = $(this).attr('heureEventEn');

        var zMessageInfo = $(this).parent('div').prev('h3').find('a').text() ;

		zMessage  = 'Vous êtes sur le point de faire une réservation <br/>' ;
        zMessage += 'pour un test d\'évaluation le ' + dateEvent + ' à ' + heureEvent + ' <br/><br/>' ;

		zMessage += '<i>You are about to make a booking for an assessment test <br/>' ;
        zMessage += dateEventEn + ' at ' + heureEventEn + ' <br/><br/></i>' ;

        zMessage += 'Le confirmez vous ? / <i>Do you confirm?</i><br/><br/>' ;
		
		$('#validateTips', context).html(zMessage);

		$('#ModalResa', context).dialog({
            title: 'Réservation / Booking',
            modal: true,
            buttons: {
              'Enregistrer': function() {
            	$(this).dialog('close');
				var telTest = $('.champsTelTest').val() ;
				if (id > 0 && telTest != ""){
					alert("Merci d'avoir fait votre réservation. Un email de confirmation vous a été envoyé\nWe thank you for your booking. A confirmation email has just been sent to you.");
					$.loader({width:221, height:20, content:'<img src="{/literal}{$j_basepath}design/commun/images/ajax-loader.gif"/>{literal}'});
					$.post("{/literal}{jurl 'auto~default:reserver', array(), false}{literal}", {id:id, telTest:telTest, m:m, p:p}, function(){
						 $('.content', context).load("{/literal}{jurl 'commun~CommunBo:getZone', array('zone'=>'auto~contentResa'), false}{literal}", {id:id}, function(){
							   autoreload($(this)) ;
							   doOnLoad($(this)) ;
							   $.loader('close') ;
						 }) ;
					}) ;
				}else{
					alert('Impossible de reserver la plage horaire\nUnable to reserve the time slot');
				}
              },
              'Annuler': function() {
										autoreload($(this), 1) ; 
										doOnLoad($(this)) ; 
										$(this).dialog('close');
									}
            },
            close: function() {/*$(this).remove();autoreload($(this), 1) ;*/},
            width: 'auto'
          });
     });
}
$(function(){
	autoreload ($("body"), iProv=0) ;
	$( "#accordion" ).accordion({ active: 0 });
	$('#accordion').accordion({ collapsible: true });

	$( ".choiseOther").click(
		function (){
			$('#accordion').accordion("activate" , false);
			return false;
		}
	);

	$('.date1').datepicker({
		hour: 09,
		numberOfMonths: 3
	});

	$('.date2').datepicker({
		hour: 09,
		numberOfMonths: 3
	});

	$('.date3').datepicker({
		hour: 09,
		numberOfMonths: 3
	});

	$('.heureDebut1').timepicker({
		showSecond: true,
		timeFormat: 'hh:mm:ss',
		stepHour: 2,
		stepMinute: 10,
		stepSecond: 10,
		timeOnlyTitle: "Choisir l'heure de début",
		timeText: 'Heure',
		hourText: 'Heure',
		minuteText: 'Minute',
		secondText: 'Second',
		currentText: 'Maintenant',
		closeText: 'Choisir',
		hourMin: 7,
		hourMax: 19
	});

	$('.heureFin1').timepicker({
		showSecond: true,
		timeFormat: 'hh:mm:ss',
		stepHour: 2,
		stepMinute: 10,
		stepSecond: 10,
		timeOnlyTitle: "Choisir l'heure de fin",
		timeText: 'Heure',
		hourText: 'Heure',
		minuteText: 'Minute',
		secondText: 'Second',
		currentText: 'Maintenant',
		closeText: 'Choisir',
		hourMin: 7,
		hourMax: 19
 	});

	$('.heureDebut2').timepicker({
		showSecond: true,
		timeFormat: 'hh:mm:ss',
		stepHour: 2,
		stepMinute: 10,
		stepSecond: 10,
		timeOnlyTitle: "Choisir l'heure de début",
		timeText: 'Heure',
		hourText: 'Heure',
		minuteText: 'Minute',
		secondText: 'Second',
		currentText: 'Maintenant',
		closeText: 'Choisir',
		hourMin: 7,
		hourMax: 19
	});

	$('.heureFin2').timepicker({
		showSecond: true,
		timeFormat: 'hh:mm:ss',
		stepHour: 2,
		stepMinute: 10,
		stepSecond: 10,
		timeOnlyTitle: "Choisir l'heure de fin",
		timeText: 'Heure',
		hourText: 'Heure',
		minuteText: 'Minute',
		secondText: 'Second',
		currentText: 'Maintenant',
		closeText: 'Choisir',
		hourMin: 7,
		hourMax: 19
 	});

	$('.heureDebut3').timepicker({
		showSecond: true,
		timeFormat: 'hh:mm:ss',
		stepHour: 2,
		stepMinute: 10,
		stepSecond: 10,
		timeOnlyTitle: "Choisir l'heure de début",
		timeText: 'Heure',
		hourText: 'Heure',
		minuteText: 'Minute',
		secondText: 'Second',
		currentText: 'Maintenant',
		closeText: 'Choisir',
		hourMin: 7,
		hourMax: 19
	});

	$('.heureFin3').timepicker({
		showSecond: true,
		timeFormat: 'hh:mm:ss',
		stepHour: 2,
		stepMinute: 10,
		stepSecond: 10,
		timeOnlyTitle: "Choisir l'heure de fin",
		timeText: 'Heure',
		hourText: 'Heure',
		minuteText: 'Minute',
		secondText: 'Second',
		currentText: 'Maintenant',
		closeText: 'Choisir',
		hourMin: 7,
		hourMax: 19
 	});

	$("#sendMailProposition").click(
		function (){
			var date1 = $('.date1').val();
			var heureDebut1 = $('.heureDebut1').val();
			var heureFin1 = $('.heureFin1').val();
			
			var date2 = $('.date2').val();
			var heureDebut2 = $('.heureDebut2').val();
			var heureFin2 = $('.heureFin2').val();
 
			var date3 = $('.date3').val();
			var heureDebut3 = $('.heureDebut3').val();
			var heureFin3 = $('.heureFin3').val();

			var commentChoix = $('#commentChoix').val();

			var raisonChoix = $("input[@name='raisonChoix']:checked").val();

			if (date1 == "" || heureDebut1 == "" || heureFin1 == ""){
				alert("Veuillez renseigner au moin le choix 1 ainsi que les plages horaires\nPlease enter a choice and the time slots")
			}else{
				$.ajax({
					 type: "POST",
					 url: $("#urlTraitementChoix").val(),
					 data: {
						 "date1": date1,
						 "heureDebut1": heureDebut1,
						 "heureFin1": heureFin1,
						 "date2": date2,
						 "heureDebut2": heureDebut2,
						 "heureFin2": heureFin2,
						 "date3": date3,
						 "heureDebut3": heureDebut3,
						 "heureFin3": heureFin3,
						 "commentChoix": commentChoix,
						 "raisonChoix": raisonChoix	
					 },
					 dataType: "json",
					 async: false,
					 success: function(resultat){
						alert("Merci d'avoir choisi Forma2+. Votre collaborateur va vous appeler dans les meilleurs délais pour fixer le rendez-vous.\nThank you for choosing Forma2 +. Your teacher will call you as soon as possible to secure the appointment.");
					 }
				});
			}
			return false;
		}
	);

});

{/literal}
</script>
    <h2 class="demoHeaders">Liste des plages de test disponibles / <i>List of available time slots for tests</i></h2>
    <p style="font-size:12px; font-family:Arial,sans-serif;">
      Pour réserver votre test oral par téléphone. Veuillez choisir la date et la plage horaire qui vous convient et cliquer sur le bouton "<strong style="color:#1D5987;">Réserver cette plage</strong>".<br />
	  <i>To book your telephone oral test, please choose the date and time that suits you best and click the button "<strong style="color:#1D5987;">book this time slot</strong>".</i> 
    </p>
    <div id="accordion" style="font-family:Arial,sans-serif;">
        {foreach $toEvents as $iKey => $oEvent}
			<div>
				<h3>
					<a href="#" style="color:#E17009">
						{$oEvent->zDateString} à {$oEvent->zHeureString} / <i>{$oEvent->zDateStringEn} at {$oEvent->zHeureStringEn}</i>
					</a>
				</h3>
				<div>
					<p>Des questions ? N'hésitez pas à <a style="color:#1D5987; text-decoration;none;" title="Nous contacter" href="http://www.forma2plus.com/index.php?nv=content&id=29" target="_blank">nous  contacter !<a/> / <i>Questions ? Don’t hesitate to <a style="color:#1D5987; text-decoration;none;" title="Contact us !" href="http://www.forma2plus.com/index.php?nv=content&id=29" target="_blank">contact us !</a></i></p>
					<p>
					  Pour réserver votre test oral par téléphone, cliquer sur le bouton "<strong style="color:#1D5987;">Réserver cette plage</strong>". / <i>To book your oral telephone test, click the button "<strong style="color:#1D5987;">Book this time slot</strong>".</i>
						<br />
						<br />
						<b>Description</b><br />
						{$oEvent->evenement_zDescription}
					</p>
					{if !$isDisponibility}<button class="reserver" p={$p} m="{$m}" eventId="{$oEvent->evenement_id}" dateEvent="{$oEvent->zDateString}" heureEvent="{$oEvent->zHeureString}" dateEventEn="{$oEvent->zDateStringEn}" heureEventEn="{$oEvent->zHeureStringEn}">Réserver cette plage / <i>Book this time slot</i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="choiseOther" eventId="{$oEvent->evenement_id}">Choisir une autre plage / <i>Choose another time slot</i></button> {/if}
				</div>
			</div>
        {/foreach}
        {if !$iNbEvent}
    	<div>
    		<h3><a href="#">Aucune enregistrement</a></h3>
    	</div>
		{else}
	    	<div style="text-align:center;padding-top:15px;">
			</div>
			<div style="margin-top:15px;color:#1D5987;font-size:11px;text-align:center;padding-top:15px;border:1px solid #2E6E9E;background:url('images/ui-bg_inset-hard_100_fcfdfd_1x100.png') repeat-x scroll 50% bottom #FCFDFD">
				<br />Vous ne trouvez pas de plage horaire correspondante à vos disponibilités ? / <i>You can’t find a time slot that fits your schedule?</i> <br /><br />
				Est ce parce que : / <i>Is it because :</i>
				<li>
					<ul>
						<span style="width:50px;">
							<input type="radio" name="raisonChoix" id="raisonChoix" class="radioChoix" value="1" checked="checked">
						</span style="width:50px;">
						Vous êtes absent sur la période proposée ? / <i>You are absent during this period ?</i>
					</ul>
					<ul>
						<span>
							<input type="radio" name="raisonChoix" id="raisonChoix" class="radioChoix" value="2">
						</span>
						Les créneaux proposés ne vous conviennent pas ? / <i>The time slots aren’t convenient for you ?</i>
					</ul>
				<li>
				<br />Pouvez vous, dans ce cas, nous proposer 3 créneaux en journée entre 7h et 19h : / <i>In this case, could you give 3 possible time slots between 7am and 7pm :</i>
				<p class="rdv clear">
					<label>Choix / <i>Choice 1 </i><span style="color:red;">*</span> </label>
					<input type="text" class="date1 text" id="date1" readonly="readonly" name="date1" value="" />
					de / <i>from</i> <input type="text" class="heureDebut1 text" id="heureDebut1" readonly="readonly" name="heureDebut1" value="" />
					à / <i>to</i> <input type="text" class="heureFin1 text" id="heureFin1" readonly="readonly" name="heureFin1" value="" />
					<input type="hidden" class="urlTraitementChoix" id="urlTraitementChoix" name="urlTraitementChoix" value="{jurl 'auto~default:traitementChoix'}" />
				</p> 
				<p class="rdv clear">
					<label>Choix / <i>Choice 2</i>&nbsp;&nbsp;&nbsp;</label>
					<input type="text" class="date2 text" id="date2" readonly="readonly" name="date2" value="" />
					de / <i>from</i> <input type="text" class="heureDebut2 text" id="heureDebut2" readonly="readonly" name="heureDebut2" value="" />
					à / <i>to</i> <input type="text" class="heureFin2 text" id="heureFin2" readonly="readonly" name="heureFin2" value="" />
				</p> 
				<p class="rdv clear">
					<label>Choix / <i>Choice 3</i>&nbsp;&nbsp;&nbsp;</label>
					<input type="text" class="date3 text" id="date3" readonly="readonly" name="date3" value="" />
					de / <i>from</i> <input type="text" class="heureDebut3 text" id="heureDebut3" readonly="readonly" name="heureDebut3" value="" />
					à / <i>to</i> <input type="text" class="heureFin3 text" id="heureFin3" readonly="readonly" name="heureFin3" value="" />
				</p> 
				<p class="rdv clear">
					<label>Vos commentaires : / <i>Your comments :</i></label>
				</p> 
				<p class="rdv clear">
					<textarea rows="10" cols="100" name="commentChoix" id="commentChoix"></textarea>
				</p> 
				<br />
			</div>
	    	<div style="margin-top:15px;text-align:center;padding-top:15px;">
				<a href="#" id="sendMailProposition"><button>Envoyer / <i>Send</i></button></a>
			</div>
        {/if}
    </div>
    <div id="loader"></div>
<!-- <div id="loader"><img src="{$j_basepath}design/commun/images/ajax-loader.gif"/></div> -->
<div style="visibility:hidden;font-family:Arial,sans-serif;">
	<div id="ModalResa">
	  <p class="validateTips" id="validateTips" style="text-align:center;"></p>
	  <form>
			<fieldset>
				<label for="name">Votre numéro de téléphone pour le test est le :</label>
				<input type="text" name="telTest" id="telTest" class="text ui-widget-content ui-corner-all champsTelTest" value="{$oCurrentUser->client_zTel}" />, sinon, veuillez le modifier
				<br /><label for="name"><i>Your telephone number for the test is :</i></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>, if not, please correct it</i>
			</fieldset>
	  </form>
	</div>
</div>