<div class="sortableListWithPagination">
    <table cellspacing="0" class="expanded" id="tableAnnoncesList" zCurrentSortField="{$zSortField}" zCurrentSortDirection="{$zSortDirection}" iParPage="{$iParPage}" iNbrTotal="{$iNbrTotal}"  iCurrentPage="{$iCurrentPage}" iNbPage="{$iNbPages}" src="{jurl 'commun~CommunBo:getZone', $tzParams}"> 
        <thead>
            <th class="color1" zSortfield="evenement_id" style="width:10%;">Id</th>
            <th class="color2" zSortfield="evenement_zLibelle" style="width:45%;">Libellé</th>
            <th class="color1" zSortfield="evenement_iDuree" style="width:15%;">Durée</th>
            <th class="color2" zSortfield="evenement_iTypeEvenementId" style="width:45%;">Type</th>
			<th class="color1" zSortfield="evenement_iStatut" style="width:15%;">Statut</th>
            <th class="color2" style="width:15%;">Edition</th>
            <th class="color1" style="width:15%;">Suppression</th>
        </thead>
        <tbody>
            {if $iNumListes == 0}
            <tr class="row1">
                <td colspan="10" class="color2 _center b_orange">Aucun événements</td>
            </tr>
            {else}
            {assign $i = 1}
            {foreach $toListes as $oListe} 
            <tr class="row{$i++%2+1}">
                <td class="color1" style="text-align: center;">{$oListe->evenement_id}</td>
                <td class="color2">{$oListe->evenement_zDateHeureDebut|date_format:"%d/%m/%Y %H:%M"}<br />{$oListe->client_zNom}&nbsp;{$oListe->client_zPrenom}<br />{$oListe->evenement_zDescription}</td>
                <td class="color1">{$oListe->evenement_iDuree } h</td>
                <td class="color2">{$oListe->typeevenements_zLibelle }</td>
                <td class="color1" style="text-align: center;">
                    {if $oListe->evenement_iStatut == 2}
                    <img src="{$j_basepath}design/back/images/non-traite.gif" alt="0" />
                    {/if}
                    {if $oListe->evenement_iStatut == 1}
                    <img src="{$j_basepath}design/back/images/publier.gif" alt="1" />
                    {/if}
                    {if $oListe->evenement_iStatut == 0}
                    <img src="{$j_basepath}design/back/images/retirer.gif" alt="2" />
                    {/if}
                </td>
                <td class="color2" style="text-align: center;"><a href="{jurl 'evenement~evenement:edit', array('page' => $iCurrentPage,'iEvenementId' => $oListe->evenement_id), false}"><img src="{$j_basepath}design/back/images/edit.gif" alt="Editer" title="Editer" border="0" /></a></td>
                <td class="color1" style="text-align: center;"><a href="#" onclick="return deleteEntry('{jurl 'evenement~evenement:delete', array('page' => $iCurrentPage,'iEvenementId' => $oListe->evenement_id), false}', 'Voulez-vous vraiment supprimer cet événement?'); return false;"><img src="{$j_basepath}design/back/images/delete.gif" alt="Supprimer" title="Supprimer" border="0" /></a></td>
            </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>    
	<div class="page"></div>
</div>