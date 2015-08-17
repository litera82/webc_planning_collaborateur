{assign $idTypeEvenementCourTelephone1 = ID_TYPE_EVENEMENT_COUR_TELEPHONE}
{assign $cours_produit=COURS_PRODUIT}
{assign $cours_annule=COURS_ANNULE}
{assign $cours_deplace=COURS_DEPLACE}

{literal}
<script type="text/javascript">
	DD_roundies.addRule('div.arrondi', '5px');
	DD_roundies.addRule('ul.titleselection', '5px');
	DD_roundies.addRule('div.blochoice', '8px'); 
	DD_roundies.addRule('div.contentselect', '8px');
	DD_roundies.addRule('input.btplan', '5px');
	DD_roundies.addRule('div.planheader', '5px');
	DD_roundies.addRule('div.headertab', '5px');
	DD_roundies.addRule('div.footertab', '5px');

	
	$(function(){ 
		closeEventRapid () ;
		$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:checkEvetToCopyExist"}, function(datas){
			$('#showColler').val(datas) ;
		});

		/*$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:getListeTypeEvenementUilisateur", user:{/literal}{$userId}{literal}}, function(row){
			var htmlSelect = '<select id="evenement_iTypeEvenementId" class="text" name="evenement_iTypeEvenementId" style="float: left;font-size:1em;padding: 2px;width: 300px;border:1px solid #000000;height:20px;"><option value="0">---------------------------------Séléctionner---------------------------------</option>';
			var idTypeEvenementCourTelephone = {/literal}{$idTypeEvenementCourTelephone1}{literal} ;
			for (i=0 ; i<row.length ; i++){	
				selected="";
				if (row[i].typeevenements_id == idTypeEvenementCourTelephone){
					selected="selected" ; 
				}
				htmlSelect += '<option value="'+row[i].typeevenements_id+'" '+selected+'>'+row[i].typeevenements_zLibelle+'</option>'; 
			}
			htmlSelect += '</select>';
			$('.actionEventHebdo').attr('typeEvent', htmlSelect);
		});*/
		getSelectBoxTypeEvent({/literal}{$userId}{literal}, 'actionEventHebdo') ;
		getSelectBoxTypeEvent({/literal}{$userId}{literal}, 'actionEventHebdozz') ;

		$('#domaines').change(
			function (){
				window.location.href = $("#urlRechercheParCritere").val() + '&iTypeEvenementId='+  $(this).val() + '&iUtilisateurId1=' + $('#employes').val();
			}
		);
		$('#employes').change(
			function (){
				window.location.href = $("#urlRechercheParCritere").val() + '&iTypeEvenementId='+  $('#domaines').val() + '&iUtilisateurId1=' + $(this).val();
			}
		);
		$('#btSemaine2').click(
			function (){
				window.location.href = $("#urlRechercheParCritere").val() + '&iAffichage=1';
			}
		);
		$('#btAujourdhui2').click(
			function (){
				window.location.href = $("#urlRechercheParCritere").val() + '&iAffichage=2';
			}
		);
		$('#btMois2').click(
			function (){
				window.location.href = $("#urlRechercheParCritere").val() + '&iAffichage=3';
			}
		);
		$(".deleteEvent").click(
			function (){
				if(confirm ("Etes vous sur de vouloir supprimer cet événement?"))
				{
					document.location.href=$("#deleteEvent").attr('urlDelete');
				}
			}
		);

		$('.conge').hover(
			function(){ 
				$('.conge').parent().find('.ajouterEventHebdo').hide();
				$('.conge').parent().find('.actionEventHebdo').hide();
				$('.conge').parent().find('.pasteEventHebdo').hide();
			}
		);
	}); 
	function testEventExist(zDate, iTime, zUrl){
		$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:testEventExist", zDate:zDate, iTime:iTime}, function(datas){
			if (datas == 0){
				document.location.href= zUrl + '&x=0';
			}else{
				if(confirm ("La plage horaire est déja occupée.\nVoulez-vous continuer ?"))
				{
					document.location.href= zUrl + '&x=1';
				}
			}
			return false;
		});
	}
	function testEventExist1(zUrl){
		document.location.href= zUrl + '&x=0';
	}

	function copierEvent(iEventId, cellId){
		//afficherMasque();
		$.loader({width:340, height:39, content:'<img src="{/literal}{$j_basepath}design/front/images/design/loading14.gif"/>{literal}'});
		$.ajax({
			 type:"POST",
			 url:$('#urlCopierEvent').val(),
			 data:{	iEventId:iEventId
				 },
			 async:false,
			 success:function(resultat){
				$('#tdToCopy').val(cellId) ;
				$('#tdToCut').val('') ;
				$('td#'+cellId).html('');
				$('td#'+cellId).html(resultat);
				$('#showColler').val(1);
			 }
		});
		$.loader('close') ;
	}
	function couperEvent(iEventId, cellId){
		$.loader({width:340, height:39, content:'<img src="{/literal}{$j_basepath}design/front/images/design/loading14.gif"/>{literal}'});
		$.ajax({
			 type:"POST",
			 url:$('#urlCouperEvent').val(),
			 data:{iEventId:iEventId},
			 async:false,
			 success:function(resultat){
				$('#tdToCopy').val('') ;
				$('#tdToCut').val(cellId) ;
				$('td#'+cellId).html('');
				$('td#'+cellId).html(resultat);
				$('#showColler').val(1)
			 }
		});
		$.loader('close') ; 
	}
	function collerEvent(zDate, zTime, cellId){
		$.loader({width:340, height:39, content:'<img src="{/literal}{$j_basepath}design/front/images/design/loading14.gif"/>{literal}'}); 

		$.ajax({
			 type:"POST",
			 url:$('#urlCollerEvent').val(),
			 data:{zDate:zDate, zTime:zTime},
			 async:false,
			 success:function(resultat){
				if ($('#tdToCut').val() != '') {
					var cellToDelete = $('#tdToCut').val() ;	
					$('td#'+cellToDelete).find('.divajouterEvent').find('ul.conge').find('li').html(' ');
					$('td#'+cellToDelete).find('.divajouterEvent').find('ul.conge').find('li').attr('style','border-bottom:none;padding:0 0 0 0; height:3px;') ;
					$('#showColler').val(0) ;
				}else{
					$('#showColler').val(1) ;
				}
				$('#tdToCopy').val('') ;
				$('#tdToCut').val('') ;
				$('td#'+cellId).html('');
				$('td#'+cellId).html(resultat);
			 }
		});
		$.loader('close') ; 
	}
	function deleteEventRapid(iEventId, cellId){
		if(confirm ("Etes vous sur de vouloir supprimer cet événement?"))
		{
			$.loader({width:340, height:39, content:'<img src="{/literal}{$j_basepath}design/front/images/design/loading14.gif"/>{literal}'}); 
			$.ajax({
				 type:"POST",
				 url:$('#urlDeleteEventRapid').val(),
				 data:{	iEventId:iEventId},
				 async:false,
				 success:function(resultat){
					$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('li').html(resultat);
					$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('li').attr('style','border-bottom:none;padding:0 0 0 0; height:3px;') ; 
				 }
			});
			$.loader('close') ; 
		}
	}
