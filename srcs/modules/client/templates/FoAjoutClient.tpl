<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear">
				{$oZoneAjoutClient}
			</div>
		</div>
	</div>
</div>
{$footer}
<div class="pop-up" id="periodepop" style="background-color:#E9E9E9; display: block; top: 149px; left: 707.5px;">
		<h2>Création d'une catégorie</h2>
        <a class="fermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
		<div class="inner clear">
			<form id="edit_form_societe" url="{jurl 'client~FoSociete:save', array(), false}" >
						<p class="clear">
							<label>Catégorie *</label>
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
{literal}
<script type="text/javascript">
	$(function() {
		$('.saveSociete').click(
			function (){
				if ($('#societe_zNom').val() == '')
				{
					alert('Veuillez remplir le champ Raison sociale')
				}else{
					$.getJSON(j_basepath + "index.php", {module:"client", action:"FoSociete:saveAjax", societe_zNom:$('#societe_zNom').val(), societe_iStatut:$('#societe_iStatut').val()}, function(datas){
						if(datas){
							var html = "";
							html += '<option value="0">----------------------Séléctionner----------------------</option>';
							for(i=0; i< datas.length; i++){
								html += '<option value="' + datas[i]["societe_id"] +'">&nbsp;' + datas[i]["societe_zNom"] + '</option>';
							}
							$('#client_iSociete').html(html);
							$('#client_iSociete').val(0);

							$('#masque').fadeOut(fadeTime);
							$(lastOpen).fadeOut(fadeTime);
							return false;
						}
					});
				}				
			}
		);
	});
</script>
{/literal}
