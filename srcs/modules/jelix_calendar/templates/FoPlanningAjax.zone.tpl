	<div class="clear divajouterEvent">
		<a class="ajouterEventHebdo" id="ajouterEvent" title="Ajouter un évènement" onclick="javascript:testEventExist('{$oEvent->evenement_date}', '{$oEvent->time1}', '{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>0,  'zDate' => $oEvent->evenement_date,'iTime' => $oEvent->time1), false}');" style="cursor:pointer;">
			<img alt="ajouter" src="{$j_basepath}design/front/images/design/plus.png">
		</a>
		<a class="actionEventHebdo action_cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}" id="actionEvent" title="Ajouter un évènement rapidement" typeEvent="" iUtilisateurId="{$userId}" href="#" style="cursor:pointer;" zDate="{$oEvent->evenement_date|date_format:'%d/%m/%Y'}" iTime="{$oEvent->time1}" onclick="javascript:showSaisieRapidEvent({$userId}, 'action_cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}'); return false;" cellId="cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}">
			<img alt="ajouter" src="{$j_basepath}design/front/images/design/action.png">
		</a>
		<a class="pasteEventHebdo" id="pasteEvent" title="Coller l'évènement" style="cursor:pointer;" onclick="collerEvent('{$oEvent->evenement_date}', '{$oEvent->time1}','cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}')" >
			<img alt="Coller l'évènement" src="{$j_basepath}design/front/images/design/pictos/coller.png">
		</a>
		<ul class="conge" style="width:146px">
			{*<!--<a class="commentEventHebdo comment_cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}" id="commentEvent" title="Description de l'événement" href="#tooltip" colorbg="{$oEvent->typeevenements_zCouleur}" description="{$oEvent->evenement_zDescription}" style="cursor:pointer;" onclick="showCommentEvent({$oEvent->evenement_id},'comment_cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}');">
				<img width="16px" height="16px" alt="couper" src="{$j_basepath}design/front/images/design/pictos/comment1.jpg">
			</a>-->*}
			<a class="copyEventHebdo" id="copyEvent" title="Copier l'événement" onclick="copierEvent({$oEvent->evenement_id}, 'cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}');" style="cursor:pointer;">
				<img alt="copier" src="{$j_basepath}design/front/images/design/pictos/copier.gif">
			</a>
			<a class="cutEventHebdo" id="cutEvent" title="Couper l'événement" onclick="couperEvent({$oEvent->evenement_id}, 'cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}');" style="cursor:pointer;">
				<img alt="couper" src="{$j_basepath}design/front/images/design/pictos/couper.png" width="16px" height="16px">
			</a>
			<a class="deleteEventHebdo" id="deleteEvent" title="Supprimer l'événement" onclick="deleteEventRapid({$oEvent->evenement_id}, 'cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}');" style="cursor:pointer;">
				<img alt="couper" src="{$j_basepath}design/front/images/design/pictos/delete.png" width="16px" height="16px">
			</a>
			<li  style="border-bottom: 5px solid {$oEvent->typeevenements_zCouleur};" class="conge">
				<a class="project cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}" onclick="javascript:getTooltipProject('cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}');" href="#tooltip" iEventId="{$oEvent->evenement_id}" dateFr="{$oEvent->evenement_date_fr}" urlDel="{jurl 'evenement~FoEvenement:deleteEvent', array('iEvenementId'=>$oEvent->evenement_id), false}" url="{jurl 'evenement~FoEvenement:add', array('iEvenementId'=>$oEvent->evenement_id), false}" id="eventDetail" value="{$oEvent->evenement_id}" titre="{if isset($oEvent->evenement_zLibelle) && $oEvent->evenement_zLibelle != ''}{$oEvent->evenement_zLibelle}{else}{$oEvent->typeevenements_zLibelle}{/if}" typesid="{$oEvent->typeevenements_id}" types="{$oEvent->typeevenements_zLibelle}" colorbg="{$oEvent->typeevenements_zCouleur}" dure="{$oEvent->evenement_iDuree}" nom="{$oEvent->client_zNom}" prenom="{$oEvent->client_zPrenom}" mail="{$oEvent->client_zMail}" tel="{$oEvent->client_zTel}" telDuJour="{$oEvent->evenement_zContactTel}" date="{$oEvent->evenement_date_fr}" heure="{$oEvent->evenement_heure_fr}" createur="{$oEvent->utilisateur_zNom} {$oEvent->utilisateur_zPrenom}" description="{$oEvent->evenement_zDescription|escape:'nl2br'}" societe="{$oEvent->societe_zNom}" {if $oEvent->evenement_iDureeTypeId == 1}typeDuree="Heure(s)" {else}typeDuree="Minute(s)"{/if} style="text-decoration:none;" title="{if isset($oEvent->evenement_iStagiaire) && $oEvent->evenement_iStagiaire > 0 && isset($oEvent->client_id) && $oEvent->client_id > 0}{$oEvent->client_zPrenom}&nbsp;{$oEvent->client_zNom} - {$oEvent->evenement_zDescription}{else}{$oEvent->typeevenements_zLibelle} - {$oEvent->evenement_zDescription}{/if}">
					{if isset($oEvent->evenement_iStagiaire) && $oEvent->evenement_iStagiaire > 0 && isset($oEvent->client_id) && $oEvent->client_id > 0}
						{$oEvent->client_zPrenom}&nbsp;{$oEvent->client_zNom}<br/>
					{else}
						{$oEvent->typeevenements_zLibelle}<br/>
					{/if}
				</a>
				<a class="eventDesc" href="#tooltip" eventId="{$oEvent->evenement_id}" desc="{if isset($oEvent->evenement_zDescription) && $oEvent->evenement_zDescription !=''}{$oEvent->evenement_zDescription}{else}Aucune description{/if}" cellId="cell_{$oEvent->evenement_date|replace:'-':'_'}_{$oEvent->time1|replace:':':'_'}" style="font-weight:normal;font-size:10px;color:#6D6149;padding-top:10px;text-decoration:none;" onClick="javascript:showModifComment();">{if isset($oEvent->evenement_zDescription) && $oEvent->evenement_zDescription !=''}{$oEvent->evenement_zDescription|truncate:150:"[...]":true|escape:'nl2br'}{else}Aucune description{/if}</a>
			</li>
		</ul>
	</div>
