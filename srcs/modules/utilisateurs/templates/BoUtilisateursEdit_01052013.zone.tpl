{literal}
<script type="text/javascript">
$(function() {
	if($("#bEdit").val() == 0){
		$("#utilisateur_bSendExcel").val(0);
		$("#utilisateur_frequenceSendExcel").val(0);
		$("#utilisateur_frequenceSendExcel").attr({'disabled':'disabled'});
	}else{
		$("#utilisateur_bSendExcel").val($("#hiddenSendExcel").val());
		if ($("#hiddenSendExcel").val() == 0){
			$("#utilisateur_frequenceSendExcel").val(0);
			$("#utilisateur_frequenceSendExcel").attr({'disabled':'disabled'});
		}else{
			$("#utilisateur_frequenceSendExcel").val($("#hiddenFrequenceSendExcel").val());
			$("#utilisateur_frequenceSendExcel").attr({'disabled':''});
		}
	}
	$("#typeEvenement_dispo").multiSelect("#typeEvenement_associes", {trigger: "#typeEvenement_right"});
	$("#typeEvenement_associes").multiSelect("#typeEvenement_dispo", {trigger: "#typeEvenement_left"});
	$("#utilisateur_bSendExcel").change(
		function (){
			if($("#utilisateur_bSendExcel").val() == 1){
				$("#utilisateur_frequenceSendExcel").attr({'disabled':''});
				$("#utilisateur_frequenceSendExcel").val(1);
			}else{
				$("#utilisateur_frequenceSendExcel").val(0);
				$("#utilisateur_frequenceSendExcel").attr({'disabled':'disabled'});
			}		
		}
	);
/***********************************************************	
	$('#lundi_debut_matin').ptTimeSelect({
		containerClass: undefined,
		containerWidth: undefined,
		hoursLabel:     'Heure',
		minutesLabel:   'Minutes',
		setButtonLabel: 'OK',
		popupImage:     undefined,
		onFocusDisplay: true,
		zIndex:         10,
		onBeforeShow:   undefined,
		onClose:        undefined								
	});
$('#lundi_fin_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#lundi_debut_apres_midi').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#lundi_fin_soir').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#mardi_debut_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#mardi_fin_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#mardi_debut_apres_midi').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#mardi_fin_soir').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#mercredi_debut_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#mercredi_fin_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#mercredi_debut_apres_midi').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#mercredi_fin_soir').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#jeudi_debut_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#jeudi_fin_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#jeudi_debut_apres_midi').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#jeudi_fin_soir').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#vendredi_debut_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#vendredi_fin_matin').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#vendredi_debut_apres_midi').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
$('#vendredi_fin_soir').ptTimeSelect({containerClass: undefined,containerWidth: undefined,hoursLabel:'Heure',minutesLabel:   'Minutes',setButtonLabel: 'OK',popupImage:undefined,onFocusDisplay:true,zIndex:10,onBeforeShow:undefined,onClose:undefined});
/***********************************************************/
		/* initialize the external events
		-----------------------------------------------------------------*/
	
		$('#external-events div.external-event').each(function() {
		
			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()) // use the element's text as the event title
			};
			
			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);
			
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});
			
		});
	
	
		/* initialize the calendar
		-----------------------------------------------------------------*/
		var plagehoraireId = $('#plagehoraireId').val();
		var slotMinute = 20 ;
		if (plagehoraireId == 1){
			slotMinute = 60;
		}else if (plagehoraireId == 2){
			slotMinute = 30;
		}	
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			selectable: true,
			selectHelper: true,
			weekends:false,
			defaultView:'agendaWeek',
			showWeekNumbers:false,
			allDaySlot: false,
			axisFormat: 'H(:mm)',
			slotMinutes: slotMinute,
			firstHour:7,
			minTime:7,
			maxTime:23,
			editable: true,
			droppable: true, // this allows things to be dropped onto the calendar !!!
			drop: function(date, allDay) { // this function is called when something is dropped
			
				// retrieve the dropped element's stored Event Object
				var originalEventObject = $(this).data('eventObject');
				
				// we need to copy it, so that multiple events don't have a reference to the same object
				var copiedEventObject = $.extend({}, originalEventObject);
				
				// assign it the date that was reported
				copiedEventObject.start = date;
				copiedEventObject.allDay = allDay;
				
				// render the event on the calendar
				// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
				$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
				
				// is the "remove after drop" checkbox checked?
				if ($('#drop-remove').is(':checked')) {
					// if so, remove the element from the "Draggable Events" list
					$(this).remove();
				}
				
			}
		});

});

