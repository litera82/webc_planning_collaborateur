<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">Gestion des disponibilités</h1>
<h2>Disponibilités</h2>
<form id="edit_form" action="{jurl 'evenement~evenement:generateDisponibilite', array(), false}" method="POST" enctype="multipart/form-data" tmt:callback="displayError" tmt:validate="true" onsubmit="return tmt_validateForm(this);" >
	<table cellspacing="0" class="expanded"  id="table_panneau">
		<tr>
			<td valign="top" colspan="2">
				<p class="clearfix">
						<label>Collaborateur *:</label>
						<span class="champ">
							<select name="utilisateur_id" id="utilisateur_id" tmt:message="Veuillez séléctionner un collaborateur<br />" tmt:required="true" tmt:invalidIndex="0">
								<option value="0">-----------Séléctionner-----------</option>
								{foreach $toUtilisateur as $oUtilisateur}
									<option value="{$oUtilisateur->utilisateur_id}">{$oUtilisateur->utilisateur_zNom}&nbsp;{$oUtilisateur->utilisateur_zPrenom}</option>
								{/foreach}
							</select>
						</span>
					</p>					
			</td>
		</tr>
		<tr>
			<td valign="top">		
				<p class="clearfix">
					<label>Semaine du :</label>
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
		</tr>			
	</table>
    <p class="line_bottom">&nbsp;</p>
    <p class="bouton_top" style="text-align: center; color: grey; font-weight: bold;">Si vous ne renseignez pas les dates, cela va générer les disponibilités de cette semaine.</p>
    <p class="errorMessage" id="errorMessage"></p>
	<p class="frmBoutonr" align="right">
		<a class="bouton submit" href="#">Générer</a>
	</p>
	<br />
	<p class="errorMessage" id="errorMessage"></p>
</form>