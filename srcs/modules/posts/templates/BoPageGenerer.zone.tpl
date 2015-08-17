<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">Gestion des posts</h1>
<h2>Générer les projets pour chaque posts</h2>
<form id="edit_form" action="{jurl 'posts~posts:generateProjetPost', array(), false}" method="POST" enctype="multipart/form-data" onsubmit="return tmt_validateForm(this);">
    <p class="line_bottom">&nbsp;</p>
    <p class="errorMessage" id="errorMessage"></p>
	<p class="frmBoutonr" style="text-align:center">
		<a class="bouton submit" href="#">Générer</a>
	</p>
	<br />
	{if isset($res) && $res == 1001}
	{literal}
		<script type="text/javascript">
			alert("Success !!!");
		</script>
	{/literal}
	{/if}
	<p class="errorMessage" id="errorMessage"></p>
</form>