function submitFormulaire (form){
	var o = $("#typeEvenement_associes")[0].options;
	g_supprvisuel = 0;
	$("#listeTypeEvenement").val("");
	oL = o.length;
		
	for (var i = 0; i < oL; i++){
		if (i != oL - 1){
			$("#listeTypeEvenement").val($("#listeTypeEvenement").val() + o[i].value + ",");

		} else {
			$("#listeTypeEvenement").val($("#listeTypeEvenement").val() + o[i].value);
		}
	}
	var isRequired = $("#utilisateur_bSendExcel").val() ;
	if($("#utilisateur_bSendExcel").val() == 1 && $("#utilisateur_frequenceSendExcel").val() == 0){
		alert("Veuillez séléctionner la fréquence") ;
	}else{
		if(tmt_validateForm(form)){
			document.forms["edit_form"].submit();
		}
	}
}
</script>
{/literal}
<h1 class="noBg">{if $bEdit}Edition {else}Nouveau{/if}</h1>
<h2>{if $bEdit}Edition  : {else}Nouveau {/if} {if $bEdit}{$oUtilisateurs->utilisateur_zNom} {$oUtilisateurs->utilisateur_zPrenom}{/if}</h2>
<form id="edit_form" onsubmit="submitFormulaire(this);" action="{jurl 'admin~utilisateurs:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
    <input type="hidden" name="utilisateur_id" value="{if $bEdit}{$oUtilisateurs->utilisateur_id}{else}0{/if}" />
    <input type="hidden" name="plagehoraireId" id="plagehoraireId" value="{if $bEdit}{$oUtilisateurs->utilisateur_plageHoraireId}{else}2{/if}" />
    <input type="hidden" name="bEdit" id="bEdit" value="{if $bEdit}1{else}0{/if}" />
    <input type="hidden" name="hiddenSendExcel" id="hiddenSendExcel" value="{if $bEdit}{$oUtilisateurs->utilisateur_bSendExcel}{else}0{/if}" />
    <input type="hidden" name="hiddenFrequenceSendExcel" id="hiddenFrequenceSendExcel" value="{if $bEdit}{$oUtilisateurs->utilisateur_frequenceSendExcel}{else}0{/if}" />
    <input type="hidden" name="id" id="id" value="{if $bEdit}{if $iResTotal > 0 && isset ($oUtilisateursIndisponibilite->id) && $oUtilisateursIndisponibilite->id > 0}{$oUtilisateursIndisponibilite->id}{else}0{/if}{else}0{/if}" />
	<p class="clearfix">
        <label>Type *</label>
        <span class="champ">
            <select name="utilisateur_iTypeId" id="utilisateur_iTypeId" style="width:140px" tmt:invalidvalue="0" tmt:message="Veuillez selectionner un type<br />" tmt:required="true">
                <option value="0">Choisir le type</option>
                {foreach $toTypes as $oType}
                    <option value="{$oType->type_id}" {if($oType->type_id == $oUtilisateurs->utilisateur_iTypeId)} selected=selected {/if}>{$oType->type_zLibelle}</option>
                {/foreach}
            </select>
        </span>
    </p>
	<p class="clearfix">
        <label>Identifiant *:</label>
        <span class="champ"><input type="text" name="utilisateur_zLogin" id="utilisateur_zLogin" value="{$oUtilisateurs->utilisateur_zLogin}" tmt:message="Veuillez remplir le champ Mot de passe<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Mot de passe *:</label>
        <span class="champ"><input type="password" name="utilisateur_zPass" id="utilisateur_zPass" value="{$oUtilisateurs->utilisateur_zPass}" tmt:message="Veuillez remplir le champ mot de passe<br />" tmt:required="true"/></span>
    </p>
    <p class="clearfix">
        <label>Confirmation *:</label>
        <span class="champ"><input type="password" name="password1" id="password1" value="{if $bEdit}{$oUtilisateurs->utilisateur_zPass}{/if}" tmt:equalto="utilisateur_zPass" tmt:message="Veuillez verifier la confirmation de votre mot de passe<br />" tmt:required="true" /></span>
    </p>
    <p class="clearfix">
        <label>Nom *:</label>
        <span class="champ"><input type="text" name="utilisateur_zNom" id="utilisateur_zNom" value="{$oUtilisateurs->utilisateur_zNom}" tmt:message="Veuillez remplir le champ Nom<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Prénom(s) :</label>
        <span class="champ"><input type="text" name="utilisateur_zPrenom" id="utilisateur_zPrenom" value="{$oUtilisateurs->utilisateur_zPrenom}" /></span>
    </p>
	<p class="clearfix">
        <label>Civilité *:</label>
		<span class="champ">
			<select name="utilisateur_iCivilite" id="utilisateur_iCivilite">
				<option value="0" {if $bEdit}{if $oUtilisateurs->utilisateur_iCivilite == CIVILITE_FEMME} selected=selected {/if}{/if}>Femme</option>
				<option value="1" {if $bEdit}{if $oUtilisateurs->utilisateur_iCivilite == CIVILITE_HOMME} selected=selected {/if}{/if}>Homme</option>
				<option value="2" {if $bEdit}{if $oUtilisateurs->utilisateur_iCivilite == CIVILITE_MADEMOISELLE} selected=selected {/if}{/if}>Mademoiselle</option>
			</select>
        </span>		
    </p>
	<p class="clearfix">
        <label>Mail *:</label>
        <span class="champ"><input type="text" name="utilisateur_zMail" id="utilisateur_zMail" value="{$oUtilisateurs->utilisateur_zMail}" tmt:pattern="email" tmt:message="Veuillez entrer un adresse mail valide<br />" tmt:required="true" tmt:pattern="email"/></span>
    </p>
	<p class="clearfix">
        <label>Télèphone *:</label>
        <span class="champ"><input type="text" name="utilisateur_zTel" id="utilisateur_zTel" value="{$oUtilisateurs->utilisateur_zTel}" tmt:message="Veuillez remplir le champ Télèphone<br />" tmt:required="true" tmt:filter="phonenumber"/></span>
    </p>
	<p class="clearfix">
        <label>Pays *:</label>
        <span class="champ">
			<select name="utilisateur_iPays" id="utilisateur_iPays"  tmt:message="Veuillez séléctionner la société<br />" tmt:required="true" tmt:invalidIndex="0">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toPays as $oPays}
					<option value="{$oPays->pays_id}" {if $bEdit}{if $oUtilisateurs->utilisateur_iPays==$oPays->pays_id} selected=selected {/if}{else}{if $oPays->pays_id==64}selected=selected{/if}{/if}>{$oPays->pays_zNom}</option>
				{/foreach}
			</select>
		</span>
    </p>
	<p class="clearfix">
        <label>Decalage horaire :</label>
        <span class="champ">
			<select name="utilisateur_decalageHoraire" id="utilisateur_decalageHoraire">
				{for $i=-12; $i<=12; $i++}
					<option value="{$i}" {if $bEdit}{if $oUtilisateurs->utilisateur_decalageHoraire==$i} selected=selected {/if}{else}{if $i == 0}selected="selected"{/if}{/if}>{$i} heures</option>
				{/for}
			</select>&nbsp;&nbsp;&nbsp;&nbsp;<b>(par rapport à l'heure française)</b>
		</span>
    </p>
	<p class="clearfix">
        <label>Plage horaire *:</label>
        <span class="champ">
			<select name="utilisateur_plageHoraireId" id="utilisateur_plageHoraireId"  tmt:message="Veuillez séléctionner la plage horaire par defaut<br />" tmt:required="true" tmt:invalidIndex="0">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toPlageHoraire as $oPlageHoraire}
					<option value="{$oPlageHoraire->plagehoraire_id}" 
						{if $bEdit}
							{if $oUtilisateurs->utilisateur_plageHoraireId==$oPlageHoraire->plagehoraire_id} selected=selected {/if}
						{else}
							{if $oPlageHoraire->plagehoraire_id == 1}selected=selected{/if}
						{/if}	
						>{$oPlageHoraire->plagehoraire_libelle}</option>
				{/foreach}
			</select>
		</span>
    </p>
	<p class="clearfix">
        <label>Superviseur :</label>
        <span class="champ">
       	<input type="radio" name="utilisateur_bSuperviseur" id="utilisateur_bSuperviseur" class="radio" value="1" {if $oUtilisateurs->utilisateur_bSuperviseur == UTILISATEUR_SUPERVISEUR}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  statut"/>&nbsp;OUI&nbsp;<input type="radio" name="utilisateur_bSuperviseur" id="utilisateur_bSuperviseur" class="radio" value="0" {if $oUtilisateurs->utilisateur_bSuperviseur == UTILISATEUR_NON_SUPERVISEUR}checked="checked"{/if} />&nbsp;NON
        </span>
    </p>
	{*<!--<p class="clearfix">
        <label>Professeur superviseur *:</label>
        <span class="champ">
			<select name="utilisateur_iSuperviseurId" id="utilisateur_iSuperviseurId">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toUtilisateur as $oTmpUtilisateur}
					<option value="{$oTmpUtilisateur->utilisateur_id}" 
						{if $bEdit}
							{if $oTmpUtilisateur->utilisateur_id==$oUtilisateurs->utilisateur_iSuperviseurId} selected=selected {/if}
						{/if}
					>{$oTmpUtilisateur->utilisateur_zNom}&nbsp;{$oTmpUtilisateur->utilisateur_zPrenom}</option>
				{/foreach}
			</select>
		</span>
    </p>-->*}
