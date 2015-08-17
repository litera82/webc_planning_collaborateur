<ul>
	{foreach $toLegende as $oLegende}
	<li style="width:180px;border-color:{$oLegende->typeevenements_zCouleur};margin-bottom:5px;">{$oLegende->typeevenements_zLibelle}</li>
	{/foreach}
</ul>