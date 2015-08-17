	<form name="recetteRechercheBo" id="recetteRechercheBo" method="post" action="{jurl 'logevent~logevent:logEvent'}" onsubmit="return tmt_validateForm(this);"  tmt:validate="true" tmt:callback="displayError">
		<input type="hidden" name="t" id="t" value="1" />
		<table cellspacing="0" class="expanded"  id="table_panneau">
			<tr>
				<td valign="top">		
					<p class="clearfix">
						<label>Date entre le :</label>
						 <span class="champ">
                        <input type="text" name="zDateDebut" id="zDateDebut" readonly style="width:100px;vertical-align:middle;top:auto; "  maxlength="10" tmt:datepattern="DD/MM/YYYY"/>
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
							<input type="text" name="zDateFin" id="zDateFin" readonly style="width:100px;vertical-align:middle;top:auto; " maxlength="10" tmt:datepattern="DD/MM/YYYY">
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
			<a class="bouton submit" href="#">Lancer l'exportation</a>
		</p>
	<!--p class="errorMessage" id="errorMessage"></p-->
	</form>