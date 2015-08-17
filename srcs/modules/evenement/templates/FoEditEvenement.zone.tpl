{assign $idTypeEvenementCourTelephone = ID_TYPE_EVENEMENT_COUR_TELEPHONE}

{literal}
<script type="text/javascript">
	$(function(){ 
		$('.submitFormulaire').click(
			function(){
				var form = document.getElementById('edit_form');
				var isValid = tmt_validateForm(form);
				if(isValid){
					var iEvenementId = $('#evenement_id').val();
					var zDateTime = $('#dtcm_event_rdv').val();
					var iTypeEvent = $('#evenement_iTypeEvenementId').val();

					$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:testEventExistEdition", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
							if (datas == 0){
								$('#edit_form').submit();
							}else{
								if (iTypeEvent == 13 || iTypeEvent == 18){
									alert("La plage horaire est déja occupée.\nVous ne pouvez pas créer ou modifier un événement de type Disponible.") ;
								}else{
									$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:testEventExistEditionIsTypeEventDisponible", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
											if (datas == 13 || datas == 18){
												//alert("La plage horaire est déja occupée par un événement de type Disponible.") ;
												$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:desactiverEventDispo", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
													if (datas == 1){
														$('#edit_form').submit();
													}
													return false;
												});
											}else{
												if(confirm ("La plage horaire est déja occupée.\nVoulez-vous continuer ?"))
												{
													$("#x").val(1);
													$('#edit_form').submit();
												}
											}
										return false;
									});
								}
							}
						return false;
					});
				}
			}
		);

		$('.submitFormMail').click(
			function(){
				var form = document.getElementById('edit_form');
				var isValid = tmt_validateForm(form);
				if(isValid){
					var iEvenementId = $('#evenement_id').val();
					var zDateTime = $('#dtcm_event_rdv').val();
					$('#sendMail').val(1);
					$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:testEventExistEdition", zDateTime:zDateTime, iEvenementId:iEvenementId}, function(datas){
							if (datas == 0){
								//$("#x").val(0);
								$('#edit_form').submit();
							}else{
								if(confirm ("La plage horaire est déja occupée.\nVoulez-vous continuer ?"))
								{
									$("#x").val(1);
									$('#edit_form').submit();
								}
							}
						return false;
					});
				}
			}
		);

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
				if (row["post_title"] !== undefined && row["post_title"] != "")
				{
					zInfo += ' ' + row["post_title"] ;
				}
				return zInfo ;
			}
		}).result(function(event, row, formatted){	
			if (typeof(row) === undefined) {		
				$('#evenement_iStagiaire').val(0);		
				$('#evenement_zStagiaire').val("");		
			} else {
				if (row !== undefined){
					$('#evenement_iStagiaire').val(row["ID"]);
					$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:chargePosteParId", iStagiaireId:$('#evenement_iStagiaire').val()}, function(datas){
						$('#div-stagiaire-liste').hide();

						var html = datas["post_title"];
						$('#evenement_zStagiaire').val(html);
						$('#p-txtsociete').show();
						$('#txtsociete').attr('href', datas["guid"]);
						$('#txtsociete').text(datas["guid"]);
					});
				}
			}
		}).blur(function(){
			$(this).search();
		});

		$('.periodicite').hide();

		$('.date').datepicker({
			duration: '',
			showTime: true,
			showOn: 'button',
			buttonImageOnly : true,
			buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
			constrainInput: false
		});

		$('.datePeriodicite').datepicker({
			duration: '',
			showTime: false,
			showOn: 'button',
			buttonImageOnly : true,
			buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
			constrainInput: false
		});

		$('.datePeriodiciteFin').datepicker({
			duration: '',
			showTime: false,
			showOn: 'button',
			buttonImageOnly : true,
			buttonImage: j_basepath + 'design/front/images/design/picto_calendar.gif',
			constrainInput: false
		});

		$('#appelStagiaire').click(
			function (){
				if ($('#evenement_iContactTel').val() == 0)
				{
					$('#evenement_iContactTel').val(1); 
					$("#evenement_zContactTel").attr('disabled', 'disabled');
					$('#appelStagiaire').val("C'est le prof qui appelle");
				}else{
					$('#evenement_iContactTel').val(0);
					$("#evenement_zContactTel").removeAttr('disabled');
					$('#appelStagiaire').val("C'est le stagiaire qui appelle");
				}
		});
		$("#evenement_iTypeEvenementId").change(function(){
				$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:getTypeEvenement", iTypeEvenementId:$('#evenement_iTypeEvenementId').val()}, function(datas){
					$('#div-stagiaire-liste').hide();
					//$('#evenement_zStagiaire').val('');
					$('#evenement_zLibelle').val('');
					$('#evenement_zLibelle').val(datas["client_zNom"]); 

					var zDuree = datas["typeevenements_iDure"]; 
					if (datas["typeevenements_iDureeTypeId"] == 1){
						zDuree += ' heures';
					}else{
						zDuree += ' minutes';
					}
					$("#evenement_iDuree").val(zDuree);
				});
		});

		$("#periodemonth").click(function(){
			$('.NombreOccurenceDuplication').show();
			$('.DateFinDuplication').hide();
			$('#DateFinDuplicationJours').hide();

			$('.datePeriodicite').removeAttr('tmt:required');
			$('.datePeriodicite').removeAttr('tmt:message');

			$('.NombreOccurencePeriodicite').attr({'tmt:required':'true'});
		});
		$('#dtcm_event_rdv_periodiciteFin').change(
			function (){
				$('.plagePeriodicite2').attr({'checked':'checked'});
				$('#dtcm_event_rdv_periodiciteFin').attr({'tmt:required':'true'});
				$('.plagePeriodicite1').removeAttr('checked');
				$('#evenement_finPeriodiciteOccurence1').removeAttr('tmt:required');

				form = document.getElementById('edit_form');
				form.tmt_validator = new tmt_formValidator(form);
			}
		);
		$('#evenement_finPeriodiciteOccurence1').change(
			function (){
				$('.plagePeriodicite1').attr({'checked':'checked'});
				$('#evenement_finPeriodiciteOccurence1').attr({'tmt:required':'true'});
				$('.plagePeriodicite2').removeAttr('checked');
				$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');

				form = document.getElementById('edit_form');
				form.tmt_validator = new tmt_formValidator(form);
			}
		);
		$('#evenement_periodiciteMensuel21').change(
			function (){
				$('.selectEvenement_periodiciteMensuel2').attr({'checked':'checked'});
				$('.selectEvenement_periodiciteMensuel1').removeAttr('checked');
			}
		);
		$('#evenement_periodiciteMensuel11').change(
			function (){
				$('.selectEvenement_periodiciteMensuel1').attr({'checked':'checked'});
				$('.selectEvenement_periodiciteMensuel2').removeAttr('checked');
			}
		);


		$('#addNewStagiaire').click(
			function (){
				$('#edit_form').removeAttr("tmt:validate");
				$('#edit_form').attr({'action':$('#urlAjoutStagiaire').val()});
				$('#edit_form').submit();
			}
		);

		$("#evenement_iDupliquer").click(
			function (){
				var checked = $("#evenement_iDupliquer").attr('checked');
				if (checked){
					$('.periodicite').show();
					$('.evenement_periodiciteQuotidienne').hide();
					$('.evenement_periodiciteHebdomadaire').hide();
					$('.evenement_periodiciteMensuel1').hide();
					$('#finPeriodiciteOccurence').val(1);
					var dtcm_event_rdv = $('#dtcm_event_rdv').val().split(' ');
					var dateDebut = dtcm_event_rdv[0]; 
					$('#dtcm_event_rdv_periodicite').val(dateDebut);

					var heureDebut = dtcm_event_rdv[1]; 
					var heureDebutFinal = heureDebut.split(':');
					var heureDebutRendezVous = heureDebutFinal[0]+':'+heureDebutFinal[1];
					$("#evenement_heureDebutRendezVous option:selected").attr("selected",'');// on met simplement la valeur de l'attribut à vide
					$('#evenement_heureDebutRendezVous option[value='+heureDebutRendezVous+']').attr("selected","selected");

					var evenement_iDuree = $('#evenement_iDuree').val();
					$("#evenement_heureDureeRendezVous option:selected").attr("selected",'');// on met simplement la valeur de l'attribut à vide
					$('#evenement_heureDureeRendezVous option[value='+evenement_iDuree+']').attr("selected","selected");


					gererAffichagePeriodicite(2);
				}else{
					$('.periodicite').hide();			
					$('#evenement_heureDebutRendezVous').removeAttr('tmt:required');
					$('#evenement_heureDureeRendezVous').removeAttr('tmt:required');
					$('#dtcm_event_rdv_periodicite').removeAttr('tmt:required');
					$('#evenement_finPeriodiciteOccurence1').removeAttr('tmt:required');
					$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');
					$('#finPeriodiciteOccurence').val(0);
					$('#periodiciteMensuel1').val(0);
				}	
				form = document.getElementById('edit_form');
				form.tmt_validator = new tmt_formValidator(form); 			
			}
		);
	}); 

	var autoCompleteJson= function(data){
		var parsed=[];
		for (var i=0; i<data.length;i++){
			var row=data[i];
			parsed.push({
				data: row,
				value: row["ID"],
				result: row["post_title"]
			});
		}
		return parsed;
	}

	function selectPeriodiciteQuotidienne (){
		gererAffichagePeriodicite (1)
	}
	function selectPeriodiciteHebdo (){
		gererAffichagePeriodicite (2)
	}
	function selectPeriodiciteMensuel (){
		gererAffichagePeriodicite (3)
	}
	function getVal (valeur){
		$('#finPeriodiciteOccurence').val(valeur);
		if (valeur == 1)
		{
			$('#evenement_finPeriodiciteOccurence1').attr({'tmt:required':'true'});
			$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');
		}else{
			$('#dtcm_event_rdv_periodiciteFin').attr({'tmt:required':'true'});
			$('#evenement_finPeriodiciteOccurence1').removeAttr('tmt:required');
		}
		form = document.getElementById('edit_form');
		form.tmt_validator = new tmt_formValidator(form); 			
	}
	function getVal1 (valeur){
		$('#periodiciteMensuel1').val(valeur);
		if (valeur == 1)
		{
			$('#evenement_periodiciteMensuel11').attr({'tmt:required':'true'});
			$('#evenement_periodiciteMensuel12').attr({'tmt:required':'true'});
			$('#evenement_periodiciteMensuel23').removeAttr('tmt:required');
		}else{
			$('#evenement_periodiciteMensuel23').attr({'tmt:required':'true'});
			$('#evenement_periodiciteMensuel11').removeAttr('tmt:required');
			$('#evenement_periodiciteMensuel12').removeAttr('tmt:required');
		}
		form = document.getElementById('edit_form');
		form.tmt_validator = new tmt_formValidator(form); 			
	}

	function gererAffichagePeriodicite (iPeriodicite){
		if(iPeriodicite == 1){
			$('.evenement_periodiciteHebdomadaire').hide();
			$('.evenement_periodiciteMensuel1').hide();
			$('.evenement_periodiciteQuotidienne').show();

			$('#evenement_periodiciteMensuel11').removeAttr('tmt:required');
			$('#evenement_periodiciteMensuel12').removeAttr('tmt:required');
			$('#evenement_periodiciteMensuel23').removeAttr('tmt:required');
		}
		if(iPeriodicite == 2){
			$('.evenement_periodiciteQuotidienne').hide();
			$('.evenement_periodiciteMensuel1').hide();
			$('.evenement_periodiciteHebdomadaire').show();

			$('#evenement_periodiciteMensuel11').removeAttr('tmt:required');
			$('#evenement_periodiciteMensuel12').removeAttr('tmt:required');
			$('#evenement_periodiciteMensuel23').removeAttr('tmt:required');
		}
		if(iPeriodicite == 3){
			$('.evenement_periodiciteQuotidienne').hide();
			$('.evenement_periodiciteHebdomadaire').hide();
			$('.evenement_periodiciteMensuel1').show();

			$('#evenement_periodiciteMensuel11').attr({'tmt:required':'true'});
			$('#evenement_periodiciteMensuel12').attr({'tmt:required':'true'});
			$('#evenement_periodiciteMensuel23').removeAttr('tmt:required');
		}
		$('#evenement_heureDebutRendezVous').attr({'tmt:required':'true', 'tmt:invalidindex':'0'});
		$('#evenement_heureDureeRendezVous').attr({'tmt:required':'true', 'tmt:invalidindex':'0'});

		$('#dtcm_event_rdv_periodicite').attr({'tmt:required':'true'});
		$('#evenement_finPeriodiciteOccurence').attr({'checked':'checked'});
		$('#evenement_finPeriodiciteOccurence1').attr({'tmt:required':'true'});
		$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');
		

		var finPeriodiciteOccurence = $('#finPeriodiciteOccurence').val();
		if (finPeriodiciteOccurence == 1)
		{
			$('#evenement_finPeriodiciteOccurence1').attr({'tmt:required':'true'});
			$('#dtcm_event_rdv_periodiciteFin').removeAttr('tmt:required');
		}else{
			$('#dtcm_event_rdv_periodiciteFin').attr({'tmt:required':'true'});
			$('#evenement_finPeriodiciteOccurence1').removeAttr('tmt:required');
		}
		form = document.getElementById('edit_form');
		form.tmt_validator = new tmt_formValidator(form);
	}
	DD_roundies.addRule('div.formevent', '5px');
	DD_roundies.addRule('input.boutonform', '5px');

	function isDate(sDate){
		var sSeparator = '/';
		if(!sDate.match("^[0-9]{2}/[0-9]{2}/[0-9]{4}$")) return false;
		var arDate = sDate.split(sSeparator);
		var iDay = parseInt(arDate[0]);
		var iMonth = parseInt(arDate[1]);
		var iYear = parseInt(arDate[2]);
		var arDayPerMonth = [31,(isLeapYear(iYear))?29:28,31,30,31,30,31,31,30,31,30,31];
		if(!arDayPerMonth[iMonth-1]) return false;
		return (iDay <= arDayPerMonth[iMonth-1] && iDay > 0);
	}
	
	function isHour(sHour){
		var sSeparator = ':';
		var withSeconds = false;
		if(sHour.match("^[0-9]{2}:[0-9]{2}:[0-9]{2}$")) var withSeconds = true;
		else if(!sHour.match("^[0-9]{2}:[0-9]{2}$")) return false;
		var arHour = sHour.split(sSeparator);
		var iHour = parseInt(arHour[0]);
		var iMinute = parseInt(arHour[1]);
		if(withSeconds)	var iSecs = parseInt(arHour[2]);
		else 						var iSecs = 0;
		return 	(iHour >= 0 && iHour < 24) && (iMinute >= 0 && iMinute < 60) && (iSecs >= 0 && iSecs < 60);
	}
	
	function isLeapYear(iYear){
		return ((iYear%4==0 && iYear%100!=0) || iYear%400==0);
	}
	
	function isDateHour(sDateHour){
		var sSeparator = ' ';
		var arDateHour = sDateHour.split(sSeparator);
		return (arDateHour[0] && arDateHour[1] && isDate(arDateHour[0]) && isHour(arDateHour[1]));
	}
