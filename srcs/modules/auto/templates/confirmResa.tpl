<script type="text/javascript">
{literal}
$(function(){
});
{/literal}
</script>
    <h2 class="demoHeaders">Reservation pour test de début de stage / <i>Booking for test placement begins</i></h2>
    <div id="accordion">
        <div>
    		<h3><a href="#">Votre réservation à été bien prise en compte / <i>Your booking has been properly taken into account</i></a></h3>
            <p>Votre collaborateur {if $oEvent->utilisateur_iCivilite == CIVILITE_FEMME}Mme{else}Mr{/if} {$oEvent->utilisateur_zPrenom} {$oEvent->utilisateur_zNom} vous appellera au numero {$oEvent->evenement_zContactTel} le  {$oEvent->zDateString} à {$oEvent->zHeureString} pour votre test orale.<br/><br/>Un email vous a été envoyé pour confirmation<br/></p>
			<br/>
			<p><i>Your teacher {if $oEvent->utilisateur_iCivilite == CIVILITE_FEMME}Mrs{else}Mr{/if} {$oEvent->utilisateur_zPrenom} 
			{$oEvent->utilisateur_zNom} will call you at the number {$oEvent->evenement_zContactTel} on  {$oEvent->zDateString} at {$oEvent->zHeureString} to test your skills..<br/><br/>An email has been sent for confirmation<br/></i></p>
            <p>
    	</div>
    </div>
    <div id="loader"></div>
<div id="loader"><img src="{$j_basepath}design/commun/images/ajax-loader.gif"/></div>