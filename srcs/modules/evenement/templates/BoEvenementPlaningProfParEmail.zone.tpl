<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">Envoyer le planning du collaborateur par email</h1>
<h2>Envoyer le planning du collaborateur par email</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'evenement~evenement:generatePlaningProfParEmail', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
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
			<a class="bouton submit" href="#">Envoyer l'email au collaborateur</a>
		</p>		
	<br />
	<p class="errorMessage" id="errorMessage"></p>
		{if isset($res) && $res != 0}
			{if $res == 1001}
			{literal}
				<script type="text/javascript">
					alert("Email envoyé avec succes !!!");
				</script>
			{/literal}
			{/if}
			{if $res == 1002}
				<script type="text/javascript">
					alert("Erreur lors de l'envoie du mail !!!");
				</script>
			{/if}
			{if $res == 1003}
				<script type="text/javascript">
					alert("Aucun mail envoyé car le collaborateur n'a pas de données enregistrer selon les critères remplies!!!");
				</script>
			{/if}
		{/if}
</form>

		<p class="frmBoutonr" style="text-align:center">
			<a class="bouton " href="{jurl 'evenement~sendExportEventByEmail:sendExportEventByEmailQuotidien', array(), false}">Envoie quotidien</a>
			<a class="bouton" href="{jurl 'evenement~sendExportEventByEmail:sendExportEventByEmailHebdomadaire', array(), false}">Envoie par semaine</a>
			<a class="bouton" href="{jurl 'evenement~sendExportEventByEmail:sendExportEventByEmailTwoWeek', array(), false}">Envoie tous les 2 semaine</a>
			<a class="bouton" href="{jurl 'evenement~sendExportEventByEmail:sendExportEventByEmailMounth', array(), false}">Envoie tous les mois</a>
		</p>