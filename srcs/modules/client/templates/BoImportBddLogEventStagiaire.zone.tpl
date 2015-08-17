<h1 class="noBg">Projets > importation</h1>
<h2>Importer les données du projet depuis la BDD LOGEVENT</h2>
<form id="edit_form" onsubmit="return tmt_validateForm(this);" action="{jurl 'client~client:getClientDataDepuisBddLogEvent', array(), false}" method="POST" enctype="multipart/form-data" tmt:validate="true" >
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
					alert("Erreur lors l'importation des données projets !!!");
				</script>
			{/literal}
			{/if}
			{if $x == 1003}
				<script type="text/javascript">
					alert("L'importation du fichier xml a été effectuée avec succès !!!");
				</script>
			{/if}
		{/if}
</form>
<p class="line_bottom">&nbsp;</p>