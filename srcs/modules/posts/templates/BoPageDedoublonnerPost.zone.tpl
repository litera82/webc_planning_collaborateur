<script type="text/javascript">
{literal}
{/literal}
</script>



<h1 class="noBg">Gestion des posts</h1>
<h2>Dedoublonner les posts</h2>
<form id="edit_form" action="{jurl 'posts~posts:dedoublonnerPost', array(), false}" method="POST" enctype="multipart/form-data" onsubmit="return tmt_validateForm(this);">
    <p class="line_bottom">&nbsp;</p>
    <p class="errorMessage" id="errorMessage"></p>
	<p class="frmBoutonr" style="text-align:center">
		<a class="bouton submit" href="#">Lancer le processus</a>
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