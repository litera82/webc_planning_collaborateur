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
		$('#domaines').change(
			function (){
				window.location.href = $("#urlRechercheParCritere").val() + '&date='+$("#currentDate").val()+'&iAffichage=2&iTypeEvenementId='+  $(this).val() + '&iUtilisateurId1=' + $('#employes').val();
			}
		);
		$('#employes').change(
			function (){
				window.location.href = $("#urlRechercheParCritere").val() + '&date='+$("#currentDate").val()+'&iAffichage=2&iTypeEvenementId='+  $('#domaines').val() + '&iUtilisateurId1=' + $(this).val();
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
	}); 
	function testEventExist(zDate, iTime, zUrl, iTypeEvent){
		if (iTypeEvent == 13){
			alert("Impossible de créer un événement sur une plage horaire Disponible")
		}else
		{
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
	}
</script>
{/literal}
<div class="planheader">
	<div class="inner">
		<div class="form clear">
		<input type="hidden" name="urlRechercheParCritere" id="urlRechercheParCritere" value="{jurl 'jelix_calendar~FoCalendar:index'}" />
		<input type="hidden" name="currentDate" id="currentDate" value="{$zCurrentdate}" />
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
		<!--input type="submit" id="btJournee2" class="btplan" value="Journée"-->
		<input type="submit" id="btSemaine2" class="btplan active" value="Semaine">
		<input type="submit" id="btMois2" class="btplan" value="Mois">
		</div>
		<div class="weekdate">
			<a title="left" href="{jurl 'jelix_calendar~FoCalendar:index', array('date' => $tzDateSuivPrec->jourPrec, 'iAffichage'=>2), false}"><img alt="left" src="{$j_basepath}design/front/images/design/bt-planning-left.png"></a>
			<span class="date">{$zDatePlanning}</span>
			<a title="right" href="{jurl 'jelix_calendar~FoCalendar:index', array('date' => $tzDateSuivPrec->jourSuiv, 'iAffichage'=>2), false}"><img alt="right" src="{$j_basepath}design/front/images/design/bt-planning-right.png"></a>
		</div>
	</div>
</div>
<div class="plancontent">
		<div class="headertab">
			<table cellspacing="0" cellpadding="0" id="planinghead">
				<tbody><tr>
					{assign $i=1}
					{foreach $tTimeListe as $oTimeListe}
						<th scope="col{$i}">{$oTimeListe}:00</th>
					{assign $i++}
					{/foreach}
				</tr>
			</tbody></table>
		</div>
		<div class="divplaning">
			<table cellspacing="0" cellpadding="0" id="planning-content">
				<tbody>
				<!-- Line -->
				{if sizeof($toEventUser)>0}
				{foreach $toEventUser as $key => $toTmpEventUser}
					<tr class="busy25">
						<th class="thrond">
							{if sizeof($toEventUser) > 0}
								<a href="#" title="{$key}">
									{$key}
								</a>
							{/if}
						</th>
					{foreach $tTimeListe as $oTimeListe}
						<td>
							<div class="clear">
								{assign $zTimeListe = $oTimeListe.':00'}
								{assign $iCpt=1}
								{foreach $toTmpEventUser as $oEventUser}
								{if $oTimeListe == $oEventUser->evenement_heure}
								<a id="ajouterEvent" class="ajouterEventJournee" onclick="javascript:testEventExist('{$zCurrentdate}', '{$zTimeListe}', '{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>0, 'zDate' => $zCurrentdate,'iTime' => $zTimeListe, 'iAffichage'=>2), false}', {$oEventUser->typeevenements_id});" title="Ajouter un évènement" style="cursor:pointer;">
									<img alt="ajouter" src="{$j_basepath}design/front/images/design/plus.png">
								</a>

									<input type="hidden" name="urlDeleteEvent" id="urlDeleteEvent" value="{jurl 'evenement~FoEvenement:deleteEvent', array('iAffichage'=>2, 'date'=>$date)}" />
									<ul class="conge">
										<li  style="border-bottom: 3px solid {$oEventUser->typeevenements_zCouleur};" class="conge">
											<a class="project" onclick="javascript:modifEvent({$oEventUser->evenement_id})" href="#tooltip" iEventId="{$oEventUser->evenement_id}" dateFr="{$oEventUser->evenement_date_fr}" url="{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>$oEventUser->evenement_id, 'zDate' => $zCurrentdate,'iTime' => $oEventUser->evenement_heure_fr, 'iAffichage'=>2), false}" id="eventDetail" value="{$oEventUser->evenement_id}" titre="{if isset($oEventUser->evenement_zLibelle) && $oEventUser->evenement_zLibelle != ''}{$oEventUser->evenement_zLibelle}{else}{$oEventUser->typeevenements_zLibelle}{/if}" types="{$oEventUser->typeevenements_zLibelle}" dure="{$oEventUser->evenement_iDuree}" nom="{$oEventUser->client_zNom}" prenom="{$oEventUser->client_zPrenom}" mail="{$oEventUser->client_zMail}" tel="{$oEventUser->client_zTel}" telDuJour="{$oEventUser->evenement_zContactTel}" date="{$oEventUser->evenement_date_fr}" heure="{$oEventUser->evenement_heure_fr}" societe="{$oEventUser->societe_zNom}" createur="{$oEventUser->utilisateur_zNom} {$oEventUser->utilisateur_zPrenom}" blog="{$oEventUser->oWpPost->guid}" posttitle="{$oEventUser->oWpPost->post_title}" poststatus="{$oEventUser->oWpPost->post_status}" posttype="{$oEventUser->oWpPost->post_type}" description="{$oEventUser->evenement_zDescription}" {if $oEventUser->evenement_iDureeTypeId == 1}typeDuree="Heure(s)" {else}typeDuree="Minute(s)"{/if} style="font-size:0.8em; font-weight:none;text-decoration:none;">
												{if isset($oEventUser->evenement_iStagiaire) && $oEventUser->evenement_iStagiaire > 0 && isset($oEventUser->client_id) && $oEventUser->client_id > 0}
													{$oEventUser->client_zNom|truncate:'5'}
												{else}
													{*if isset($oEventUser->evenement_zLibelle) && $oEventUser->evenement_zLibelle != ""}
														{$oEventUser->evenement_zLibelle|truncate:'5'}
													{else}
														{$oEventUser->typeevenements_zLibelle|truncate:'5'}
													{/if*}
														{$oEventUser->typeevenements_zLibelle|truncate:'5'}
												{/if}
											</a>
										</li>
									</ul>
									{else}
										{if $iCpt==1}
											<ul><li style="border-bottom:none;padding:0 0 0 0; height:3px;">&nbsp;</li></ul>
										{/if}
									{/if}
									{assign $iCpt=2}
								{/foreach}
							</div>
						</td>
					{/foreach}
					</tr>
				{/foreach}
				{/if}
				<!-- Line -->
				</tbody>
			</table> 
			<div class="footertab">
			<table cellspacing="0" cellpadding="0" id="planinfoot">
				<tbody><tr>
					{assign $i=1}
					{foreach $tTimeListe as $oTimeListe}
						<th scope="col{$i}">{$oTimeListe}:00</th>
					{assign $i++}
					{/foreach}
				</tr>
			</tbody></table>
			</div>  
			<div class="legendeplan clear">
				{$oZoneLegend}
			</div> 
		</div>
	</div>