<!-- -->
	<p>
		<table cellspacing="0" cellpadding="0" style="margin-left:5px; width:717px;" align="center">
			<tr class="row1">
					<td class="color1">
						<p class="clearfix"> <label>Les type d'événement disponibles : </label></p>
					</td>
					<td class="color2">&nbsp;</td>
					<td class="color1"> 
						<p class="clearfix">
							<label>Les type d'événement associé(s) à l'utilisateur :  </label>
						</p>
					</td>
			</tr>
			<tr class="row2">
				<td class="color1">
					<select name="typeEvenement_dispo" id="typeEvenement_dispo" multiple="multiple" size="6" style="width:295px">
						{foreach $toTypeEvenements as $oTypeEvenements}
							<option value="{$oTypeEvenements->typeevenements_id}">{$oTypeEvenements->typeevenements_zLibelle}</option>
						{/foreach}
					</select>
				</td>
				<td class="color2">
					<p style="background:none;">
						<a id="typeEvenement_right" style="cursor:hand; cursor:pointer;" ><img src="{$j_basepath}design/back/images/arrow_right.gif" alt="&gt;" /></a>
					</p>
					<p style="background:none;">
						<a id="typeEvenement_left" style="cursor:hand; cursor:pointer;" ><img src="{$j_basepath}design/back/images/arrow_left.gif" alt="&lt;" /></a>
					</p>
				</td>
				<td class="color1">
					<select name="typeEvenement_associes[]" id="typeEvenement_associes" multiple="multiple" size="6" style="width:295px">
						{foreach $toTypeEvenementsUtilisateur as $oTypeEvenementsUtilisateur}
							<option value="{$oTypeEvenementsUtilisateur->typeevenements_id}">{$oTypeEvenementsUtilisateur->typeevenements_zLibelle}</option>
						{/foreach}
					</select>
					<input type="hidden" id="listeTypeEvenement" name="listeTypeEvenement" value="{if $bEdit}{$zlisteTypeEvenement}{/if}" />
				</td>
			</tr>
		</table>
	</p>  
