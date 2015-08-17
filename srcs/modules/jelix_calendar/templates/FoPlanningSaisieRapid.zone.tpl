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

	function activetd(affichage, cellId){
		var contentUl = $('td#'+cellId).find('.divajouterEvent').find('ul.conge') ;

		if (affichage == 1){
			$('td#'+cellId).addClass("activetd");
		}else{
			$('td#'+cellId).removeClass("activetd");
		}
		if (contentUl.length != 0){
			$('td#'+cellId).find('.divajouterEvent').find('.ajouterEventHebdo').hide();
			$('td#'+cellId).find('.divajouterEvent').find('.actionEventHebdo').hide();
			$('td#'+cellId).find('.divajouterEvent').find('.pasteEventHebdo').hide();
		}else{
			if ($('td#'+cellId).hasClass('activetd')){
				$('td#'+cellId).find('.divajouterEvent').find('.ajouterEventHebdo').show();
				$('td#'+cellId).find('.divajouterEvent').find('.actionEventHebdo').show();
				$('td#'+cellId).find('.divajouterEvent').find('.pasteEventHebdo').show();
			}else{
				$('td#'+cellId).find('.divajouterEvent').find('.ajouterEventHebdo').hide();
				$('td#'+cellId).find('.divajouterEvent').find('.actionEventHebdo').hide();
				$('td#'+cellId).find('.divajouterEvent').find('.pasteEventHebdo').hide();
			}
		}
	}
</script>
{/literal}
		<table cellspacing="0" cellpadding="0" id="planning-content">
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
								<a class="actionEventHebdo action_cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" id="actionEvent" title="Ajouter un évènement rapidement" typeEvent="" iUtilisateurId="{$userId}" href="#" style="cursor:pointer;" zDate="{$oDateListe->zDate|date_format:'%d/%m/%Y'}" iTime="{$oTimeListeDemiHeure->time1}" onclick="javascript:showSaisieRapidEvent({$userId}, 'action_cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}'); return false;">
									<img alt="ajouter" src="{$j_basepath}design/front/images/design/action.png">
								</a>
								{if isset($iEventToCopy) && $iEventToCopy > 0}
									<a class="pasteEventHebdo" id="pasteEvent" title="Coller l'évènement" style="cursor:pointer;" onclick="collerEvent({$iEventToCopy}, '{$oDateListe->zDate}', '{$oTimeListeDemiHeure->time1}')" >
										<img alt="Coller l'évènement" src="{$j_basepath}design/front/images/design/pictos/coller.png">
									</a>
								{/if}

								{assign $iCpt=1}
								{if sizeof($toEventUser) > 0}
									{foreach $toEventUser as $oEventUser}
										{if $oTimeListeDemiHeure->time1 == $oEventUser->evenement_heures && $oDateListe->zDate == $oEventUser->evenement_date}
											<ul class="conge" style="width:146px">
												<a class="commentEventHebdo comment_cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" id="commentEvent" title="Description de l'événement" href="#tooltip" colorbg="{$oEventUser->typeevenements_zCouleur}" description="{$oEventUser->evenement_zDescription}" style="cursor:pointer;" onclick="showCommentEvent('comment_cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}');">
													<img width="16px" height="16px" alt="couper" src="{$j_basepath}design/front/images/design/pictos/comment1.jpg">
												</a>
												<a class="copyEventHebdo" id="copyEvent" title="Copier l'événement" onclick="copierEvent({$oEventUser->evenement_id});" style="cursor:pointer;">
													<img alt="copier" src="{$j_basepath}design/front/images/design/pictos/copier.gif">
												</a>
												<a class="cutEventHebdo" id="cutEvent" title="Couper l'événement" onclick="couperEvent({$oEventUser->evenement_id});" style="cursor:pointer;">
													<img alt="couper" src="{$j_basepath}design/front/images/design/pictos/couper.png" width="16px" height="16px">
												</a>
												<a class="deleteEventHebdo" id="deleteEvent" title="Supprimer l'événement" onclick="deleteEventRapid({$oEventUser->evenement_id});" style="cursor:pointer;">
													<img alt="couper" src="{$j_basepath}design/front/images/design/pictos/delete.png" width="16px" height="16px">
												</a>
												<li  style="border-bottom: 5px solid {$oEventUser->typeevenements_zCouleur};" class="conge">
													<a class="project a_project_cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}" onclick="javascript:getTooltipProject('a_project_cell_{$oDateListe->zDate|replace:'-':'_'}_{$oTimeListeDemiHeure->time1|replace:':':'_'}');" href="#tooltip" iEventId="{$oEventUser->evenement_id}" dateFr="{$oEventUser->evenement_date_fr}" urlDel="{jurl 'evenement~FoEvenement:deleteEvent', array('iEvenementId'=>$oEventUser->evenement_id), false}" url="{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>$oEventUser->evenement_id,  'zDate' => $oDateListe->zDate,'iTime' => $oEventUser->evenement_heure_fr), false}" id="eventDetail" value="{$oEventUser->evenement_id}" titre="{if isset($oEventUser->evenement_zLibelle) && $oEventUser->evenement_zLibelle != ''}{$oEventUser->evenement_zLibelle}{else}{$oEventUser->typeevenements_zLibelle}{/if}" typesid="{$oEventUser->typeevenements_id}" types="{$oEventUser->typeevenements_zLibelle}" colorbg="{$oEventUser->typeevenements_zCouleur}" dure="{$oEventUser->evenement_iDuree}" nom="{$oEventUser->client_zNom}" prenom="{$oEventUser->client_zPrenom}" mail="{$oEventUser->client_zMail}" tel="{$oEventUser->client_zTel}" telDuJour="{$oEventUser->evenement_zContactTel}" date="{$oEventUser->evenement_date_fr}" heure="{$oEventUser->evenement_heure_fr}" createur="{$oEventUser->utilisateur_zNom} {$oEventUser->utilisateur_zPrenom}" description="{$oEventUser->evenement_zDescription}" societe="{$oEventUser->societe_zNom}" {if $oEventUser->evenement_iDureeTypeId == 1}typeDuree="Heure(s)" {else}typeDuree="Minute(s)"{/if} style="text-decoration:none;">
														{if isset($oEventUser->evenement_iStagiaire) && $oEventUser->evenement_iStagiaire > 0 && isset($oEventUser->client_id) && $oEventUser->client_id > 0}
															{$oEventUser->client_zPrenom}&nbsp;{$oEventUser->client_zNom}
														{else}
															{$oEventUser->typeevenements_zLibelle}
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
								{/if}
							</div>
						</td>
						{/foreach}
					</tr>
				{/foreach}
				<!-- Line -->
			</tbody>
		</table> 