</script>
{/literal}
<div class="planheader">
	<div class="inner">
		<div class="form clear">
		<input type="hidden" name="urlRechercheParCritere" id="urlRechercheParCritere" value="{jurl 'jelix_calendar~FoCalendar:index'}" />
		<input type="hidden" name="urlCopierEvent" id="urlCopierEvent" value="{jurl 'evenement~FoEvenement:copierEvent'}" />
		<input type="hidden" name="typeUser" id="typeUser" value="{$oUtilisateur->utilisateur_iTypeId}" />
		<input type="hidden" name="urlCouperEvent" id="urlCouperEvent" value="{jurl 'evenement~FoEvenement:couperEvent'}" />
		<input type="hidden" name="urlCollerEvent" id="urlCollerEvent" value="{jurl 'evenement~FoEvenement:collerEvent'}" />
		<input type="hidden" name="urlSaveEvent" id="urlSaveEvent" value="{jurl 'evenement~FoEvenement:saveEventRapid'}" />
		<input type="hidden" name="urlDeleteEvent" id="urlDeleteEvent" value="{jurl 'evenement~FoEvenement:deleteEvent'}" />
		<input type="hidden" name="urlDeleteEventRapid" id="urlDeleteEventRapid" value="{jurl 'evenement~FoEvenement:deleteEventRapid'}" />
		<input type="hidden" name="urlCheckEvetToCopyExist" id="urlCheckEvetToCopyExist" value="{jurl 'evenement~FoEvenement:checkEvetToCopyExist'}" />
		<input type="hidden" name="urlSaveDescEvent" id="urlSaveDescEvent" value="{jurl 'evenement~FoEvenement:saveDescEvent'}" />
		<input type="hidden" name="tdToCopy" id="tdToCopy" value="" />
		<input type="hidden" name="tdToCut" id="tdToCut" value="" />
		<input type="hidden" name="showColler" id="showColler" value="" />

		{if $oUtilisateur->utilisateur_decalageHoraire != 0}
			<span style="color: rgb(255, 255, 255); text-align: center; padding-left: 0px;">
				France / {$oPays->pays_zNom}
			</span>
		{/if}
		{*<!--
		{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}
		<select name="domaines" id="domaines" class="js-style-me">
			<option value="0">Tous</option>
			{foreach $toTypeEvenement as $oTypeEvenement}
				<option {if isset($iTypeEvenementId) && $iTypeEvenementId == $oTypeEvenement->typeevenements_id} selected="selected" {/if} value="{$oTypeEvenement->typeevenements_id}">{$oTypeEvenement->typeevenements_zLibelle}</option>
			{/foreach}
		</select>
		<select name="employes" id="employes" class="js-style-me">
			<option value="0">Tous les plannings</option>
			{foreach $toRessources as $oRessources}
				<option {if isset($iUtilisateurId1) && $iUtilisateurId1 == $oRessources->utilisateur_id} selected="selected" {/if} value="{$oRessources->utilisateur_id}">{$oRessources->utilisateur_zNom} {$oRessources->utilisateur_zPrenom}</option>
			{/foreach}
		</select>
		{/if}
		<input type="submit" id="btAujourdhui2" class="btplan" value="Aujourd'hui">
		<input type="submit" id="btSemaine2" class="btplan active" value="Semaine">
		<input type="submit" id="btMois2" class="btplan" value="Mois">
		-->*}
		</div>
		<div class="weekdate">
			<a title="left" href="{jurl 'jelix_calendar~FoCalendar:index', array('date' => $zDateDebSemainePrec, 'iGroupeId'=>$iGroupeId), false}"><img alt="left" src="{$j_basepath}design/front/images/design/bt-planning-left.png"></a>
			<span class="date">{$zIntervalsemaine}</span>
			<a title="right" href="{jurl 'jelix_calendar~FoCalendar:index', array('date' => $zDateDebSemaineSuiv, 'iGroupeId'=>$iGroupeId), false}"><img alt="right" src="{$j_basepath}design/front/images/design/bt-planning-right.png"></a>
		</div>
	</div>
