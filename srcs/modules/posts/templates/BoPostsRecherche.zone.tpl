	<form name="recetteRechercheBo" id="recetteRechercheBo" method="post" action="{jurl 'posts~posts:index'}" onsubmit="return tmt_validateForm(this);"  tmt:validate="true" tmt:callback="displayError">
		<table cellspacing="0" class="expanded"  id="table_panneau">
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Titre :</label>
						<span class="champ">
							<input type="text" id="post_title" name="post_title" {if isset ($oCritere->post_title) && $oCritere->post_title != ""}value="{$oCritere->post_title}" {else} value="" {/if}/>
						</span>
					</p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Statut :</label>
						<span class="champ">
							<input type="text" id="post_status" name="post_status" {if isset ($oCritere->post_status) && $oCritere->post_status != ""}value="{$oCritere->post_status}" {else} value="" {/if}/>
						</span>
					</p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Type :</label>
						<span class="champ">
							<input type="text" id="post_type" name="post_type" {if isset ($oCritere->post_type) && $oCritere->post_type != ""}value="{$oCritere->post_type}" {else} value="" {/if}/>
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