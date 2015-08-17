<div class="sortableListWithPagination">
    <table cellspacing="0" class="expanded" id="tableAnnoncesList" zCurrentSortField="{$zSortField}" zCurrentSortDirection="{$zSortDirection}" iCurrentPage="{$iCurrentPage}" iNbPage="{$iNbPages}" src="{jurl 'commun~CommunBo:getZone', $tzParams}"> 
        <thead>
            <th class="color1" zSortfield="type_id" style="width:10%;">Id</th>
            <th class="color2" zSortfield="type_zLibelle" style="width:45%;">Libelle</th>
            <th class="color1" zSortfield="type_statut" style="width:15%;">Statut</th>
            <th class="color2" style="width:15%;">Edition</th>
            <th class="color1" style="width:15%;">Suppression</th>
        </thead>
        <tbody>
            {if $iNumListes == 0}
            <tr class="row1">
                <td colspan="10" class="color2 _center b_orange">Aucun Type</td>
            </tr>
            {else}
            {assign $i = 1}
            {foreach $toListes as $oListe} 
            <tr class="row{$i++%2+1}">
                <td class="color1" style="text-align: center;">{$oListe->type_id}</td>
                <td class="color2">{$oListe->type_zLibelle }</td>
                <td class="color1" style="text-align: center;">
                    {if $oListe->type_statut == 2}
                    <img src="{$j_basepath}design/back/images/non-traite.gif" alt="0" />
                    {/if}
                    {if $oListe->type_statut == 1}
                    <img src="{$j_basepath}design/back/images/publier.gif" alt="1" />
                    {/if}
                    {if $oListe->type_statut == 0}
                    <img src="{$j_basepath}design/back/images/retirer.gif" alt="2" />
                    {/if}
                </td>
                <td class="color2" style="text-align: center;"><a href="{jurl 'admin~typeUtilisateurs:edit', array('page' => $iCurrentPage,'iTypeId' => $oListe->type_id), false}"><img src="{$j_basepath}design/back/images/edit.gif" alt="Editer" title="Editer" border="0" /></a></td>
                <td class="color1" style="text-align: center;"><a href="#" onclick="return deleteEntry('{jurl 'admin~typeUtilisateurs:delete', array('page' => $iCurrentPage,'iTypeId' => $oListe->type_id), false}', 'Voulez-vous vraiment supprimer cet type d\'utilisateurs'); return false;"><img src="{$j_basepath}design/back/images/delete.gif" alt="Supprimer" title="Supprimer" border="0" /></a></td>
            </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>    
</div>