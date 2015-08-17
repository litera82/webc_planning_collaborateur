	<form name="recetteRechercheBo" id="recetteRechercheBo" method="post" action="{jurl 'client~client:index'}" onsubmit="return tmt_validateForm(this);"  tmt:validate="true" tmt:callback="displayError">
		<table cellspacing="0" class="expanded"  id="table_panneau">
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Nom :</label>
						<span class="champ">
							<input type="text" id="nom" name="nom" {if isset ($oCritere->nom) && $oCritere->nom != ""}value="{$oCritere->nom}" {else} value="" {/if}/>
						</span>
					</p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Statut de publication :</label>
						 <span class="champ">
							<select id="statut" name="statut" style="width:120px;">
									<option value="3" {if isset($oCritere) && $oCritere->statut == 3}selected="selected"{/if}>-------Toutes -------</option>
									<option value="1" {if isset($oCritere) && $oCritere->statut == 1}selected="selected"{/if}>Publier</option>
									<option value="2" {if isset($oCritere) && $oCritere->statut == 2}selected="selected"{/if}>Non publier</option>
									<option value="0" {if isset($oCritere) && $oCritere->statut == 0}selected="selected"{/if}>Desactiver</option>
							</select>
						 </span>
					</p>
				</td>
			</tr>
		</table>
		<br/>
		<p class="frmBoutonr" align="right">
			<a class="bouton submit" href="#">Rechercher</a>
		</p>
		<!--p class="errorMessage" id="errorMessage"></p-->
	</form>