<!-- -->
    <p class="clearfix">
        <label>Statut :</label>
        <span class="champ">
       	<input type="radio" name="utilisateur_statut" id="utilisateur_statut" class="radio" value="1" {if $oUtilisateurs->utilisateur_statut == STATUT_PUBLIE}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  statut"/>&nbsp;PUBLIER&nbsp;<input type="radio" name="utilisateur_statut" id="utilisateur_statut" class="radio" value="2" {if $oUtilisateurs->utilisateur_statut == STATUT_NON_PUBLIE}checked="checked"{/if} />&nbsp;NON PUBLIER&nbsp;<input type="radio" name="utilisateur_statut" id="utilisateur_statut" class="radio" value="0" {if $oUtilisateurs->utilisateur_statut == STATUT_DESACTIVE}checked="checked"{/if} />&nbsp;DESACTIVER
        </span>
    </p>
	<p class="clearfix">
        <label>Recevoir une copie du planning au format Excel par email :</label>
        <span class="champ" style="margin-left: 350px;">
			<select name="utilisateur_bSendExcel" id="utilisateur_bSendExcel">
					<option value=" ">----</option>
					<option value="1" {if $oUtilisateurs->utilisateur_bSendExcel == 1}selected="selected"{/if}>Oui</option>
					<option value="0" {if $oUtilisateurs->utilisateur_bSendExcel == 0}selected="selected"{/if}>Non</option>
			</select>&nbsp;Fréquence&nbsp;<select name="utilisateur_frequenceSendExcel" id="utilisateur_frequenceSendExcel">
					<option value="0">-----------Fréquence-----------</option>
					<option value="1">Quotidien</option>
					<option value="2">Hebdomadaire</option>
					<option value="3">Tous les 2 semaine</option>
					<option value="4">Tous les mois</option>
			</select>
		</span>
    </p>
