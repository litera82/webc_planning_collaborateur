{literal}
	<script type="text/javascript">
		$( function () {
			$("#btDate").click(
				function (){
					//#btDate
				}
			);
			$("#btAujourdhui").click(
				function (){
					//#btAujourdhui
				}
			);
			$("#btSemaine").click(
				function (){
					//#btSemaine
				}
			);
			$("#btMois").click(
				function (){
					//#btMois 
				}
			);			
			date_heure("dp1301326611454");
			$('#journe').click (
				function (){
					var jour = $("#jour").val();					
					var mois = $("#mois").val();					
					var annee = $("#annee").val();					
					var iAffichage = 2; 
					var zPath = j_basepath + "index.php?module=jelix_calendar&action=FoCalendar:index"
					document.location.href = zPath + "&date="+annee+"-"+mois+"-"+jour+"&iAffichage="+iAffichage;
					return false;
				}
			);
		});		

	</script>
{/literal}
<div class="header clear noPrint">
            	<p class="date" id="dp1301326611454">Jeudi 18 février 2010 / 12:04:06</p>
                <div class="centralhead" style="width:345px;">
                	<p class="hi">Bonjour<span>{$oUtilisateur->utilisateur_zPrenom}</span></p>
                	<a title="Se déconnecter" href="{jurl 'jauth~login:outFO'}"><span>Se déconnecter</span></a>
					{if $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR}
						<a title="Back office" href="{$j_basepath}admin.php"><span>Back office</span></a>
					{/if}
				</div>
                <div class="centralhead" style="width:180px;text-align:center;">
                    <ul>
                        <li><a title="Journée" href="{jurl 'jelix_calendar~FoCalendar:index', array('iAffichage'=>2)}" target="_blank"><span>Journée</span></a></li>
						<li><a title="Semaine" href="{jurl 'jelix_calendar~FoCalendar:index', array('iAffichage'=>1)}" target="_blank"><span>Semaine</span></a></li>
                    	<li><a title="Mois" href="{jurl 'jelix_calendar~FoCalendar:index', array('iAffichage'=>3)}" target="_blank"><span>Mois</span></a></li>
                    </ul>
				</div>
                <div class="formhead" style="width:268px;text-align:center;">
                    <ul>
						<li><a target="_blank" title="Approche par liste" href="{jurl 'evenement~FoEvenement:getEventListingDispo', array(), false}"><span>Disponibilité</span></a></li>
						<li><a target="_blank" title="Event Listing" href="{jurl 'evenement~FoEvenement:getEventListing'}"><span>Event listing</span></a></li>
                    	<li><a target="_blank" title="Listing des projets" href="{jurl 'client~FoClient:projetListing', array()}"><span>Projets</span></a></li>
						{*<!--<li><a target="_blank" title="Blog" href="{jurl 'client~FoBlog:getBlog', array()}"><span>Blog</span></a></li>-->*}
					</ul>
                </div>
                <!--<p class="linkR"><a title="Aide" href="#" class="create">Aide</a></p>-->
            </div>
			<!--div class="pop-up" id="periodepop" style="display: block; top: 47.5px; left: 923.5px;">
					<h2>Bienvenue sur le planning en ligne de Format2+</h2>
					<a class="fermer" title="Fermer" href="#"><img alt="fermer" src="{$j_basepath}design/front/images/design/close.png"></a>
					<div class="inner clear">
						<p class="clear">
							text aide
						</p>
						<div class="input">
							<a href="#"><input type="button" value="Fermer" class="boutonform fermer" /></a>
						</div>
					</div>
			</div-->
			<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>
