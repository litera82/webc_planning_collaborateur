<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">Gestion des événements</h1>
<h2>{if $bEdit}Edition  : {else}Nouveau {/if} {if $bEdit}{$oEvenement->evenement_zLibelle}{/if}</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'evenement~evenement:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
    <input type="hidden" name="evenement_id" value="{if $bEdit}{$oEvenement->evenement_id}{else}0{/if}" />
    <p class="clearfix">
        <label>Type d'événement *:</label>
        <span class="champ">
			<select name="evenement_iTypeEvenementId" id="evenement_iTypeEvenementId" tmt:message="Veuillez séléctionner le type d'événement<br />" tmt:required="true" tmt:invalidIndex="0">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toTypeEvenement as $oTypeEvenement}
					<option value="{$oTypeEvenement->typeevenements_id}" {if $bEdit}{if $oEvenement->evenement_iTypeEvenementId==$oTypeEvenement->typeevenements_id} selected=selected {/if}{/if}>{$oTypeEvenement->typeevenements_zLibelle}</option>
				{/foreach}
			</select>
		</span>
    </p>
    <p class="clearfix">
        <label>Utilisateur *:</label>
        <span class="champ">
			<select name="evenement_iUtilisateurId" id="evenement_iUtilisateurId" tmt:message="Veuillez séléctionner l'utilisateur<br />" tmt:required="true" tmt:invalidIndex="0">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toUtilisateur as $oUtilisateur}
					<option value="{$oUtilisateur->utilisateur_id}" {if $bEdit}{if $oEvenement->evenement_iUtilisateurId==$oUtilisateur->utilisateur_id} selected=selected {/if}{/if}>{$oUtilisateur->utilisateur_zNom}&nbsp;{$oUtilisateur->utilisateur_zPrenom}</option>
				{/foreach}
			</select>
		</span>
    </p>
	<p class="clearfix">
        <label>Libellé *:</label>
        <span class="champ"><input type="text" name="evenement_zLibelle" id="evenement_zLibelle" value="{$oEvenement->evenement_zLibelle}" tmt:message="Veuillez remplir le champ libellé<br />" tmt:required="true"/></span>
    </p>
    <p class="clearfix">
        <label>Description :</label>
        <span class="champ"><textarea name="evenement_zDescription" id="evenement_zDescription">{$oEvenement->evenement_zDescription}</textarea></span>
    </p>
    <p class="clearfix">
        <label>Projet :</label>
        <span class="champ">
			<select name="evenement_iStagiaire" id="evenement_iStagiaire" >
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toStagiaire as $oStagiaire}
					<option value="{$oStagiaire->client_id}" {if $bEdit}{if $oEvenement->evenement_iStagiaire==$oStagiaire->client_id} selected=selected {/if}{/if}>{$oStagiaire->client_zPrenom} {$oStagiaire->client_zNom}</option>
				{/foreach}
			</select>
		</span>
    </p>

	<p class="clearfix">
        <label>Contact téléphonique :</label>
        <span class="champ"><input type="text" name="evenement_zContactTel" id="evenement_zContactTel" value="{$oEvenement->evenement_zContactTel}" /></span>
    </p>
	<p class="clearfix">
        <label>Date / heure début :</label>
		<span class="champ">
		<input type="text" name="evenement_zDateHeureDebut" id="evenement_zDateHeureDebut" readonly style="width:140px;vertical-align:middle;top:auto; "  maxlength="10" tmt:datepattern="DD/MM/YYYY H:M:00" {if isset ($oEvenement->evenement_zDateHeureDebut)}value="{$oEvenement->evenement_zDateHeureDebut|date_format:"%d/%m/%Y %H:%M:00"}" {else} value=""{/if} />
		{literal}
			<img src="design/back/images/picto_calendar_search.jpg"  name="debut" id="debut" class="imageDate1" style="vertical-align:middle;top:auto"/>
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "evenement_zDateHeureDebut",	// id of the input field
						ifFormat       :    "%d/%m/%Y %H:%M:00",		// format of the input field
						showsTime      :    true,			// will display a time selector
						button         :    "debut",		// trigger for the calendar (button ID)
						singleClick    :    true,			// double-click mode
						step           :    1				// show all years in drop-down boxes (instead of every other year as default)
					});
				</script>                        
		{/literal}
		</span>
	</p>
	<p class="clearfix">
        <label>Durée :</label>
        <span class="champ"><input type="text" name="evenement_iDuree" id="evenement_iDuree" value="{$oEvenement->evenement_iDuree}" /></span>
    </p>
	<input type="hidden" name="evenement_iPriorite" id="evenement_iPriorite" value="1" />
	<p class="clearfix">
        <label>Rappel :</label>
        <span class="champ">
			<select name="evenement_iRappel" id="evenement_iRappel">
				<option value="0">&nbsp;&nbsp;&nbsp;&nbsp;Aucun&nbsp;&nbsp;&nbsp;&nbsp;</option>
				<option value="1"{if $bEdit}{if $oEvenement->evenement_iRappel==1} selected=selected {/if}{/if}>1h</option>
				<option value="2"{if $bEdit}{if $oEvenement->evenement_iRappel==2} selected=selected {/if}{/if}>2h</option>
				<option value="3"{if $bEdit}{if $oEvenement->evenement_iRappel==3} selected=selected {/if}{/if}>3h</option>
				<option value="4"{if $bEdit}{if $oEvenement->evenement_iRappel==4} selected=selected {/if}{/if}>4h</option>
				<option value="5"{if $bEdit}{if $oEvenement->evenement_iRappel==5} selected=selected {/if}{/if}>5h</option>
				<option value="6"{if $bEdit}{if $oEvenement->evenement_iRappel==6} selected=selected {/if}{/if}>6h</option>
				<option value="7"{if $bEdit}{if $oEvenement->evenement_iRappel==7} selected=selected {/if}{/if}>7h</option>
				<option value="8"{if $bEdit}{if $oEvenement->evenement_iRappel==8} selected=selected {/if}{/if}>8h</option>
				<option value="9"{if $bEdit}{if $oEvenement->evenement_iRappel==9} selected=selected {/if}{/if}>9h</option>
				<option value="10"{if $bEdit}{if $oEvenement->evenement_iRappel==10} selected=selected {/if}{/if}>10h</option>
				<option value="11"{if $bEdit}{if $oEvenement->evenement_iRappel==11} selected=selected {/if}{/if}>11h</option>
				<option value="12"{if $bEdit}{if $oEvenement->evenement_iRappel==12} selected=selected {/if}{/if}>12h</option>
				<option value="13"{if $bEdit}{if $oEvenement->evenement_iRappel==13} selected=selected {/if}{/if}>13h</option>
				<option value="14"{if $bEdit}{if $oEvenement->evenement_iRappel==14} selected=selected {/if}{/if}>14h</option>
				<option value="15"{if $bEdit}{if $oEvenement->evenement_iRappel==15} selected=selected {/if}{/if}>15h</option>
				<option value="16"{if $bEdit}{if $oEvenement->evenement_iRappel==16} selected=selected {/if}{/if}>16h</option>
				<option value="17"{if $bEdit}{if $oEvenement->evenement_iRappel==17} selected=selected {/if}{/if}>17h</option>
				<option value="18"{if $bEdit}{if $oEvenement->evenement_iRappel==18} selected=selected {/if}{/if}>18h</option>
				<option value="19"{if $bEdit}{if $oEvenement->evenement_iRappel==19} selected=selected {/if}{/if}>19h</option>
				<option value="20"{if $bEdit}{if $oEvenement->evenement_iRappel==20} selected=selected {/if}{/if}>20h</option>
				<option value="21"{if $bEdit}{if $oEvenement->evenement_iRappel==21} selected=selected {/if}{/if}>21h</option>
				<option value="22"{if $bEdit}{if $oEvenement->evenement_iRappel==22} selected=selected {/if}{/if}>22h</option>
				<option value="23"{if $bEdit}{if $oEvenement->evenement_iRappel==23} selected=selected {/if}{/if}>23h</option>
				<option value="24"{if $bEdit}{if $oEvenement->evenement_iRappel==24} selected=selected {/if}{/if}>24h</option>
			</select>
		</span>
    </p>
    <p class="clearfix">
        <label>Statut *:</label>
        <span class="champ">
       	<input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="1" {if $oEvenement->evenement_iStatut == STATUT_PUBLIE}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  statut"/>&nbsp;PUBLIER&nbsp;<input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="2" {if $oEvenement->evenement_iStatut == STATUT_NON_PUBLIE}checked="checked"{/if} />&nbsp;NON PUBLIER&nbsp;<input type="radio" name="evenement_iStatut" id="evenement_iStatut" class="radio" value="0" {if $oEvenement->evenement_iStatut == STATUT_DESACTIVE}checked="checked"{/if} />&nbsp;DESACTIVER
        </span>
    </p>
    <p class="line_bottom">&nbsp;</p>
    <p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Enregistrer" />&nbsp;
        <input type="button" class="bouton" name="annuler" value="Annuler" onclick="location.href='{jurl 'evenement~evenement:index', array(), false}'"/>
    </p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>