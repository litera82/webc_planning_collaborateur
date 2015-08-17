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
		$(".deleteEvent").click(
			function (){
				if(confirm ("Etes vous sur de vouloir supprimer cet événement?"))
				{
					document.location.href=$("#deleteEvent").attr('urlDelete');
				}
			}
		);
		$(".addEvent").dblclick (
			function (event){
				$('#addEvendRapid').hide(); 
				var x = 0;
				var y = 0;
				x = event.clientX;
				y = event.clientY + 280;
				$('#addEvendRapid').show(); 
				$('#addEvendRapid').attr('style', 'background-color:#E9E9E9; display: block; top: '+y+'px; left: '+x+'px;border-bottom:0 none;width:400px;');
				afficherMasque(); 
				return false; 
			}
		);
		$(".addEvendRapidFermer").click (
			function (){
				$('#addEvendRapid').hide(); 
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
function afficherMasque()
{
	var w = $('body').width();
	var mh = $('body').height();
	var ih = $(document).height();

	if ( mh < ih ) mh = ih;		

	$('#masque')
		.css({width: w + 'px', height: mh +'px', opacity: 0.5, filter:'Alpha(Opacity=50)'})
		.fadeIn(fadeTime);

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
			<a title="left" href="{jurl 'jelix_calendar~FoCalendar:index', array('date' => $zDateDebSemainePrec), false}"><img alt="left" src="{$j_basepath}design/front/images/design/bt-planning-left.png"></a>
			<span class="date">{$zIntervalsemaine}</span>
			<a title="right" href="{jurl 'jelix_calendar~FoCalendar:index', array('date' => $zDateDebSemaineSuiv), false}"><img alt="right" src="{$j_basepath}design/front/images/design/bt-planning-right.png"></a>
		</div>
	</div>
</div>
<div class="plancontent">
                    <div class="headertab">
                        <table cellspacing="0" cellpadding="0" id="planinghead">
                            <tbody><tr>
									{assign $i=1}
									{foreach $tDateListe as $oDateListe}
										<th scope="col{$i}">
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
                        </tbody></table>
                    </div>
                    <div class="divplaning">
						<table cellspacing="0" cellpadding="0" id="planning-content">
					<tbody>
						<!-- Line -->
						{foreach $tTimeListeDemiHeure as $oTimeListeDemiHeure}
							<tr class="busy25">
								<th class="thrond">
									<a href="#" title="{$oTimeListeDemiHeure} h">{$oTimeListeDemiHeure} h</a>
								</th>
								{foreach $toDateListe as $oDateListe}
								{if $oUtilisateur->utilisateur_plageHoraireId == 2}
									{if $oTimeListeDemiHeure == '07:30' || $oTimeListeDemiHeure == '08:30' || $oTimeListeDemiHeure == '09:30' || $oTimeListeDemiHeure == '10:30' || $oTimeListeDemiHeure == '11:30' || $oTimeListeDemiHeure == '12:30' || $oTimeListeDemiHeure == '13:30' || $oTimeListeDemiHeure == '14:30' || $oTimeListeDemiHeure == '15:30' || $oTimeListeDemiHeure == '16:30' || $oTimeListeDemiHeure == '17:30' || $oTimeListeDemiHeure == '18:30' || $oTimeListeDemiHeure == '19:30' || $oTimeListeDemiHeure == '20:30' || $oTimeListeDemiHeure == '21:30'}
										<td style="border-bottom:1px solid #6C6C6C;">
									{else}
										<td style="border-bottom:1px solid #DCDCDC;">
									{/if}
								{else}
									{if $oUtilisateur->utilisateur_plageHoraireId == 3}
										{if $oTimeListeDemiHeure == '07:40' || $oTimeListeDemiHeure == '08:40' || $oTimeListeDemiHeure == '09:40' || $oTimeListeDemiHeure == '10:40' || $oTimeListeDemiHeure == '11:40' || $oTimeListeDemiHeure == '12:40' || $oTimeListeDemiHeure == '13:40' || $oTimeListeDemiHeure == '14:40' || $oTimeListeDemiHeure == '15:40' || $oTimeListeDemiHeure == '16:40' || $oTimeListeDemiHeure == '17:40' || $oTimeListeDemiHeure == '18:40' || $oTimeListeDemiHeure == '19:40' || $oTimeListeDemiHeure == '20:40' || $oTimeListeDemiHeure == '21:40'}
											<td style="border-bottom:1px solid #6C6C6C;">
										{else}
											<td style="border-bottom:1px solid #DCDCDC;">
										{/if}
									{else}	 
										<td style="border-bottom:1px solid #6C6C6C;">
									{/if}
								{/if}
									<div class="clear">
										<a class="ajouterEventHebdo" id="ajouterEvent" title="Ajouter un évènement" onclick="javascript:testEventExist('{$oDateListe->zDate}', '{$oTimeListeDemiHeure}', '{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>0,  'zDate' => $oDateListe->zDate,'iTime' => $oTimeListeDemiHeure), false}');" style="cursor:pointer;">
											<img alt="ajouter" src="{$j_basepath}design/front/images/design/plus.png">
										</a>
										{assign $iCpt=1}
										{if sizeof($toEventUser) > 0}
											{foreach $toEventUser as $oEventUser}
												{if $oTimeListeDemiHeure == $oEventUser->evenement_heures && $oDateListe->zDate == $oEventUser->evenement_date}
												<input type="hidden" name="urlDeleteEvent" id="urlDeleteEvent" value="{jurl 'evenement~FoEvenement:deleteEvent', array('date'=>$date)}" />
													<ul class="conge">
														<li  style="border-bottom: 5px solid {$oEventUser->typeevenements_zCouleur};" class="conge">
															<a class="project" href="#tooltip" iEventId="{$oEventUser->evenement_id}" dateFr="{$oEventUser->evenement_date_fr}" urlDel="{jurl 'evenement~FoEvenement:deleteEvent', array('iEvenementId'=>$oEventUser->evenement_id), false}" url="{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>$oEventUser->evenement_id,  'zDate' => $oDateListe->zDate,'iTime' => $oEventUser->evenement_heure_fr), false}" id="eventDetail" value="{$oEventUser->evenement_id}" titre="{if isset($oEventUser->evenement_zLibelle) && $oEventUser->evenement_zLibelle != ''}{$oEventUser->evenement_zLibelle}{else}{$oEventUser->typeevenements_zLibelle}{/if}" types="{$oEventUser->typeevenements_zLibelle}" dure="{$oEventUser->evenement_iDuree}" nom="{$oEventUser->client_zNom}" prenom="{$oEventUser->client_zPrenom}" mail="{$oEventUser->client_zMail}" tel="{$oEventUser->client_zTel}" telDuJour="{$oEventUser->evenement_zContactTel}" date="{$oEventUser->evenement_date_fr}" heure="{$oEventUser->evenement_heure_fr}" createur="{$oEventUser->utilisateur_zNom} {$oEventUser->utilisateur_zPrenom}" description="{$oEventUser->evenement_zDescription}" societe="{$oEventUser->societe_zNom}" {if $oEventUser->evenement_iDureeTypeId == 1}typeDuree="Heure(s)" {else}typeDuree="Minute(s)"{/if} style="text-decoration:none;">
																{if isset($oEventUser->evenement_iStagiaire) && $oEventUser->evenement_iStagiaire > 0 && isset($oEventUser->client_id) && $oEventUser->client_id > 0}
																	{$oEventUser->client_zPrenom}&nbsp;{$oEventUser->client_zNom}
																{else}
																	{if isset($oEventUser->evenement_zLibelle) && $oEventUser->evenement_zLibelle != ""}
																		{$oEventUser->evenement_zLibelle}
																	{else}
																		{$oEventUser->typeevenements_zLibelle}
																	{/if}
																{/if}
															</a>
														</li>
													</ul>
												{else}
													{if $iCpt==1}
														<a  style="cursor:pointer;" zDate="{$oDateListe->zDate}" iTime="{$oTimeListeDemiHeure}" class="addEvent">
														<ul style="height:20px;width:120px;"><li style="border-bottom:none;padding:0 0 0 0; height:3px;">&nbsp;</li></ul>
														</a>
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
                            <tbody><tr>
									{assign $i=1}
									{foreach $tDateListe as $oDateListe}
										<th scope="col{$i}">
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
                        </tbody></table>
                    	</div>  
                        <div class="legendeplan clear">
							{$oZoneLegend}
                        </div> 
                	</div>
                </div>
	<div class="pop-up" id="addEvendRapid" style="background-color:#E9E9E9; display: block; top: 149px; left: 707.5px; border-bottom:0 none;">
		<h2>Création d'un événement</h2>
        <a class="fermer addEvendRapidFermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
		<div class="inner clear">
			<form id="edit_event" url="{jurl 'evenement~FoEvenement:add', array(), false}" >
				<p class="clear">
					<label style="width:150px;">Type d'événement *</label>
					<input class="text" type="text" name="societe_zNom" id="societe_zNom" value=""/>
					<input class="text" type="hidden" name="societe_iStatut" id="societe_iStatut" value="1" />
				</p>
				<p class="clear">
					<label style="width:150px;">Stagiaire </label>
					<input class="text" type="text" name="societe_zNom" id="societe_zNom" value=""/>
					<input class="text" type="hidden" name="societe_iStatut" id="societe_iStatut" value="1" />
				</p>
				<div class="input">
					<a href="#"><input type="button" value="Annuler" class="boutonform fermer" /></a>
					<input type="button" value="Créer" class="boutonform saveSociete" />
				</div>
			</form>
		</div>
	</div>
<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>