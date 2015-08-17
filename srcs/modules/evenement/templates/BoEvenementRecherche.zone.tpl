	<form name="recetteRechercheBo" id="recetteRechercheBo" method="post" action="{jurl 'evenement~evenement:index'}" onsubmit="return tmt_validateForm(this);"  tmt:validate="true" tmt:callback="displayError">
		<table cellspacing="0" class="expanded"  id="table_panneau">
			<tr>
				<td valign="top">
					<p class="clearfix">
						<label>Libelle :</label>
						<span class="champ">
							<input type="text" id="libelle" name="libelle" {if isset ($oCritere->libelle) && $oCritere->libelle != ""}value="{$oCritere->libelle}" {else} value="" {/if}/>
						</span>
					</p>
				</td>
				<td valign="top">
					<p class="clearfix">
						<label>Statut de publication :</label>
						 <span class="champ">
							<select id="statut" name="statut" style="width:120px;">
									<option value="3"  {if isset($oCritere) && $oCritere->statut == 3}selected="selected"{/if} >-------Toutes -------</option>
									<option value="1" {if isset($oCritere) && $oCritere->statut == 1}selected="selected"{/if}>Publier</option>
									<option value="2" {if isset($oCritere) && $oCritere->statut == 2}selected="selected"{/if}>Non publier</option>
									<option value="0" {if isset($oCritere) && $oCritere->statut == 0}selected="selected"{/if}>Desactiver</option>
							</select>
						 </span>
					</p>
				</td>
			</tr>
			<tr>
				<td valign="top">		
					<p class="clearfix">
						<label>Date entre le :</label>
						 <span class="champ">
                        <input type="text" name="zDateDebut" id="zDateDebut" readonly style="width:100px;vertical-align:middle;top:auto; "  maxlength="10" tmt:datepattern="DD/MM/YYYY" {if isset ($oCritere->zDateDebut) && $oCritere->zDateDebut != ''}value="{$oCritere->zDateDebut}" {else} value=""{/if} />
                        {literal}
                            <img src="design/back/images/picto_calendar_search.jpg"  name="debut" id="debut" class="imageDate1" style="vertical-align:middle;top:auto"/>
    							<script type="text/javascript">
    								Calendar.setup({
    									inputField     :    "zDateDebut",	// id of the input field
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
				<td valign="top">		
					<p class="clearfix">
						<label>et le :</label>
						 <span class="champ">
							<input type="text" name="zDateFin" id="zDateFin" readonly style="width:100px;vertical-align:middle;top:auto; " maxlength="10" tmt:datepattern="DD/MM/YYYY"  {if isset ($oCritere->zDateFin) && $oCritere->zDateFin != ''}value="{$oCritere->zDateFin}" {else} value=""{/if}>
								{literal}
									<img src="design/back/images/picto_calendar_search.jpg"  name="fin" id="fin" class="imageDate2" style="vertical-align:middle;top:auto"/>
										<script type="text/javascript">
											Calendar.setup({
												inputField     :    "zDateFin",		// id of the input field
												ifFormat       :    "%d/%m/%Y",		// format of the input field
												showsTime      :    false,			// will display a time selector
												button         :    "fin",			// trigger for the calendar (button ID)
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