	<form name="recetteRechercheBo" id="recetteRechercheBo" method="post" action="{jurl 'client~client:index'}" onsubmit="return tmt_validateForm(this);"  tmt:validate="true" tmt:callback="displayError">
		<table cellspacing="0" class="expanded"  id="table_panneau">
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Titre :</label>
						<span class="champ">
							<input type="text" id="zTitlePost" name="zTitlePost" {if isset ($oCritere->zTitlePost) && $oCritere->zTitlePost != ""}value="{$oCritere->zTitlePost}" {else} value="" {/if}/>
						</span>
					</p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Type :</label>
						<span class="champ">
							<select id="zTypePost" name="zTypePost" style="width:120px;">
									<option value="0" {if isset($oCritere) && $oCritere->zTypePost == 0}selected="selected"{/if}>-------Tous -------</option>
									<option value="1" {if isset($oCritere) && $oCritere->zTypePost == 1}selected="selected"{/if}>Revision</option>
									<option value="2" {if isset($oCritere) && $oCritere->zTypePost == 2}selected="selected"{/if}>Post</option>
							</select>
						</span>
					</p>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Date de publication :</label>
						 <span class="champ">

							<input type="text" name="zDatePost" id="zDatePost" readonly style="width:100px;vertical-align:middle;top:auto; "  maxlength="10" tmt:datepattern="DD/MM/YYYY" {*{if isset ($oCritere->zDatePost) && $oCritere->zDatePost != ""}value="{$oCritere->zDatePost}" {else} value="" {/if}*}/>
							{literal}
								<img src="design/back/images/picto_calendar_search.jpg"  name="debut" id="debut" class="imageDate1" style="vertical-align:middle;top:auto"/>
									<script type="text/javascript">
										Calendar.setup({
											inputField     :    "zDatePost",	// id of the input field
											ifFormat       :    "%d/%m/%Y",		// format of the input field
											showsTime      :    false,			// will display a time selector
											button         :    "debut",		// trigger for the calendar (button ID)
											singleClick    :    true,			// double-click mode
											step           :    1				// show all years in drop-down boxes (instead of every other year as default)
										});
									</script>                        
							{/literal}

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