<h1 class="noBg">Gestion des événements</h1>
<h2> Recherche </h2>
<div class="ajaxZone">
	    {$oListeCritereRecerche}
</div>
<p class="line_bottom">&nbsp;</p>
<h2>Listes</h2>
<p class="line_bottom">&nbsp;</p>
<p class="button">
    <input type="button" class="bouton" name="nouveau" value="Nouveau" onclick="location.href='{jurl 'evenement~evenement:edit', array(), false}'"/>
</p>
<p class="line_bottom">&nbsp;</p>
	<div class="ajaxZone" id="AjaxL">
	    {$oListeAjax}
	</div>
<p class="line_bottom">&nbsp;</p>

