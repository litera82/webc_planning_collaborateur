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
				window.location.href = $("#urlRechercheParCritere").val() + '&iAffichage=3&iTypeEvenementId='+  $(this).val() + '&iUtilisateurId1=' + $('#employes').val();
			}
		);
		$('#employes').change(
			function (){
				window.location.href = $("#urlRechercheParCritere").val() + '&iAffichage=3&iTypeEvenementId='+  $('#domaines').val() + '&iUtilisateurId1=' + $(this).val();
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
</script>
{/literal}
<div class="planheader">
	<div class="inner">
		<div class="form clear">
		<input type="hidden" name="urlRechercheParCritere" id="urlRechercheParCritere" value="{jurl 'jelix_calendar~FoCalendar:index'}" />
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
			<a title="left" href="{$previous_link}"><img alt="left" src="{$j_basepath}design/front/images/design/bt-planning-left.png"></a>
			<span class="date">{$monthTitre} {$year}</span>
			<a title="right" href="{$next_link}"><img alt="right" src="{$j_basepath}design/front/images/design/bt-planning-right.png"></a>
		</div>
	</div>
</div>
<div class="plancontent">
	<div class="headertab">
		<table cellspacing="0" cellpadding="0" id="planinghead">
			<tbody>
				<tr>
					{assign $i=1}
					{foreach $tJourListe as $oJourListe}
						<th scope="col{$i}">{$oJourListe}</th>
					{assign $i++}
					{/foreach}
				</tr>
			</tbody>
		</table>
	</div>
	<div class="divplaning">
		<table cellspacing="0" cellpadding="0" id="planning-content">
	<tbody>
		<!-- Line -->
	{assign $i=0}
	{foreach $weeks as $week}
		<tr class="busy25">
		{foreach $week as $d}
        {assign $day_link = $d}
		{if $i < $offset_count}
				<td style="background:none repeat scroll 0 0 #CCCCCC">
		{/if}	
		{if $i >= $offset_count && $i < ($num_weeks * 7) - $outset}
				<td>
		{elseif $outset > 0}
			{if $i >= ($num_weeks * 7) - $outset}
				<td style="background:none repeat scroll 0 0 #CCCCCC">
			{/if}	
		{/if}			
					<div class="clear" style="width:118px;">
						<h1>{$day_link}</h1>
						{if $i >= $offset_count && $i < ($num_weeks * 7) - $outset}
							{if $day_link >= 10}
								{assign $dateNewEvent = $year.'-'.$month.'-'.$day_link}
							{else}
								{assign $dateNewEvent = $year.'-'.$month.'-'.'0'.$day_link}
							{/if}
								<a id="ajouterEvent" class="ajouterEventMensuel" onclick="javascript:testEventExist('{$dateNewEvent}', '09:00', '{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>0,  'zDate' => $dateNewEvent ,'iTime' => '09:00', 'iAffichage'=>3), false}');" title="Ajouter un évènement" style="cursor:pointer;"><img alt="Ajouter un événement" src="{$j_basepath}design/front/images/design/plus.png"></a>
						{/if}
						{assign $iCpt=1}
							{if sizeof($toEventUser) > 0}
								{foreach $toEventUser as $oEventUser}
								{if $i >= $offset_count && $i < ($num_weeks * 7) - $outset}
									{if isset($oEventUser->evenement_date_jour) && $day_link == $oEventUser->evenement_date_jour && $month == $oEventUser->evenement_date_mois && $year == $oEventUser->evenement_date_annee}
									<input type="hidden" name="urlDeleteEvent" id="urlDeleteEvent" value="{jurl 'evenement~FoEvenement:deleteEvent', array('date'=>$date, 'iAffichage'=>3)}" />
										<ul class="conge" style="width:120px;">
											<li  style="padding-bottom:0; padding-top:0; border-bottom: 3px solid {$oEventUser->typeevenements_zCouleur};" class="conge">
												<a class="project" href="#tooltip" iEventId="{$oEventUser->evenement_id}" date="{$date}" urlDel="{jurl 'evenement~FoEvenement:deleteEvent', array('iEvenementId'=>$oEventUser->evenement_id), false}" url="{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>$oEventUser->evenement_id,  'zDate' => $oEventUser->evenement_date,'iTime' => $oEventUser->evenement_heure_fr, 'iAffichage'=>3), false}" id="eventDetail" value="{$oEventUser->evenement_id}" titre="{if isset($oEventUser->evenement_zLibelle) && $oEventUser->evenement_zLibelle != ''}{$oEventUser->evenement_zLibelle}{else}{$oEventUser->typeevenements_zLibelle}{/if}" types="{$oEventUser->typeevenements_zLibelle}" dure="{$oEventUser->evenement_iDuree}" nom="{$oEventUser->client_zNom}" prenom="{$oEventUser->client_zPrenom}" mail="{$oEventUser->client_zMail}" tel="{$oEventUser->client_zTel}" blog="{$oEventUser->oWpPost->guid}" posttitle="{$oEventUser->oWpPost->post_title}" poststatus="{$oEventUser->oWpPost->post_status}" posttype="{$oEventUser->oWpPost->post_type}" telDuJour="{$oEventUser->evenement_zContactTel}" dateFr="{$oEventUser->evenement_date_fr}" heure="{$oEventUser->evenement_heure_fr}" societe="{$oEventUser->societe_zNom}" createur="{$oEventUser->utilisateur_zNom} {$oEventUser->utilisateur_zPrenom}" description="{$oEventUser->evenement_zDescription}" {if $oEventUser->evenement_iDureeTypeId == 1}typeDuree="Heure(s)" {else}typeDuree="Minute(s)"{/if} style="font-size:0.8em; font-weight:none;text-decoration:none;">
													{if isset($oEventUser->evenement_iStagiaire) && $oEventUser->evenement_iStagiaire > 0 && isset($oEventUser->client_id) && $oEventUser->client_id > 0}
														{$oEventUser->evenement_heure_fr1} - {$oEventUser->client_zPrenom}&nbsp;{$oEventUser->client_zNom}
													{else}
														{*if isset($oEventUser->evenement_zLibelle) && $oEventUser->evenement_zLibelle != ""}
															{$oEventUser->evenement_heure_fr1} - {$oEventUser->evenement_zLibelle}
														{else}
															{$oEventUser->evenement_heure_fr1} - {$oEventUser->typeevenements_zLibelle}
														{/if*}
															{$oEventUser->evenement_heure_fr1} - {$oEventUser->typeevenements_zLibelle}
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
								{/if}
								{/foreach}
							{/if}

					</div>
				</td>
		{assign $i++}
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
					{foreach $tJourListe as $oJourListe}
						<th scope="col{$i}">{$oJourListe}</th>
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