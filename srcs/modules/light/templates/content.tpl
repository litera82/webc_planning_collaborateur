<script type="text/javascript">
{literal}
$(function(){
    $('.suprimer').click(function(){
        var isDisponibility = $(this).attr('isDisponibility') ;
        if (confirm('Etes vous vraiment sûr de vouloir supprimer cet enregistrement ?')){
	       $.loader({width:221, height:20, content:'<img src="{/literal}{$j_basepath}design/commun/images/ajax-loader.gif"/>{literal}'});
           $.post("{/literal}{jurl 'light~default:suprimer', array(), false}{literal}", {id:$(this).attr('eventId')}, function(){
	             $('.content').load("{/literal}{jurl 'commun~CommunBo:getZone', array('zone'=>'light|||content'), false}{literal}", {isDisponibility:isDisponibility}, function(){
	                 doOnLoad($(this)) ;
                     $.loader('close');
	             }) ;
	        }) ;
        }
     });
    $('.liberer').click(function(){
        $.loader({width:221, height:20, content:'<img src="{/literal}{$j_basepath}design/commun/images/ajax-loader.gif"/>{literal}'});
        	$.post("{/literal}{jurl 'light~default:liberer', array(), false}{literal}", {id:$(this).attr('eventId')}, function(){
	             $('.content').load("{/literal}{jurl 'commun~CommunBo:getZone', array('zone'=>'light|||content'), false}{literal}", {}, function(){
	            	 doOnLoad($(this)) ;
                     $.loader('close');
	             }) ;
	        }) ;
     });
});
{/literal}
</script>
    <h2 class="demoHeaders">{if $isDisponibility}Mes disponibilités{else}Mon agenda{/if}</h2>
    <div id="accordion">
        {foreach $toEvents as $iKey => $oEvent}
    	<div>
    		<h3>
                <a href="#" style="color:{$oEvent->typeevenements_zCouleur}">
                    {$oEvent->evenement_zDateHeureDebut}&nbsp;
                    {if !$isDisponibility}
                        {if $oEvent->client_id}
                            {if $oEvent->client_iCivilite == CIVILITE_FEMME}Mme{else}Mr{/if} {$oEvent->client_zPrenom} {$oEvent->client_zNom} ({$oEvent->societe_zNom})
                        {else}
                            {$oEvent->evenement_zLibelle}
                        {/if}
                    {/if}
                </a>
            </h3>
    		<div>
                {if !$isDisponibility}
                    <p><label>Telephone :</label> 	{$oEvent->client_zTel}<br />
                    {if $oEvent->client_zPortable}<label>Num. de Portable :</label> 	{$oEvent->client_zPortable}<br />{/if}
                    <label>Courrier électronique :</label> 	{$oEvent->client_zMail}<br />
                    <label>Contact pour ce jour :</label> 	{$oEvent->evenement_zContactTel}</p>
                {/if}
                <p>
                    <b>Note</b><br />
                    {$oEvent->evenement_zDescription}
                </p>
                {if !$isDisponibility}<button class="liberer" eventId="{$oEvent->evenement_id}">Libérer</button> {/if}<button class="suprimer" eventId="{$oEvent->evenement_id}" isDisponibility="{$isDisponibility}">Supprimer</button>
            </div>
    	</div>
        {/foreach}
        {if !$iNbEvent}
    	<div>
    		<h3><a href="#">Aucune enregistrement</a></h3>
    	</div>
        {/if}
    </div>
    <div id="loader"></div>
<div id="loader"><img src="{$j_basepath}design/commun/images/ajax-loader.gif"/></div>