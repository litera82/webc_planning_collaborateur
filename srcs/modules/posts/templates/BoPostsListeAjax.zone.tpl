<div class="sortableListWithPagination">
    <table cellspacing="0" class="expanded" id="tableAnnoncesList" zCurrentSortField="{$zSortField}" zCurrentSortDirection="{$zSortDirection}" iParPage="{$iParPage}" iNbrTotal="{$iNbrTotal}"  iCurrentPage="{$iCurrentPage}" iNbPage="{$iNbPages}" src="{jurl 'commun~CommunBo:getZone', $tzParams}"> 
		<thead>
            <th class="color1" zSortfield="id" style="width:10%;">Id</th>
            <th class="color2" zSortfield="post_title" style="width:20%;">Titre</th>
            <th class="color2" zSortfield="post_date" style="width:10%;">Date</th>
            <th class="color1" zSortfield="post_status" style="width:10%;">Statut</th>
			<th class="color2" zSortfield="post_name" style="width:20%;">Nom</th>
			<th class="color1" zSortfield="guid" style="width:20%;">Url</th>
			<th class="color2" zSortfield="post_type" style="width:10%;">Type</th>
            <th class="color1" style="width:5%;"> </th>
            <th class="color2" style="width:5%;"> </th>
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
                <td class="color2">{$oListe->post_title}</td>
                <td class="color1">{$oListe->post_date|date_format:"%d/%m/%Y"}</td>
                <td class="color2">{$oListe->post_status}</td>
                <td class="color1">{$oListe->post_name}</td>
                <td class="color2"><a href="{$oListe->guid}" target="_blank">{$oListe->guid}</a></td>
                <td class="color1">{$oListe->post_type}</td>
                <td class="color2" style="text-align: center;"><a href="{jurl 'posts~posts:edit', array('page' => $iCurrentPage,'id' => $oListe->ID), false}"><img src="{$j_basepath}design/back/images/edit.gif" alt="Editer" title="Editer" border="0" /></a></td>
                <td class="color1" style="text-align: center;"><a href="#" onclick="return deleteEntry('{jurl 'posts~posts:delete', array('page' => $iCurrentPage,'id' => $oListe->ID), false}', 'Voulez-vous vraiment supprimer ce post'); return false;"><img src="{$j_basepath}design/back/images/delete.gif" alt="Supprimer" title="Supprimer" border="0" /></a></td>
            </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>    
	<div class="page"></div>
</div>