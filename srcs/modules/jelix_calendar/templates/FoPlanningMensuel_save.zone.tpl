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
	}); 
</script>
{/literal}
<div class="planheader">
	<div class="inner">
		<div class="form clear">
		<input type="hidden" name="urlRechercheParCritere" id="urlRechercheParCritere" value="{jurl 'jelix_calendar~FoCalendar:index'}" />
		{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}
		<select name="domaines" id="domaines" class="js-style-me">
			<option value="0">All</option>
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
			<a title="left" href="{jurl 'jelix_calendar~FoCalendar:index', array('date' => $zDateDebSemainePrec), false}"><img alt="left" src="{$j_basepath}design/front/images/design/bt-planning-left.png"></a>
			<span class="date">{$zIntervalsemaine}</span>
			<a title="right" href="{jurl 'jelix_calendar~FoCalendar:index', array('date' => $zDateDebSemaineSuiv), false}"><img alt="right" src="{$j_basepath}design/front/images/design/bt-planning-right.png"></a>
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
		<tr class="busy25">
		{foreach $tJourListe as $oJourListe}
				<td>
					<div class="clear">
						<h1>{$zDateFr}</h1>
						<a href="#" title="Ajouter un booking"><img alt="ajouter" src="{$j_basepath}design/front/images/design/plus.png"></a>
						<ul>
							<li class="conge"><a class="project" href="#tooltip">Congé</a></li>
							<li class="conge"><a class="project" href="#tooltip">Congé</a></li>
							<li class="conge"><a class="project" href="#tooltip">Congé</a></li>
						</ul>
					</div>
				</td>
		{/foreach}
		</tr>
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