<script type="text/javascript">
{literal}
$(function(){ 

	$('#genereProjet').click (
		function (){
			if($('#genereProjet').is(':checked')){
				$('#post_clientId').attr({'disabled':'disabled'});
				$('#post_clientId').val(0);
			}else{
				$('#post_clientId').removeAttr('disabled');
			}
		}
	);
});
{/literal}
</script>



<h1 class="noBg">Gestion des posts</h1>
<h2>{if $bEdit}Edition  : {else}Nouveau {/if} {if $bEdit}{$oPosts->post_title}{/if}</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'posts~posts:save', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
    <input type="hidden" name="id" value="{if $bEdit}{$oPosts->ID}{else}0{/if}" />
	<p class="clearfix">
        <label>Titre *:</label>
        <span class="champ"><input type="text" name="post_title" id="post_title" value="{if $bEdit}{$oPosts->post_title}{/if}" tmt:message="Veuillez remplir le champ titre<br />" tmt:required="true"/></span>
    </p>
	<p class="clearfix">
        <label>Date de création :</label>
        <span class="champ">{if $bEdit}{$oPosts->post_date|date_format:"%d/%m/%Y"}{else}&nbsp;{$today|date_format:"%d/%m/%Y"}{/if}</span>
    </p>
    <p class="clearfix">
        <label>Projet :</label>
        <span class="champ">
		{if $bEdit && isset($oPosts->client_id) && $oPosts->client_id != NULL}
			{$oPosts->client_zNom}&nbsp;{$oPosts->client_zPrenom}
		{else}
			<select name="post_clientId" id="post_clientId" style="width:200px;">
				<option value="0">-----------Séléctionner-----------</option>
				{foreach $toListProjetDispo as $oListProjetDispo}
					<option value="{$oListProjetDispo->client_id}">{$oListProjetDispo->client_zNom}&nbsp;{$oListProjetDispo->client_zPrenom}</option>
				{/foreach}
			</select>
			&nbsp;<input type="checkbox" name="genereProjet" id="genereProjet" style="width:10%;"/> Générer le projet correspondant
		{/if}
		</span>
    </p>
	<p class="clearfix">
        <label>Statut :</label>
        <span class="champ"><input type="text" name="post_status" id="post_status" value="{if $bEdit}{$oPosts->post_status}{/if}" /></span>
    </p>
	<p class="clearfix">
        <label>Nom :</label>
        <span class="champ"><input type="text" name="post_name" id="client_zMail" value="{if $bEdit}{$oPosts->post_name}{/if}" /></span>
    </p>
	<p class="clearfix">
        <label>Url:</label>
        <span class="champ"><input type="text" name="guid" id="guid" value="{if $bEdit}{$oPosts->guid}{/if}"/></span>
    </p>
	<p class="clearfix">
        <label>Type :</label>
        <span class="champ"><input type="text" name="post_type" id="post_type" value="{if $bEdit}{$oPosts->post_type}{/if}"/></span>
    </p>
    <p class="clearfix">
        <label>Content :</label>
        <span class="champ"><textarea name="post_content" id="post_content" style="height:200px;">{if $bEdit}{$oPosts->post_content}{/if}</textarea></span>
    </p>

    <p class="line_bottom">&nbsp;</p>
    <p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Enregistrer" />&nbsp;
        <input type="button" class="bouton" name="annuler" value="Annuler" onclick="location.href='{jurl 'posts~posts:index', array(), false}'"/>
    </p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>