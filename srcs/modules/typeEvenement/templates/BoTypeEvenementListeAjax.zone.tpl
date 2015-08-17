{literal}
<script type="text/javascript">
	function permuter (_id,_iAction){
		var zUrlPermuter = "{/literal}{jurl 'typeEvenement~typeEvenement:permuter'}{literal}";
        $.ajax
         ({
               type: "POST",
               url: zUrlPermuter,
			   cache: false,
               data: {
						'iId' : _id,
						'iAction' : _iAction
                     }
         });
         return false ;   
	}
</script>

{/literal} 
<div class="sortableListWithPagination">
    <table cellspacing="0" class="expanded" id="tableAnnoncesList" zCurrentSortField="{$zSortField}" zCurrentSortDirection="{$zSortDirection}" iCurrentPage="{$iCurrentPage}" iNbPage="{$iNbPages}" src="{jurl 'commun~CommunBo:getZone', $tzParams}"> 
        <thead>
            <th class="color1" zSortfield="typeevenements_id" style="width:10%;">Id</th>
            <th class="color2" zSortfield="typeevenements_zLibelle" style="width:45%;">Libellé</th>
            <th class="color1" style="width:15%;">Couleur</th>
            <th class="color2" zSortfield="typeevenements_iDure" style="width:15%;">Durée par défaut</th>
			<th class="color1" zSortfield="typeevenements_iStatut" style="width:15%;">Statut</th>
			<th class="color2" zSortfield="typeevenements_iOrdre" style="width:15%;">Ordre</th>
			<th class="color1" style="width:15%;">Edition</th>
            <th class="color2" style="width:15%;">Suppression</th>
        </thead>
        <tbody>
            {if $iNumListes == 0}
            <tr class="row1">
                <td colspan="10" class="color2 _center b_orange">Aucun type d'événements</td>
            </tr>
            {else}
            {assign $i = 1}
            {foreach $toListes as $oListe} 
            <tr class="row{$i++%2+1}">
                <td class="color1" style="text-align: center;">{$oListe->typeevenements_id}</td>
                <td class="color2">{$oListe->typeevenements_zLibelle }</td>
                <td class="color1" style="text-align: center;"><p style="height:19px; width:70px; margin-left:10px; background-color:{$oListe->typeevenements_zCouleur};"></p></td>
                <td class="color2" style="text-align: center;">
					{if $oListe->typeevenements_iDureeTypeId == 1}
						{assign $zDureParDefaut = ' heures'}
					{else}
						{assign $zDureParDefaut = ' minutes'} 
					{/if}
					{$oListe->typeevenements_iDure} {$zDureParDefaut}
				</td>
                <td class="color1" style="text-align: center;">
                    {if $oListe->typeevenements_iStatut == 2}
                    <img src="{$j_basepath}design/back/images/non-traite.gif" alt="0" />
                    {/if}
                    {if $oListe->typeevenements_iStatut == 1}
                    <img src="{$j_basepath}design/back/images/publier.gif" alt="1" />
                    {/if}
                    {if $oListe->typeevenements_iStatut == 0}
                    <img src="{$j_basepath}design/back/images/retirer.gif" alt="2" />
                    {/if}
                </td>
                <td class="color2" style="text-align: center;">
					{if $oListe->canUp}
						<a href="{jurl 'typeEvenement~typeEvenement:permuter', array ('iId'=>$oListe->typeevenements_id, 'iAction'=>0)}" id="monter" title="monter" >
							<img src="{$j_basepath}design/back/images/arrow_up.gif" border="0" alt="monter"/>
						</a>						
					{/if}
					{if $oListe->canDown}
						<a href="{jurl 'typeEvenement~typeEvenement:permuter', array ('iId'=>$oListe->typeevenements_id, 'iAction'=>1)}" id="descendre" title="descendre" >
							<img src="{$j_basepath}design/back/images/arrow_down.gif" border="0" alt="descendre"/>
						</a>
					{/if}
				</td>
                <td class="color1" style="text-align: center;"><a href="{jurl 'typeEvenement~typeEvenement:edit', array('page' => $iCurrentPage,'iTypeEvenementId' => $oListe->typeevenements_id), false}"><img src="{$j_basepath}design/back/images/edit.gif" alt="Editer" title="Editer" border="0" /></a></td>
                <td class="color2" style="text-align: center;"><a href="#" onclick="return deleteEntryTypeEvenemt('{jurl 'typeEvenement~typeEvenement:delete', array('page' => $iCurrentPage,'iTypeEvenementId' => $oListe->typeevenements_id), false}', 'Voulez-vous vraiment supprimer cet type d\'événement?', {$oListe->typeevenements_id}); return false;"><img src="{$j_basepath}design/back/images/delete.gif" alt="Supprimer" title="Supprimer" border="0" /></a></td>
            </tr>
            {/foreach}
            {/if}
        </tbody>
    </table>    
</div>