</div>
<div class="plancontent">
	<div class="headertab">
		<table cellspacing="0" cellpadding="0" id="planinghead">
			<tbody>
				<tr>
					{assign $i=1}
					{foreach $tDateListe as $oDateListe}
						<th scope="col{$i}" style="border-right:none;">
							{if $i==1}Lun {/if}
							{if $i==2}Mar {/if}
							{if $i==3}Mer {/if}
							{if $i==4}Jeu {/if}
							{if $i==5}Ven {/if}
							{if $i==6}Sam {/if}
							{if $i==7}Dim {/if}
							{$oDateListe|date_format:"%d/%m/%Y"}
						</th>
					{assign $i++}
					{/foreach}
				</tr>
			</tbody>
		</table>
	</div>


	<div class="divplaning">
		<table class="divtableplaning" cellspacing="0" cellpadding="0" id="planning-content">
			<tbody>
				<!-- Line -->
				{foreach $toTimeListeDemiHeureDecalage as $oTimeListeDemiHeure}
					<tr class="busy25">
						<th class="thrond">
							{if $oUtilisateur->utilisateur_decalageHoraire != 0}
								<a href="#" style="color:#FFFFFF;text-align:center;font-size:1.1em;padding-left:12px;background:none;margin-left:0;" title="Heure France : {$oTimeListeDemiHeure->time1} / Heure {$oPays->pays_zNom} : {$oTimeListeDemiHeure->time2} (Décalage horaire : {$oUtilisateur->utilisateur_decalageHoraire} heure)">
									{$oTimeListeDemiHeure->time1} / {$oTimeListeDemiHeure->time2}
								</a>
							{else}
								<a href="#" style="color:#FFFFFF;text-align:center;font-size:1.1em;padding-left:32px;background:none;margin-left:0;" title="{$oTimeListeDemiHeure->time1}">
									{$oTimeListeDemiHeure->time1}
								</a>
							{/if}
						</th>
						{foreach $toDateListe as $oDateListe}
						{if $oUtilisateur->utilisateur_plageHoraireId == 2}
							{if $oTimeListeDemiHeure->time1 == '07:30' || $oTimeListeDemiHeure->time1 == '08:30' || $oTimeListeDemiHeure->time1 == '09:30' || $oTimeListeDemiHeure->time1 == '10:30' || $oTimeListeDemiHeure->time1 == '11:30' || $oTimeListeDemiHeure->time1 == '12:30' || $oTimeListeDemiHeure->time1 == '13:30' || $oTimeListeDemiHeure->time1 == '14:30' || $oTimeListeDemiHeure->time1 == '15:30' || $oTimeListeDemiHeure->time1 == '16:30' || $oTimeListeDemiHeure->time1 == '17:30' || $oTimeListeDemiHeure->time1 == '18:30' || $oTimeListeDemiHeure->time1 == '19:30' || $oTimeListeDemiHeure->time1 == '20:30' || $oTimeListeDemiHeure->time1 == '21:30'}
								<td style="border-bottom:1px solid #6C6C6C;" id="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" onmouseover="javascript:activetd(1, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')" onmouseout="javascript:activetd(0, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')">
							{else}
								<td style="border-bottom:1px solid #DCDCDC;" id="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" onmouseover="javascript:activetd(1, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')" onmouseout="javascript:activetd(0, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')">
							{/if}
						{else}
							{if $oUtilisateur->utilisateur_plageHoraireId == 3}
								{if $oTimeListeDemiHeure->time1 == '07:40' || $oTimeListeDemiHeure->time1 == '08:40' || $oTimeListeDemiHeure->time1 == '09:40' || $oTimeListeDemiHeure->time1 == '10:40' || $oTimeListeDemiHeure->time1 == '11:40' || $oTimeListeDemiHeure->time1 == '12:40' || $oTimeListeDemiHeure->time1 == '13:40' || $oTimeListeDemiHeure->time1 == '14:40' || $oTimeListeDemiHeure->time1 == '15:40' || $oTimeListeDemiHeure->time1 == '16:40' || $oTimeListeDemiHeure->time1 == '17:40' || $oTimeListeDemiHeure->time1 == '18:40' || $oTimeListeDemiHeure->time1 == '19:40' || $oTimeListeDemiHeure->time1 == '20:40' || $oTimeListeDemiHeure->time1 == '21:40'}
									<td style="border-bottom:1px solid #6C6C6C;" id="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" onmouseover="javascript:activetd(1, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')" onmouseout="javascript:activetd(0, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')">
								{else}
									<td style="border-bottom:1px solid #DCDCDC;" id="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" onmouseover="javascript:activetd(1, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')" onmouseout="javascript:activetd(0, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')">
								{/if}
							{else}	 
								<td style="border-bottom:1px solid #6C6C6C;" id="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" onmouseover="javascript:activetd(1, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')" onmouseout="javascript:activetd(0, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')">
							{/if}
						{/if}
							<div class="clear divajouterEvent">
								<a class="ajouterEventHebdo" id="ajouterEvent" title="Ajouter un évènement" onclick="javascript:testEventExist('{$oDateListe->zDate}', '{$oTimeListeDemiHeure->time1}', '{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>0,  'zDate' => $oDateListe->zDate,'iTime' => $oTimeListeDemiHeure->time1), false}');" style="cursor:pointer;">
									<img alt="ajouter" src="{$j_basepath}design/front/images/design/plus.png">
								</a>
								<a class="actionEventHebdo" cellId="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" id="actionEvent" title="Ajouter un évènement rapidement" typeEvent="" iUtilisateurId="{$userId}" href="#" style="cursor:pointer;" zDate="{$oDateListe->zDate|date_format:'%d/%m/%Y'}" iTime="{$oTimeListeDemiHeure->time1}">
									<img alt="ajouter" src="{$j_basepath}design/front/images/design/action.png">
								</a>
								<a class="pasteEventHebdo" id="pasteEvent" title="Coller l'évènement" style="cursor:pointer;" onclick="collerEvent('{$oDateListe->zDate}', '{$oTimeListeDemiHeure->time1}', 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')" >
									<img alt="Coller l'évènement" src="{$j_basepath}design/front/images/design/pictos/coller.png">
								</a>
								{assign $iCpt=1}
								{if sizeof($toEventUser) > 0}
								{assign $iCpt1=1}
									{foreach $toEventUser as $oEventUser}
										{if $oTimeListeDemiHeure->time1 == $oEventUser->evenement_heures && $oDateListe->zDate == $oEventUser->evenement_date}
											<ul class="conge" style="width:146px; {if $iCpt1 > 1}padding-top:30px;{/if}">
												{*<!--<a class="commentEventHebdo" id="commentEvent" cellId="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" title="Description de l'événement" href="#tooltip" colorbg="{$oEventUser->typeevenements_zCouleur}" iEventId="{$oEventUser->evenement_id}" description="{$oEventUser->evenement_zDescription}" style="cursor:pointer;">
													<img width="16px" height="16px" alt="couper" src="{$j_basepath}design/front/images/design/pictos/comment1.jpg">
												</a>-->*}
												{if $oEventUser->typeevenements_id == ID_TYPE_EVENEMENT_DISPONIBLE || $oEventUser->typeevenements_id == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE}
													<a class="ajouterEventHebdozz" id="ajouterEvent" title="Ajouter un évènement" onclick="javascript:testEventExist1('{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>0,  'zDate' => $oDateListe->zDate,'iTime' => $oTimeListeDemiHeure->time1), false}');" style="cursor:pointer;">
														<img alt="ajouter" src="{$j_basepath}design/front/images/design/plus.png">
													</a>

													<a class="actionEventHebdozz" cellId="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" id="actionEvent" title="Ajouter un évènement rapidement" typeEvent="" iUtilisateurId="{$userId}" href="#" style="cursor:pointer;" zDate="{$oDateListe->zDate|date_format:'%d/%m/%Y'}" iTime="{$oTimeListeDemiHeure->time1}">
														<img alt="ajouter" src="{$j_basepath}design/front/images/design/action.png">
													</a>
													<a class="pasteEventHebdozz" id="pasteEvent" title="Coller l'évènement" style="cursor:pointer;" onclick="collerEvent('{$oDateListe->zDate}', '{$oTimeListeDemiHeure->time1}', 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}')" >
														<img alt="Coller l'évènement" src="{$j_basepath}design/front/images/design/pictos/coller.png">
													</a>
												{/if}
												<a class="copyEventHebdo" id="copyEvent" title="Copier l'événement" onclick="copierEvent({$oEventUser->evenement_id}, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}');" style="cursor:pointer;">
													<img alt="copier" src="{$j_basepath}design/front/images/design/pictos/copier.gif">
												</a>
												<a class="cutEventHebdo" id="cutEvent" title="Couper l'événement" onclick="couperEvent({$oEventUser->evenement_id}, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}');" style="cursor:pointer;">
													<img alt="couper" src="{$j_basepath}design/front/images/design/pictos/couper.png" width="16px" height="16px">
												</a>
												<a class="deleteEventHebdo" id="deleteEvent" title="Supprimer l'événement" onclick="deleteEventRapid({$oEventUser->evenement_id}, 'cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}');" style="cursor:pointer;">
													<img alt="couper" src="{$j_basepath}design/front/images/design/pictos/delete.png" width="16px" height="16px">
												</a>
												{if isset($oEventUser->etat_iEvenementId) && $oEventUser->etat_iEvenementId == $oEventUser->evenement_id}
													{if $oEventUser->etat_iTypeEtatId == $cours_produit}
														<img alt="{$oEventUser->typeetat_zLibelle}" title="{$oEventUser->typeetat_zLibelle}" src="{$j_basepath}design/front/images/design/pictos/produit.png">
													{/if}
													{if $oEventUser->etat_iTypeEtatId == $cours_annule}
														<img alt="{$oEventUser->typeetat_zLibelle}" title="{$oEventUser->typeetat_zLibelle}" src="{$j_basepath}design/front/images/design/pictos/annule.png">
													{/if}
													{if $oEventUser->etat_iTypeEtatId == $cours_deplace}
														<img alt="{$oEventUser->typeetat_zLibelle}" title="{$oEventUser->typeetat_zLibelle}" src="{$j_basepath}design/front/images/design/pictos/deplace.png">
													{/if}
												{/if}
												<li  style="padding-bottom:2px;padding-top:2px;border-bottom: 5px solid {$oEventUser->typeevenements_zCouleur};" class="conge contentLi">
													<a class="project cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" title="{if isset($oEventUser->evenement_iStagiaire) && $oEventUser->evenement_iStagiaire > 0 && isset($oEventUser->client_id) && $oEventUser->client_id > 0}{$oEventUser->client_zPrenom}&nbsp;{$oEventUser->client_zNom} - {$oEventUser->evenement_zDescription}{else}{$oEventUser->typeevenements_zLibelle} - {$oEventUser->evenement_zDescription}{/if} - {$oEventUser->utilisateur_zNom} {$oEventUser->utilisateur_zPrenom}" href="#tooltip" iEventId="{$oEventUser->evenement_id}" dateFr="{$oEventUser->evenement_date_fr}" urlchangeetat1="{jurl 'evenement~FoEvenement:changeEtat', array('iEvenementId'=>$oEventUser->evenement_id, 'typeetat'=>1), false}" urlchangeetat2="{jurl 'evenement~FoEvenement:changeEtat', array('iEvenementId'=>$oEventUser->evenement_id, 'typeetat'=>2), false}" urlchangeetat3="{jurl 'evenement~FoEvenement:changeEtat', array('iEvenementId'=>$oEventUser->evenement_id, 'typeetat'=>3), false}" typeetat="{$oEventUser->etat_iTypeEtatId}" urlDel="{jurl 'evenement~FoEvenement:deleteEvent', array('iEvenementId'=>$oEventUser->evenement_id), false}" url="{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>$oEventUser->evenement_id,  'zDate' => $oDateListe->zDate,'iTime' => $oEventUser->evenement_heure_fr), false}" id="eventDetail" value="{$oEventUser->evenement_id}" titre="{if isset($oEventUser->evenement_zLibelle) && $oEventUser->evenement_zLibelle != ''}{$oEventUser->evenement_zLibelle}{else}{$oEventUser->typeevenements_zLibelle}{/if}" typesid="{$oEventUser->typeevenements_id}" types="{$oEventUser->typeevenements_zLibelle}" colorbg="{$oEventUser->typeevenements_zCouleur}" dure="{$oEventUser->evenement_iDuree}" nom="{$oEventUser->client_zNom}" prenom="{$oEventUser->client_zPrenom}" blog="{$oEventUser->oWpPost->guid}" posttitle="{$oEventUser->oWpPost->post_title}" poststatus="{$oEventUser->oWpPost->post_status}" posttype="{$oEventUser->oWpPost->post_type}" mail="{$oEventUser->client_zMail}" tel="{$oEventUser->client_zTel}" telDuJour="{$oEventUser->evenement_zContactTel}" date="{$oEventUser->evenement_date_fr}" heure="{$oEventUser->evenement_heure_fr}" createur="{$oEventUser->utilisateur_zNom} {$oEventUser->utilisateur_zPrenom}" description="{$oEventUser->evenement_zDescription|escape:'nl2br'}" societe="{$oEventUser->societe_zNom}" {if $oEventUser->evenement_iDureeTypeId == 1}typeDuree="Heure(s)" {else}typeDuree="Minute(s)"{/if} style="text-decoration:none;">
														{if isset($oEventUser->evenement_iStagiaire) && $oEventUser->evenement_iStagiaire > 0 && isset($oEventUser->client_id) && $oEventUser->client_id > 0}
															{$oEventUser->client_zPrenom}&nbsp;{$oEventUser->client_zNom}{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR && isset($iUtilisateurId1) && $iUtilisateurId1 == 0}<span style="font-size:10px;color:#6D6149;"><br/>{$oEventUser->utilisateur_zNom} {$oEventUser->utilisateur_zPrenom}</span>{/if}<br/>
														{else}
															{$oEventUser->typeevenements_zLibelle}{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR && isset($iUtilisateurId1) && $iUtilisateurId1 == 0}<span style="font-size:10px;color:#6D6149;"><br/>{$oEventUser->utilisateur_zNom} {$oEventUser->utilisateur_zPrenom}</span>{/if}<br/>
														{/if}
													</a>
													<a class="eventDesc" href="#tooltip" eventId="{$oEventUser->evenement_id}" desc="{if isset($oEventUser->evenement_zDescription) && $oEventUser->evenement_zDescription !=''}{$oEventUser->evenement_zDescription}{else}Aucune description{/if}" cellId="cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" style="font-weight:normal;font-size:10px;color:#6D6149;padding-top:10px;text-decoration:none;" onClick="javascript:showModifComment();">{if isset($oEventUser->evenement_zDescription) && $oEventUser->evenement_zDescription !=''}{$oEventUser->evenement_zDescription|truncate:150:"[...]":true|escape:'nl2br'}{else}Aucune description{/if}</a>
												</li>
												{assign $iCpt1=2}
											</ul>
										{else}
											{if $iCpt==1}
												<ul>
													<li style="border-bottom:none;padding:0 0 0 0; height:3px;" class="contentLi">&nbsp;</li>
												</ul>
											{/if}
										{/if}
										{assign $iCpt=2}
									{/foreach}
								{/if}
							</div>
						</td>
						{/foreach}
					</tr>
				{/foreach}
				<!-- Line -->
			</tbody>
		</table> 
		<div class="footertab">
			<table cellspacing="0" cellpadding="0" id="planinfoot">
				<tbody>
					<tr>
						{assign $i=1}
						{foreach $tDateListe as $oDateListe}
							<th scope="col{$i}" style="border-right:none;">
								{if $i==1}Lun {/if}
								{if $i==2}Mar {/if}
								{if $i==3}Mer {/if}
								{if $i==4}Jeu {/if}
								{if $i==5}Ven {/if}
								{if $i==6}Sam {/if}
								{if $i==7}Dim {/if}
								{$oDateListe|date_format:"%d/%m/%Y"}
							</th>
						{assign $i++}
						{/foreach}
					</tr>
				</tbody>
			</table>
		</div>  
		<div class="legendeplan clear">
			{$oZoneLegend}
		</div> 
	</div>
</div>