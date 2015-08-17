{literal}
<script type="text/javascript">
$(function(){ 
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
function addEventEventlisting (){
	document.location.href = $('#urlAddEvent').val() + "&prec=2&debut="+$('#dtcm_event_rdv').val()+"&fin="+$('#dtcm_event_rdv1').val();
}
</script>
{/literal}
<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear">
				<form id="edit_form" action="#" method="POST" enctype="multipart/form-data" tmt:validate="true">
					<input type="hidden" name="action2" id="action2" value="{jurl 'evenement~FoEvenement:getEventListingDispo', array(), false}"/>
					<input type="hidden" name="approcheParListeGetEvent" id="approcheParListeGetEvent" value="{jurl 'evenement~FoEvenement:approcheParListeGetEvent'}" />
					<input type="hidden" name="urlAddEvent" id="urlAddEvent" value="{jurl 'evenement~FoEvenement:add', array(), false}"/>
					<input type="hidden" name="evenement_id" id="evenement_id" value=""/>
					<input type="hidden" name="urlCalculDateDiff" id="urlCalculDateDiff" value="{jurl 'evenement~FoEvenement:calculDateDiff'}"/>

					<h2>Recherche d'évènement</h2>
					<p class="civil clear">
						<label style="width:200px;">Date du</label>
						<input type="text" class="date text" id="dtcm_event_rdv" name="dtcm_event_rdv" style="width:300px;" value="{if isset ($toParams[0]->zDateDebut)}{$toParams[0]->zDateDebut}{/if}" readonly="readonly"/>
					</p>
					<p class="civil clear">
						<label style="width:200px;">Jusqu'au</label>
						<input type="text" class="date text" id="dtcm_event_rdv1" name="dtcm_event_rdv1" style="width:300px;" value="{if isset ($toParams[0]->zDateFin)}{$toParams[0]->zDateFin}{/if}" readonly="readonly"/>


						{*<!--<input type="text" class="date1 text" id="dtcm_event_rdv1" name="dtcm_event_rdv1" value="" style="width:300px;" value="{if isset ($toParams[0]->zDateFin)}{$toParams[0]->zDateFin}{/if}" readonly="readonly"/>-->*}
					</p>
					<div class="input" style="margin-right:133px;width:295px">
						<input type="button" value="Ajouter un évènement" class="boutonform" onclick="addEventEventlisting();" />
						<input type="button" value="Rechercher" class="boutonform" onclick="submitFormRechercheApprocheListeDispo();" />
					</div>
					<div class="input" style="width:480px;">
						<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
					</div>
				</form>
			</div>
		</div>
		<div class="content">
			<div class="formevent listeclients clear" style="width:943px;">
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
			<h3 class="last"><span class="title">Nombre d'événement trouvés :</span> <span>{$iResTotal}</span></h3>
			<div id="accordion" style="font-family:Arial,sans-serif;">
			{foreach $toEvenement as $oEvent}
				<div>
					<h3 style="font-size:1em;">
						<a href="#" style="color:#E17009{*$oEvent->typeevenements_zCouleur*}">
							{$oEvent->evenement_zDateJoursDeLaSemaine}&nbsp;{$oEvent->evenement_zDateHeureDebut|date_format:'%d/%m/%Y'} à {$oEvent->evenement_zDateHeureDebut|date_format:'%H:%M'}
						</a>
					</h3>
					<div>
						<p>
						  Pour affecter une plage horaire à un projet, cliquez sur le bouton "<strong style="color:#1D5987;">Affecter à un projet</strong>".
							<br />
							<br />
							{if isset($oEvent->evenement_zDescription) && $oEvent->evenement_zDescription != ""}
							<b>Description</b><br />
							{$oEvent->evenement_zDescription}
							{/if}
						</p>
						<button class="showpopupAffestation" eventId="{$oEvent->evenement_id}" dateEvent="{$oEvent->evenement_zDateHeureDebut|date_format:'%d/%m/%Y'}" heureEvent="{$oEvent->evenement_zDateHeureDebut|date_format:'%H:%M'}" href="#">Affecter à un projet</button>
					</div>
				</div>
			{/foreach}
			</div>
	</div>
</div>
{$footer}


<div class="pop-up formevent clear"  id="popupAffestation" style="background-color:#E9E9E9; border:1px solid #E1E1E1; width:600px">
	<form id="edit_form" name="edit_form" action="{jurl 'evenement~FoEvenement:saveAffectation', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true">
	<input type="hidden" name="evenement_id" id="evenement_id" value="" />

	<input type="hidden" name="criteria_datedebut" id="criteria_datedebut" value="" />
	<input type="hidden" name="criteria_datefin" id="criteria_datefin" value="" />
	
	<input type="hidden" name="evenement_origine" id="evenement_origine" value="" />
	<input type="hidden" name="sendMail" id="sendMail" value="0" />
	<input type="hidden" name="evenement_iPriorite" id="evenement_iPriorite" value="1" />
	<input type="hidden" name="iAffichage" id="iAffichage" value="" />
	<input type="hidden" name="zDate" id="zDate" value="" />
	<input type="hidden" class="text" name="evenement_zLibelle" id="evenement_zLibelle" value=""> 
	<input type="hidden" name="evenement_iContactTel" id="evenement_iContactTel" value="0" />
	<input type="hidden" name="finPeriodiciteOccurence" id="finPeriodiciteOccurence" value="0" />
	<input type="hidden" name="periodiciteMensuel1" id="periodiciteMensuel1" value="0" />
	<input type="hidden" name="evenement_zDateHeureSaisie" id="evenement_zDateHeureSaisie" value="" />
	<input type="hidden" name="evenement_iTypeEvenementId" id="evenement_iTypeEvenementId" class="evenement_iTypeEvenementId" value="" />
	<input type="hidden" name="x" id="x" value="0" />

	<h2>Afféctation d'un évènement à un projet</h2>
	<a href="#" title="Fermer" class="fermer"><img src="{$j_basepath}design/front/images/design/close.png" alt="fermer"></a>
	<p class="clear">
	</p>
	<p class="clear">
		<label>Types d’évènement *</label>
		{foreach $toTypeEvenement as $oTypeEvenement}
			<input type="hidden" id="typeevenements_iStagiaireActif_{$oTypeEvenement->typeevenements_id}" name="typeevenements_iStagiaireActif_{$oTypeEvenement->typeevenements_id}" value="{$oTypeEvenement->typeevenements_iStagiaireActif}" />
		{/foreach}
		<select name="evenement_iTypeEvenementId" class="text" id="evenement_iTypeEvenementId" tmt:invalidindex="0" tmt:required="true" >
			<option value="0">----------------------Séléctionner----------------------</option>
			{foreach $toTypeEvenement as $oTypeEvenement}
				<option value="{$oTypeEvenement->typeevenements_id}">{$oTypeEvenement->typeevenements_zLibelle}</option>
			{/foreach}
		</select>
	</p>
	<p class="clear">
		<label>Description</label>
		<textarea style="height:auto" name="evenement_zDescription" id="evenement_zDescription"></textarea>
	</p> 
	<p class="clear">
		<label>Projet</label>
		<input type="hidden" name="evenement_iStagiaire" id="evenement_iStagiaire" value="" />
		<input style="width:296px;" type="text" class="text" name="evenement_zStagiaire" id="evenement_zStagiaire" value="" />
		&nbsp;<a href="#" title="Rechercher" id="rechercherStagiaire">
			<img src="{$j_basepath}design/front/images/design/rechercher.png" alt="Ajouter un projet" />
		</a>
	</p>
	<p class="clear" id="div-stagiaire-liste">
		<label for="dtcm_event_project">&nbsp;</label>
		<select style="width:400px;" name="stagiaire-liste" id="stagiaire-liste" size="10" url="">
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
		<label>Rendez vous *</label>
		<input type="text" class="daterdv text" id="dtcm_event_rdv_affectation" name="dtcm_event_rdv" value="" tmt:required="true"/>
	</p> 
	<p class="rdv clear">
		<label>Tel. pour ce jour</label>
		<input type="text" name="evenement_zContactTel" id="evenement_zContactTel" class="text" value=""/>
		<input type="button" value="C'est le stagiaire qui appelle" id="appelStagiaire" class="boutonforms" />
	</p>
	<p class="duree clear">
		<label>Durée</label>
		<select style="width:120px;"name="evenement_iDuree" class="text" id="evenement_iDuree">
		</select>
	</p>
	<p class="rappel clear">
		<label>Rappel</label>
		<input type="radio" class="radio" name="evenement_iRappel" id="evenement_iRappel" value="1"/>
		<span>Oui</span>
		<input type="radio" class="radio" name="evenement_iRappel" id="evenement_iRappel" value="0" checked="checked"/>
		<span>Non</span>
		<input type="text" class="text" name="evenement_iRappelJour" id="evenement_iRappelJour"/>
		<span class="text">jours</span>
		<input type="text" class="text" name="evenement_iRappelHeure" id="evenement_iRappelHeure"/>
		<span class="text">heures</span>
		<input type="text" class="text" name="evenement_iRappelMinute" id="evenement_iRappelMinute"/>
		<span class="text">minutes avant</span>
	</p>
	<p class="statut clear">
		<label>Statut *</label>
		<input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="1" tmt:required="true" checked="checked"/><span>Afficher</span><input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="2"/><span>Ne pas afficher</span><input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="0" /><span>Annuler</span>
	</p>
	<div class="input">
		<a href="#" class="close"><input type="button" value="Annuler" class="boutonform" /></a>
		<input type="button" value="Affecter" class="boutonform submitFormulaire" />
	</div>
	<div class="input" style="width:480px;">
		<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
	</div>
</form>
	</div>
</div>
<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>