<script type="text/javascript">
{literal}
//Couleur
	bloc = true;
	function palette() { 
		document.write("<TABLE border='0' cellpadding='0' cellspacing='0' ><TR>"); 
		var h=new Array('00','33','66','99','CC','FF'); 
		var col=""; 
		for(var i=0;i<6;i++) { 
			for(var j=0;j<6;j++) { 
				for(var k=0;k<6;k++) { 
					col="#"+h[i]+h[j]+h[k]; 
					document.write("<TD width='15' height='15' bgcolor='"+col+"' onClick=\"hexa('"+col+"')\"></TD>"); 
				} 
			} 
			document.write("</tr>"); 
		} 
		document.write("</TABLE>"); 
	} 
	function hexa(couleur)
	{	
		document.getElementById('typeevenements_zCouleur').value = couleur;
		$('#aprecu_color').attr("style", "height:20px; width:20px; background-color:"+couleur+"; margin-top:5px;margin-left:-1px;");
	}

{/literal}
</script>

<h1 class="noBg">{if $bEdit}Edition {else}Nouveau{/if}</h1>
<h2>{if $bEdit}Edition  : {else}Nouveau {/if} {if $bEdit}{$oTypeEvenement->typeevenements_zLibelle}{/if}</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'typeEvenement~typeEvenement:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
    <input type="hidden" name="typeevenements_id" id="typeevenements_id" value="{if $bEdit}{$oTypeEvenement->typeevenements_id}{else}0{/if}" />
    <p class="clearfix">
        <label>Libellé *:</label>
        <span class="champ"><input type="text" name="typeevenements_zLibelle" id="typeevenements_zLibelle" value="{$oTypeEvenement->typeevenements_zLibelle}" tmt:message="Veuillez remplir le champ libellé<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Couleur *:</label>
        <span class="champ">
			<input type="text" name="typeevenements_zCouleur" id="typeevenements_zCouleur" value="{$oTypeEvenement->typeevenements_zCouleur}" tmt:message="Veuillez remplir le champ couleur<br />" tmt:required="true" readonly="readonly" />
			<p style="height:20px; width:20px; background-color:{$oTypeEvenement->typeevenements_zCouleur}; margin-top:5px;margin-left:-1px;" id="aprecu_color"></p>
			<p style="margin-top:5px;margin-left:-1px;">Cliquez sur la couleur de votre choix.</p>
			<script language="JavaScript"> 
				palette(); 
			</script>
		</span>
    </p>
    <p class="clearfix">
        <label>Durée par défaut *:</label>
		{if $bEdit}
			{if $oTypeEvenement->typeevenements_iDureeTypeId == 1}
				{assign $zDureParDefaut = $oTypeEvenement->typeevenements_iDure . ' heures'}
			{else}
				{assign $zDureParDefaut = $oTypeEvenement->typeevenements_iDure . ' minutes'} 
			{/if}
		{else}
			{assign $zDureParDefaut = '30 minutes'}
		{/if}
        <span class="champ">
			<select name="typeevenements_iDure" id="typeevenements_iDure" tmt:required="true" tmt:invalidindex="0">
				<option value="0">---------Durée---------</option>
				{foreach $toDure as $oDure}
					<option value="{$oDure}" {if isset($zDureParDefaut) && $oDure == $zDureParDefaut}selected='selected'{/if}>{$oDure}</option>
				{/foreach}
			</select>
		</span>
    </p>
    <p class="clearfix">
        <label>Statut :</label>
        <span class="champ">
       	<input type="radio" name="typeevenements_iStatut" id="typeevenements_iStatut" class="radio" value="1" {if $oTypeEvenement->typeevenements_iStatut == STATUT_PUBLIE}checked="checked"{/if} tmt:required="true" tmt:message="Veuillez choisir le  statut"/>&nbsp;PUBLIER&nbsp;<input type="radio" name="typeevenements_iStatut" id="typeevenements_iStatut" class="radio" value="2" {if $oTypeEvenement->typeevenements_iStatut == STATUT_NON_PUBLIE}checked="checked"{/if} />&nbsp;NON PUBLIER&nbsp;<input type="radio" name="typeevenements_iStatut" id="typeevenements_iStatut" class="radio" value="0" {if $oTypeEvenement->typeevenements_iStatut == STATUT_DESACTIVE}checked="checked"{/if} />&nbsp;DESACTIVER
        </span>
    </p>
    <p class="clearfix">
        <label>Projet obligatoire :</label>
        <span class="champ">
		{if $bEdit}
       		<input type="radio" name="typeevenements_iStagiaireActif" id="typeevenements_iStagiaireActif" class="radio" value="1" {if $oTypeEvenement->typeevenements_iStagiaireActif == 1}checked="checked"{/if} />&nbsp;Projet obligatoire&nbsp;<input type="radio" name="typeevenements_iStagiaireActif" id="typeevenements_iStagiaireActif" class="radio" value="2" {if $oTypeEvenement->typeevenements_iStagiaireActif == 2}checked="checked"{/if} />&nbsp;Stagiaire optionnel&nbsp;<input type="radio" name="typeevenements_iStagiaireActif" id="typeevenements_iStagiaireActif" class="radio" value="0" {if $oTypeEvenement->typeevenements_iStagiaireActif == 0}checked="checked"{/if} />&nbsp;Pas de stagiaire&nbsp;
		{else}
			<input type="radio" name="typeevenements_iStagiaireActif" id="typeevenements_iStagiaireActif" class="radio" value="1" />&nbsp;Projet obligatoire&nbsp;<input type="radio" name="typeevenements_iStagiaireActif" id="typeevenements_iStagiaireActif" class="radio" value="2" checked="checked" />&nbsp;Stagiaire optionnel&nbsp;<input type="radio" name="typeevenements_iStagiaireActif" id="typeevenements_iStagiaireActif" class="radio" value="0" />&nbsp;Pas de stagiaire&nbsp;
		{/if}
        </span>
    </p>

	<p class="line_bottom">&nbsp;</p>
    <p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Enregistrer" />&nbsp;
        <input type="button" class="bouton" name="annuler" value="Annuler" onclick="location.href='{jurl 'typeEvenement~typeEvenement:index', array(), false}'"/>
    </p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>