<!--INDISPONIBILITE-->
{*<!--
	<p class="line_bottom">&nbsp;</p>
	<h2>Indisponibilité du professeur {if $bEdit}{$oUtilisateurs->utilisateur_zNom} {$oUtilisateurs->utilisateur_zPrenom}{/if} (Heure française)</h2>
    <table cellspacing="0" class="expanded" id="tableAnnoncesList"> 
        <thead>
            <th class="color1" style="width:20%;">Jour</th>
            <th class="color2" style="width:20%;">Matin - Debut</th>
            <th class="color1" style="width:20%;">Matin - Fin</th>
            <th class="color2" style="width:20%;">Soir - Debut</th>
            <th class="color1" style="width:20%;">Soir - Fin</th>
        </thead>
        <tbody>
              <tr class="row1">
                <td class="color1" style="text-align: center;"><b>Lundi</b></td>
                <td class="color2" style="text-align: center;"><input type="text" name="lundi_debut_matin" id="lundi_debut_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->lundi_debut_matin) && $oUtilisateursIndisponibilite->lundi_debut_matin!=''}{$oUtilisateursIndisponibilite->lundi_debut_matin}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="lundi_fin_matin" id="lundi_fin_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->lundi_fin_matin) && $oUtilisateursIndisponibilite->lundi_fin_matin!=''}{$oUtilisateursIndisponibilite->lundi_fin_matin}{/if}"></td>
                <td class="color2" style="text-align: center;"><input type="text" name="lundi_debut_apres_midi" id="lundi_debut_apres_midi" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->lundi_debut_apres_midi) && $oUtilisateursIndisponibilite->lundi_debut_apres_midi!=''}{$oUtilisateursIndisponibilite->lundi_debut_apres_midi}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="lundi_fin_soir" id="lundi_fin_soir" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->lundi_fin_soir) && $oUtilisateursIndisponibilite->lundi_fin_soir!=''}{$oUtilisateursIndisponibilite->lundi_fin_soir}{/if}"></td>
            </tr>
            <tr class="row2">
                <td class="color1" style="text-align: center;"><b>Mardi</b></td>
                <td class="color2" style="text-align: center;"><input type="text" name="mardi_debut_matin" id="mardi_debut_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->mardi_debut_matin) && $oUtilisateursIndisponibilite->mardi_debut_matin!=''}{$oUtilisateursIndisponibilite->mardi_debut_matin}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="mardi_fin_matin" id="mardi_fin_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->mardi_fin_matin) && $oUtilisateursIndisponibilite->mardi_fin_matin!=''}{$oUtilisateursIndisponibilite->mardi_fin_matin}{/if}"></td>
                <td class="color2" style="text-align: center;"><input type="text" name="mardi_debut_apres_midi" id="mardi_debut_apres_midi" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->mardi_debut_apres_midi) && $oUtilisateursIndisponibilite->mardi_debut_apres_midi!=''}{$oUtilisateursIndisponibilite->mardi_debut_apres_midi}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="mardi_fin_soir" id="mardi_fin_soir" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->mardi_fin_soir) && $oUtilisateursIndisponibilite->mardi_fin_soir!=''}{$oUtilisateursIndisponibilite->mardi_fin_soir}{/if}"></td>
            </tr>
			<tr class="row1">
                <td class="color1" style="text-align: center;"><b>Mercredi</b></td>
                <td class="color2" style="text-align: center;"><input type="text" name="mercredi_debut_matin" id="mercredi_debut_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->mercredi_debut_matin) && $oUtilisateursIndisponibilite->mercredi_debut_matin!=''}{$oUtilisateursIndisponibilite->mercredi_debut_matin}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="mercredi_fin_matin" id="mercredi_fin_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->mercredi_fin_matin) && $oUtilisateursIndisponibilite->mercredi_fin_matin!=''}{$oUtilisateursIndisponibilite->mercredi_fin_matin}{/if}"></td>
                <td class="color2" style="text-align: center;"><input type="text" name="mercredi_debut_apres_midi" id="mercredi_debut_apres_midi" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->mercredi_debut_apres_midi) && $oUtilisateursIndisponibilite->mercredi_debut_apres_midi!=''}{$oUtilisateursIndisponibilite->mercredi_debut_apres_midi}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="mercredi_fin_soir" id="mercredi_fin_soir" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->mercredi_fin_soir) && $oUtilisateursIndisponibilite->mercredi_fin_soir!=''}{$oUtilisateursIndisponibilite->mercredi_fin_soir}{/if}"></td>
			</tr>
			<tr class="row2">
                <td class="color1" style="text-align: center;"><b>Jeudi</b></td>
                <td class="color2" style="text-align: center;"><input type="text" name="jeudi_debut_matin" id="jeudi_debut_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->jeudi_debut_matin) && $oUtilisateursIndisponibilite->jeudi_debut_matin!=''}{$oUtilisateursIndisponibilite->jeudi_debut_matin}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="jeudi_fin_matin" id="jeudi_fin_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->jeudi_fin_matin) && $oUtilisateursIndisponibilite->jeudi_fin_matin!=''}{$oUtilisateursIndisponibilite->jeudi_fin_matin}{/if}"></td>
                <td class="color2" style="text-align: center;"><input type="text" name="jeudi_debut_apres_midi" id="jeudi_debut_apres_midi" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->jeudi_debut_apres_midi) && $oUtilisateursIndisponibilite->jeudi_debut_apres_midi!=''}{$oUtilisateursIndisponibilite->jeudi_debut_apres_midi}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="jeudi_fin_soir" id="jeudi_fin_soir" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->jeudi_fin_soir) && $oUtilisateursIndisponibilite->jeudi_fin_soir!=''}{$oUtilisateursIndisponibilite->jeudi_fin_soir}{/if}"></td>
			</tr>
			<tr class="row1">
                <td class="color1" style="text-align: center;"><b>Vendredi</b></td>
                <td class="color2" style="text-align: center;"><input type="text" name="vendredi_debut_matin" id="vendredi_debut_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->vendredi_debut_matin) && $oUtilisateursIndisponibilite->vendredi_debut_matin!=''}{$oUtilisateursIndisponibilite->vendredi_debut_matin}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="vendredi_fin_matin" id="vendredi_fin_matin" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->vendredi_fin_matin) && $oUtilisateursIndisponibilite->vendredi_fin_matin!=''}{$oUtilisateursIndisponibilite->vendredi_fin_matin}{/if}"></td>
                <td class="color2" style="text-align: center;"><input type="text" name="vendredi_debut_apres_midi" id="vendredi_debut_apres_midi" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->vendredi_debut_apres_midi) && $oUtilisateursIndisponibilite->vendredi_debut_apres_midi!=''}{$oUtilisateursIndisponibilite->vendredi_debut_apres_midi}{/if}"></td>
                <td class="color1" style="text-align: center;"><input type="text" name="vendredi_fin_soir" id="vendredi_fin_soir" style="width:60px;" value="{if isset ($oUtilisateursIndisponibilite->vendredi_fin_soir) && $oUtilisateursIndisponibilite->vendredi_fin_soir!=''}{$oUtilisateursIndisponibilite->vendredi_fin_soir}{/if}"></td>
			</tr>
        </tbody>
    </table>    
