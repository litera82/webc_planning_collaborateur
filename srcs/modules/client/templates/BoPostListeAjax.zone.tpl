<div class="sortableListWithPagination">
    <table cellspacing="0" class="expanded" id="tableAnnoncesList" zCurrentSortField="{$zSortField}" zCurrentSortDirection="{$zSortDirection}" iParPage="{$iParPage}" iNbrTotal="{$iNbrTotal}" iCurrentPage="{$iCurrentPage}" iNbPage="{$iNbPages}" src="{jurl 'commun~CommunBo:getZone', $tzParams}"> 
		<thead>
            <th class="color1" zSortfield="client_id" style="width:10%;">Id</th>
            <th class="color2" zSortfield="client_zNom" style="width:10%;">Date</th>
            <th class="color1" zSortfield="client_zPrenom" style="width:60%;">Titre</th>
			<th class="color2" zSortfield="client_iStatut" style="width:10%;">Type</th>
            <th class="color1" style="width:10%;">Edition</th>
        </thead>
        <tbody>
            {if $iNumListes == 0}
				<tr class="row1">
					<td colspan="10" class="color2 _center b_orange" style="text-align:center;">Aucun projets</td>
				</tr>
				{else}
				{assign $i = 1}
				{foreach $toListes as $oListe} 
				<tr class="row{$i++%2+1}">
					<td class="color1" style="text-align: center;">{$oListe->ID}</td>
					<td class="color2">{$oListe->post_date|date_format:'%d/%m/%Y'}</td>
					<td class="color1">{$oListe->post_title}</td>
					<td class="color2">{$oListe->post_type}</td>
	                <td class="color1" style="text-align: center;"><a href="{$oListe->guid}" target="_blank"><img src="{$j_basepath}design/back/images/edit.gif" alt="Editer" title="{$oListe->guid}" border="0" /></a></td>

				</tr>
				{/foreach}
            {/if}
        </tbody>
    </table>    
	<div class="page"></div>
</div>