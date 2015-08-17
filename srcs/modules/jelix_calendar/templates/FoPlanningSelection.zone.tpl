{literal}
<script type="text/javascript">
$(function() {
	
});
</script>
{/literal}

<input type="hidden" name="afficheBlocSelection" id="afficheBlocSelection" value="{$afficheBlocSelection}" class="afficheBlocSelection" />
<div class="planning-selection">
	<ul class="titleselection">
		<li class="choicetitle choice {if $afficheBlocSelection==0}choicehide{/if}">Profils</li>
		<li class="choicetitle choice {if $afficheBlocSelection==0}choicehide{/if}">Groupes de prof</li>
		<li class="choicetitle choice {if $afficheBlocSelection==0}choicehide{/if}">Collaborateurs</li>
		<li class="calendar choice  {if $afficheBlocSelection==0}choicehide{/if}">Calendriers</li>
		<li class="close"><a href="javascript:;" title="fermer" class="close"><img src="{$j_basepath}design/front/images/design/picto_close.JPG" alt="fermer"></a></li>
	</ul>
	<div class="contentselect clear" {if $afficheBlocSelection==0}style="display:none;"{/if}>
		{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}
		<div class="blochoice">
			<ul>
			{foreach $toProfils as $oProfils}
				{if $oProfils->type_statut != 0}
				<li {if $oUtilisateur->utilisateur_iTypeId == $oProfils->type_id}class="active"{/if}><a href="#">{$oProfils->type_zLibelle}</a></li>
				{/if}
			{/foreach}
			</ul>
		</div>
		{/if}
		{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}
		<div class="blochoice">
			<ul>
				<li {if $iGroupeId == 0}class="active"{/if}><a href="{jurl 'jelix_calendar~FoCalendar:index', array('iAffichage'=>$iAffichage), false}">Tous</a></li>
			{foreach $toGroupe as $oGroupe}
				<li {if $oGroupe->groupe_id == $iGroupeId}class="active"{/if}><a title="{$oGroupe->groupe_libelle}" href="{jurl 'jelix_calendar~FoCalendar:index', array('iAffichage'=>$iAffichage, 'iGroupeId'=>$oGroupe->groupe_id, 'date'=>$date), false}">{$oGroupe->groupe_libelle}</a></li>
			{/foreach}
			</ul>
		</div>
		{/if}
		{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}
		<div id="blocressource" class="blochoice" style="overflow-y:scroll">
			<ul>
				<li {if isset($iUtilisateurId1) && $iUtilisateurId1 == 0}class="active"{/if}><a href="{jurl 'jelix_calendar~FoCalendar:index', array('iAffichage'=>$iAffichage, 'iUtilisateurId1'=>0, 'iGroupeId'=>$iGroupeId, 'date'=>$date), false}">Tous</a></li>
			{foreach $toRessources as $oRessources}
				<li {if $iUtilisateurId1 == $oRessources->utilisateur_id}class="active"{/if} ><a href="{jurl 'jelix_calendar~FoCalendar:index', array('iAffichage'=>$iAffichage, 'iUtilisateurId1'=>$oRessources->utilisateur_id, 'iGroupeId'=>$oRessources->groupe_id, 'date'=>$date), false}" title="{$oRessources->utilisateur_zNom|escape:'ucfirst'} {$oRessources->utilisateur_zPrenom|escape:'ucfirst'}">{$oRessources->utilisateur_zPrenom|escape:'ucfirst'}</a></li>
			{/foreach}
			</ul>
		</div>
		{/if}
		<div class="bloccalendar" {if $oUtilisateur->utilisateur_iTypeId != TYPE_UTILISATEUR_ADLINISTRATEUR}style="width:959px;"{/if}>
			<a class="btleft" title="Left" href="{jurl 'jelix_calendar~FoCalendar:index', array('date'=>$zPrevDate, 'iAffichage'=>$iAffichage, 'iGroupeId'=>$iGroupeId), false}"><img alt="left" src="{$j_basepath}design/front/images/design/bt-left-calendar.png"></a>
			<div class="innercalendar clear">
				<div class="titletable clear">
					<h3>{$previous_month_fr}</h3>
					<h3>{$month_fr}</h3>
					<h3>{$next_month_fr}</h3>
				</div>
				{$calendar->output_calendar($previous_year, $previous_month)}
				{$calendar->output_calendar()}
				{$calendar->output_calendar($next_year, $next_month)}
			</div>
			<a class="btright" title="Right" href="{jurl 'jelix_calendar~FoCalendar:index', array('date'=>$zNextDate, 'iAffichage'=>$iAffichage, 'iGroupeId'=>$iGroupeId), false}"><img alt="Right" src="{$j_basepath}design/front/images/design/bt-right-calendar.png"></a>
		</div>
	</div>
</div>