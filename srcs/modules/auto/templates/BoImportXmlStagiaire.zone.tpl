<h1 class="noBg">Auto-planification</h1>
<h2> Import de fichier XML des projets pour l'auto-planification </h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'auto~import:index', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
	<p class="clearfix">
		<label>Fichier *:</label>
		<span class="champ">
			<input type="file" name="PostedXMLStagiaire" id="PostedXMLStagiaire" size="70" tmt:message="Veuillez sélectionner un fichier .xml dont le nom ne comporte ni espace ni caractère accentué.<br/>" tmt:pattern="filepath_xml" tmt:required="true"/>    
		</span>
    </p>
	<!--p class="clearfix">
		<label>Fichier *:</label>
		<span class="champ">
			<input type="text" name="PostedXMLStagiaire" id="PostedXMLStagiaire"/>    
		</span>
    </p-->

	<p class="bouton_top"></p>
    <p class="errorMessage" id="errorMessage"></p>
    <p class="button" style="text-align:center;">
        <input type="button" class="bouton submit" name="enregistrer" id="valider" value="Lancer l'importation" />&nbsp;
    </p>
	<p class="errorMessage" id="errorMessage"></p>
		{if isset($x) && $x != 0}
			{if $x == 1001}
			{literal}
				<script type="text/javascript">
					alert("Erreur lors du transfert du fichier !!!");
				</script>
			{/literal}
			{/if}
			{if $x == 1002}
				<script type="text/javascript">
					alert("Erreur lors de l'upload du fichier !!!");
				</script>
			{/if}
			{if $x == 1003}
				<script type="text/javascript">
					alert("L'importation du fichier xml a été effectuée avec succès !!!");
				</script>
			{/if}
		{/if}
</form>
<p class="line_bottom">&nbsp;</p>