</script>
{/literal}
<form id="edit_form" action="{jurl 'evenement~FoEvenement:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
	<input type="hidden" name="evenement_id" id="evenement_id" value="{if $bEdit}{$oEvenement->evenement_id}{else}0{/if}" />
	<input type="hidden" name="evenement_origine" id="evenement_origine" value="{if $bEdit}{$oEvenement->evenement_origine}{else}2{/if}" />
	<input type="hidden" name="sendMail" id="sendMail" value="0" />
	<input type="hidden" name="evenement_iPriorite" id="evenement_iPriorite" value="1" />
	<input type="hidden" name="iAffichage" id="iAffichage" value="{$iAffichage}" />
	<input type="hidden" name="zDate" id="zDate" value="{$zDate}" />
	<input type="hidden" class="text" name="evenement_zLibelle" id="evenement_zLibelle" value="{if $bEdit}{$oEvenement->evenement_zLibelle}{/if}"> 
	<input type="hidden" name="evenement_iContactTel" id="evenement_iContactTel" value="0" />
	<input type="hidden" name="finPeriodiciteOccurence" id="finPeriodiciteOccurence" value="0" />
	<input type="hidden" name="periodiciteMensuel1" id="periodiciteMensuel1" value="0" />
	<input type="hidden" name="evenement_zDateHeureSaisie" id="evenement_zDateHeureSaisie" value="{if $bEdit}{$oEvenement->evenement_zDateHeureSaisie}{else}{$currentDate}{/if}" />
	<input type="hidden" name="x" id="x" value="{$x}" />

	<input type="hidden" name="prec" id="prec" value="{$prec}" />
	<input type="hidden" name="debut" id="debut" value="{$debut}" />
	<input type="hidden" name="fin" id="fin" value="{$fin}" />

	<h2>Création / Modification d’évènement</h2>
	<p class="clear">
		<label>Types d’évènement *</label>
		{foreach $toTypeEvenement as $oTypeEvenement}
			<input type="hidden" id="typeevenements_iStagiaireActif_{$oTypeEvenement->typeevenements_id}" name="typeevenements_iStagiaireActif_{$oTypeEvenement->typeevenements_id}" value="{$oTypeEvenement->typeevenements_iStagiaireActif}" />
		{/foreach}
		<select name="evenement_iTypeEvenementId" class="text" id="evenement_iTypeEvenementId" tmt:invalidindex="0" tmt:required="true" >
			<option value="0">----------------------Séléctionner----------------------</option>
			{foreach $toTypeEvenement as $oTypeEvenement}
				<option value="{$oTypeEvenement->typeevenements_id}" {if isset($tEvent['evenement_iTypeEvenementId']) && $tEvent['evenement_iTypeEvenementId'] == $oTypeEvenement->typeevenements_id}selected="selected"{/if}
				{if $bEdit}
					{if $oEvenement->evenement_iTypeEvenementId==$oTypeEvenement->typeevenements_id}
						selected=selected 
					{/if}
				{else}
					{if $oTypeEvenement->typeevenements_id == $idTypeEvenementCourTelephone}
							selected=selected
					{/if}
				{/if}
				>{$oTypeEvenement->typeevenements_zLibelle}</option>
			{/foreach}
		</select>
	</p>
	<p class="clear">
		<label>Description</label>
		<textarea style="height:auto" name="evenement_zDescription" id="evenement_zDescription">{if isset($tEvent['evenement_zDescription'])}{$tEvent['evenement_zDescription']}{/if}{if $bEdit}{$oEvenement->evenement_zDescription}{/if}</textarea>
	</p> 
	<p class="clear">
		<label>Projet</label>
		<input type="hidden" name="evenement_iStagiaire" id="evenement_iStagiaire" value="{if $bEdit}{$oEvenement->evenement_iStagiaire}{else}0{/if}" />
		<input type="hidden" name="urlTraitementStagiaireRecherche" id="urlTraitementStagiaireRecherche" value="{jurl 'client~FoClient:rechercherStagiaire'}" />
		<input type="hidden" name="urlAjoutStagiaire" id="urlAjoutStagiaire" value="{jurl 'client~FoClient:add', array('iEvenementId'=>$iEvenementId), false}" />
		<input style="width:267px;" type="text" class="text" name="evenement_zStagiaire" id="evenement_zStagiaire" value="{if $bEdit}{if isset($oEvenement->evenement_zStagiaire)}{$oEvenement->evenement_zStagiaire}{/if}{/if}" />
		<!--&nbsp;<a href="#" title="Rechercher" id="rechercherStagiaire">
			<img src="{$j_basepath}design/front/images/design/rechercher.png" alt="Ajouter un projet" />
		</a>
		&nbsp;<a href="#" title="Ajouter un stagiaire" id="addNewStagiaire">
			<img src="{$j_basepath}design/front/images/design/buttons/plus.png" alt="Ajouter un stagiaire" />
		</a>-->		
		{if $bEdit && isset($oEvenement->evenement_iStagiaire) && $oEvenement->evenement_iStagiaire > 0}
		&nbsp;<a href="{$oEvenement->oWpPost->guid}" id="imgInfoStagiaire" title="Detail du projet" target="_blank">
			<img src="{$j_basepath}design/front/images/design/icone_info.png" alt="Detail du projet" />
		</a>
		{if $bEdit && isset($oPost->guid) && $oPost->guid != ""}
		&nbsp;<a href="{$oPost->guid}" id="imgInfoStagiaire" title="Acceder au blog du post" target="_blank">
			<img src="{$j_basepath}design/front/images/design/icone_blog.png" alt="Acceder au blog du post" width="42" height="22"/>
		</a>
		{/if}
		{/if}
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
		<label>URL Blog</label>
		<a href="#" id="txtsociete" target="_blank" style="color:#3b5998;cursor:pointer;text-decoration:none;"></a>
	</p> 
	<p class="clear" id="p-txtville"> 
		<label>Adresse</label>
		<input type="text" name="txtville" id="txtville" class="text" readonly="readonly"/>
	</p> 

	<p class="rdv clear">
		<label>Rendez vous *</label>
		<input type="text" class="date text" id="dtcm_event_rdv" name="dtcm_event_rdv" value="{$zDateDefaultEvent}" tmt:required="true"/>
	</p> 
	<p class="duree clear">
		<label>Durée</label>
		{if isset($tEvent['evenement_iDuree'])}
			{assign $zDureParDefaut = $tEvent['evenement_iDuree']}
		{else}
			{if !$bEdit}
				{assign $zDureParDefaut = '30 minutes'}
			{else}
				{if $oEvenement->evenement_iDureeTypeId == 1}
					{assign $zDureParDefaut = $oEvenement->evenement_iDuree . ' heures'}
				{else}
					{assign $zDureParDefaut = $oEvenement->evenement_iDuree . ' minutes'} 
				{/if}
			{/if}
		{/if}
		<select style="width:120px;"name="evenement_iDuree" class="text" id="evenement_iDuree">
			<option value="0">---------Durée---------</option>
			{foreach $toDurePeriodicite as $oDurePeriodicite}
			<option value="{$oDurePeriodicite}" {if isset($zDureParDefaut) && $oDurePeriodicite == $zDureParDefaut}selected='selected'{/if}>{$oDurePeriodicite}</option>
			{/foreach}
		</select>
	</p>
	<p class="rappel clear">
		<label>Rappel</label>
		<input type="radio" class="radio" name="evenement_iRappel" id="evenement_iRappel" value="1" {if $bEdit}{if $oEvenement->evenement_iRappel>0}checked="checked"{/if}{/if}/>
		<span>Oui</span>
		<input type="radio" class="radio" name="evenement_iRappel" id="evenement_iRappel" value="0" {if $bEdit}{if $oEvenement->evenement_iRappel==0}checked="checked"{/if}{else}checked="checked"{/if}/>
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
		<input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="1" {if $bEdit}{if $oEvenement->evenement_iStatut == STATUT_PUBLIE}checked="checked"{/if}{else}checked="checked"{/if} tmt:required="true"/><span>Afficher</span><input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="2" {if $bEdit}{if $oEvenement->evenement_iStatut == STATUT_NON_PUBLIE}checked="checked"{/if}{/if} /><span>Ne pas afficher</span><input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="0" {if $bEdit}{if $oEvenement->evenement_iStatut == STATUT_DESACTIVE}checked="checked"{/if}{/if} /><span>Annuler</span>
	</p>
	<div class="enveloperiode clear" {if $bEdit}style="display:none;"{/if}>
		<p class="master clear">
			<label><strong>Périodicité</strong></label>
			<input type="checkbox" name="evenement_iDupliquer" id="evenement_iDupliquer" value="1" />
		</p>
		<div class="periodicite clear" style="display:none;">
		<fieldset class="heure">
    <legend>Heure du rendez vous</legend>
		<p class="clear">
			<label>Debut</label>
			<select style="width:120px;"name="evenement_heureDebutRendezVous" class="text" id="evenement_heureDebutRendezVous">
				<option value="0">---------Heure---------</option>
				{foreach $toPeriodicite as $oPeriodicite}
				<option value="{$oPeriodicite}">{$oPeriodicite}</option>
				{/foreach}
			</select>
		<!--/p>
		<p class="clear"-->
			<label>Durée</label>
			<select style="width:120px;"name="evenement_heureDureeRendezVous" class="text" id="evenement_heureDureeRendezVous">
				<option value="0">---------Durée---------</option>
				{foreach $toDurePeriodicite as $oDurePeriodicite}
				<option value="{$oDurePeriodicite}">{$oDurePeriodicite}</option>
				{/foreach}
			</select>
		</p>
		</fieldset>
    <fieldset class="fieldperiode">
    	<legend>Periodicité</legend>
		<div class="leftperiode">
    <p class="clear">
    	 <label>Quotidienne</label>
			<input type="radio" name="choixperiode" id="periodicite" class="radio choixperiode" value="1" onclick="selectPeriodiciteQuotidienne()"/>
		</p>
		<p class="clear">
			<label>Hebdomadaire</label>
			<input type="radio" name="choixperiode" id="periodicite" class="radio choixperiode" value="2" checked="checked" onclick="selectPeriodiciteHebdo()"/>
    </p>
		<p class="clear">
			<label>Mensuelle</label>
			<input type="radio" name="choixperiode" id="periodicite" class="radio choixperiode" value="3" onclick="selectPeriodiciteMensuel()"/>
		</p>
    </div>
    <div class="rightperiode">
		<div class="clear evenement_periodiciteQuotidienne">
			<p class="clear">
        <label>Tous les</label>
         <select name="evenement_periodiciteQuotidienne" id="evenement_periodiciteQuotidienne">
          {for $i=1; $i<=7; $i++}
            <option value="{$i}">{$i}</option>
          {/for}
        </select>
        <span> jours</span>
      </p>
		</div>
		<div class="clear evenement_periodiciteHebdomadaire">
    	<p class="clear tilte">
      	<span>Tous les</span>
        <select name="evenement_periodiciteHebdomadaire" id="evenement_periodiciteHebdomadaire">
				{for $i=1; $i<=4; $i++}
					<option value="{$i}">{$i}</option>
				{/for}
				</select>
        <span>semaine(s) le </span>
      </p>
      <p class="jour clear">
      	<input type="checkbox" name="evenement_iLundi" id="evenement_iLundi" value="1" />
        <span>Lundi</span>
        <input type="checkbox" name="evenement_iMardi" id="evenement_iMardi" value="1" />
        <span>Mardi</span>
        <input type="checkbox" name="evenement_iMercredi" id="evenement_iMercredi" value="1" />
        <span>Mercredi</span>
        <span class="extra clear">
        <input type="checkbox" name="evenement_iJeudi" id="evenement_iJeudi" value="1" />
        <span>Jeudi</span>
        <input type="checkbox" name="evenement_iVendredi" id="evenement_iVendredi" value="1" />
        <span>Vendredi</span>
        </span>
      </p>
		</div>
		
		<div class="clear evenement_periodiciteMensuel1">
    	<p class="top clear">
        <input type="radio" class="radio selectEvenement_periodiciteMensuel1" name="evenement_periodiciteMensuel1" id="evenement_periodiciteMensuel1" value="1" onclick="getVal1(1);" checked="checked"/>
        <span>Le</span>
        <select name="evenement_periodiciteMensuel11" id="evenement_periodiciteMensuel11">
          {for $i=1; $i<=31; $i++}
            <option value="{$i}">{$i}</option>
          {/for}
           </select>
        <span>tous les</span> 
        <select name="evenement_periodiciteMensuel12" id="evenement_periodiciteMensuel12">
          {for $i=1; $i<=12; $i++}
            <option value="{$i}">{$i}</option>
          {/for}
        </select> 
        <span>mois</span>
      </p>
      <p class="bottom clear">
      <input type="radio" class="radio selectEvenement_periodiciteMensuel2" name="evenement_periodiciteMensuel1" id="evenement_periodiciteMensuel1" value="2" onclick="getVal1(2);"/>
			<span>Le</span> 
			<select name="evenement_periodiciteMensuel21" class="extra" id="evenement_periodiciteMensuel21">
				<option value="1">Premier</option>
				<option value="2">Deuxième</option>
				<option value="3">Troisième</option>
				<option value="4">Quatrième</option>
				<option value="5">Dernier</option>
			</select>
			<select name="evenement_periodiciteMensuel22" class="extra" id="evenement_periodiciteMensuel22">
				<option value="1">Lundi</option>
				<option value="2">Mardi</option>
				<option value="3">Mercredi</option>
				<option value="4">Jeudi</option>
				<option value="5">Vendredi</option>
			</select>
			<span>tous les</span> 
			<select name="evenement_periodiciteMensuel23" id="evenement_periodiciteMensuel23">
				{for $i=1; $i<=12; $i++}
					<option value="{$i}">{$i}</option>
				{/for}
			</select> 
      <span>mois</span>
      </p>
		</div>
    </div>
    </fieldset>
    <fieldset class="plage">
    <legend>Plage de periodicité</legend>
    <div class="plageleft">
      <p class="clear">
        <label>Debut</label>
        <input type="text" id="dtcm_event_rdv_periodicite" name="dtcm_event_rdv_periodicite" class="datePeriodicite text" style="width:90px;"/>
      </p>
    </div>
    <div class="plageright">
      <p class="clear">
        <input type="radio" class="radio plagePeriodicite1" name="evenement_finPeriodiciteOccurence" id="evenement_finPeriodiciteOccurence" value="1" onclick="getVal(1);"/> 
        <span>Fin apres</span>
        <input type="text" class="text" name="evenement_finPeriodiciteOccurence1" tmt:filters="numbersonly" id="evenement_finPeriodiciteOccurence1" > 								 				
        <span>occurences</span>
      </p>
      <p class="clear">
        <input type="radio" class="radio plagePeriodicite2" name="evenement_finPeriodiciteOccurence" id="evenement_finPeriodiciteOccurence" value="2" onclick="getVal(2);"/> 
        <span>Fin le</span>
        <input type="text" id="dtcm_event_rdv_periodiciteFin" class="text extra datePeriodiciteFin" name="dtcm_event_rdv_periodiciteFin" />
      </p>
    </div>
    </fieldset>
		</div>
	</div>
	<div class="input">
		<a href="#" onclick="javascript:history.back();"><input type="button" value="Annuler" class="boutonform" /></a>
		<input type="button" value="{if $bEdit}Valider{else}Créer{/if}" class="boutonform submitFormulaire" />
		<input type="button" value="Valider avec envoi de mail" class="boutonform longtext submitFormMail" />
	</div>
	<div class="input" style="width:480px;">
		<p class="errorMessage" id="errorMessage" style="text-align:center;color:red;"></p>
	</div>
</form>