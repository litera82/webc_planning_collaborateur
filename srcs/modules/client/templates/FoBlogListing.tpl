<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			<div class="formevent clear" style="padding-bottom: 1px; padding-top: 0px; border-bottom-width: 0px; width: 700px;">
				<form id="edit_form" action="{jurl 'client~FoBlog:getBlog'}" method="POST" enctype="multipart/form-data">
						<h2>Recherche de blogs</h2>
						<p class="clear">
							<label style="width:200px;">Collaborateur</label>
							<select class="text"  style="width:300px;" name="blog_utilisateurId" id="blog_utilisateurId" >
								<option value="0">----------------------------------Tous----------------------------------</option>
								{foreach $toUtilisateur as $oUtilisateur}
									<option value="{$oUtilisateur->utilisateur_id}" {if isset ($toParams[0]->blog_utilisateurId) && $toParams[0]->blog_utilisateurId == $oUtilisateur->utilisateur_id}selected="selected"{/if}>{$oUtilisateur->utilisateur_zNom} {$oUtilisateur->utilisateur_zPrenom}</option>
								{/foreach}
							</select>
							<input type="submit" value="Rechercher" class="boutonform" style="margin-left: 10px;padding:1px 5px;"/>
						</p>
				</form>
			</div>
			<div class="content" style="padding-top: 5px;">
			<div class="formevent listeclients clear" style="width:943px">
				<div class="tabevent">
					<table cellpadding="0" cellspacing="0" border="0">
						<tbody>
							<tr>
								<th class="col1" style="width:33%;border:1px solid #E9E9E9;"><span>&nbsp;&nbsp;Titre</span></th>
								<th class="col2" style="width:33%;border:1px solid #E9E9E9;">&nbsp;&nbsp;Auteur</th>
								<th class="col3" style="width:33%;border:1px solid #E9E9E9;">&nbsp;&nbsp;Catégories</th>
							</tr>
						{assign $i = 1}
						{foreach $toBlog as $oBlog}
						<tr class="extra{$i++%2+1}" style="width:33%;border:1px solid #E9E9E9;">
							<td class="col1">
								<span>
									<a href="http://{$oBlog->client_url}" target="_blank">
										&nbsp;&nbsp;{$oBlog->client_zNom}&nbsp;{$oBlog->client_zPrenom}
									</a>
								</span>
							</td>
							<td class="col2" style="">&nbsp;&nbsp;{$oBlog->utilisateur_zNom} {$oBlog->utilisateur_zPrenom}</td>
							<td class="col3" style="width:auto;">{$oBlog->societe_zNom}</td>
						</tr>
						{/foreach}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
{$footer}