-->*}
<!--INDISPONIBILITE-->
<p class="line_bottom">&nbsp;</p>
<h2>Disponibilité du professeur {if $bEdit}{$oUtilisateurs->utilisateur_zNom} {$oUtilisateurs->utilisateur_zPrenom}{/if} (Heure française)</h2>

<div id='wrap'>
<div id='external-events'>
<h4>Draggable Events</h4>
<div class='external-event' style="background:none repeat scroll 0 0 #33FF66;">Disponible</div>
<div class='external-event' style="background:none repeat scroll 0 0 #FF3300;">Indisponible</div>
<!--<p>
<input type='checkbox' id='drop-remove' /> <label for='drop-remove'>remove after drop</label>
</p>-->
</div>

<div id='calendar'></div>

<div style='clear:both'></div>
</div>

	<p class="line_bottom">&nbsp;</p>
    <p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Enregistrer" />&nbsp;
        <input type="button" class="bouton" name="annuler" value="Annuler" onclick="location.href='{jurl 'admin~utilisateurs:index', array(), false}'"/>
    </p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>
{literal}
<style>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
		
	#wrap {
		width: 1100px;
		margin: 10px -198px;
		}
		
	#external-events {
		float: left;
		width: 150px;
		padding: 0 10px;
		border: 1px solid #ccc;
		background: #eee;
		text-align: left;
		}
		
	#external-events h4 {
		font-size: 16px;
		margin-top: 0;
		padding-top: 1em;
		}
		
	.external-event { /* try to mimick the look of a real event */
		margin: 10px 0;
		padding: 2px 4px;
		background: #3366CC;
		color: #fff;
		font-size: .85em;
		cursor: pointer;
		}
		
	#external-events p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
		}
		
	#external-events p input {
		margin: 0;
		vertical-align: middle;
		}

	#calendar {
		float: right;
		width: 900px;
		}

</style>
{/literal}