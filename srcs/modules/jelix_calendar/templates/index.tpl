{assign $idTypeEvenementCourTelephone = ID_TYPE_EVENEMENT_COUR_TELEPHONE}
{literal}
<script type="text/javascript">
	$(function(){ 
		$("a#commentEvent").simpletooltip({ click: true, showEffect: "slideDown", hideEffect: "slideUp", hideOnLeave: true,
			customTooltip: function(target){
				return '<div id="tooltip" class="tooltip tooltiptooltip" style="border-radius: 20px 20px 20px 20px;background-color:'+$(target).attr("colorbg")+'"><table border="0" cellspacing="0" cellpadding="0"><tr><td class="left">'+$(target).attr("description")+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="'+j_basepath+'design/front/images/design/edit.png" style="cursor: pointer;" onclick=\'javascript:editDescriptionEvent('+$(target).attr("iEventId")+',"'+$(target).attr("cellId")+'")\'></td></tr></table></div>'
			}
		});
		
		$("a#actionEvent").simpletooltip({ click: true, showEffect: "slideDown", hideEffect: "slideUp", hideOnLeave: false,
			customTooltip: function(target){
				afficherMasque(); 
				$('#iStagiaire').val(0);		
				$('.input_zStagiaire').val("");	
				$('#div-stagiaire-liste').hide();
				$('#evenement_zDescription').val("");
				return '<div id="tooltip" class="tooltip"><table border="0" cellspacing="0" cellpadding="0"><tr><td colspan="2"><p style="background-color: #899EB0; border: 1px solid #1E364E; text-align:center; border-radius: 0px 10px 0px 10px; color: #1E364E; font-size: 1em; font-weight: bold; padding:3px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ajouter un évènement rapidement&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td><td><a onclick="closeEventRapid();" rel="close" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="'+j_basepath+'design/front/images/design/pictos/close.png" border="0" /></a></td></tr><tr><td class="left" style="width:auto">&nbsp;</td></tr><tr><td class="left" style="width:auto">Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right">'+$(target).attr("zDate")+'</td></tr><tr><td class="left" style="width:auto">Heure :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right">'+$(target).attr("iTime")+'</td></tr><tr><td class="left" style="width:auto">Type d\'evenement :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right">'+$(target).attr("typeEvent")+'</td></tr>  <tr><td class="left" style="width:auto">Description:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right"><textarea style="width: 293px; margin-left:0; border: 1px solid; height: auto;" name="evenement_zDescription" id="evenement_zDescription"></textarea></td></tr>  <tr><td class="left" style="width:auto">Projet :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right"><input type="hidden" name="zDate" id="zDate" value="'+$(target).attr("zDate")+'" /><input type="hidden" name="iTime" id="iTime" value="'+$(target).attr("iTime")+'" /><input type="hidden" name="iStagiaire" id="iStagiaire" value="" /><input type="text" value="" id="evenement_zStagiaire" name="evenement_zStagiaire" class="text input_zStagiaire" style="border:1px solid #000000;height:20px;width:276px;">&nbsp;<img onclick="rechercherStagiaire();" src="'+j_basepath+'design/front/images/design/rechercher.png" alt="Ajouter un projet" style="cursor:pointer;"/></td></tr><tr class="affichageListeStagiaire" style="display:none;"><td class="left" style="width:auto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right"><p class="clear" id="div-stagiaire-liste"><label for="dtcm_event_project">&nbsp;</label><select style="width: 300px; border: 1px solid; margin-left: -4px; height: auto;" name="stagiaire-liste" id="stagiaire-liste" size="10" url=""><option></option></select></p></td></tr><td class="left" style="width:auto">&nbsp;</td><td class="right" style="text-align:right;"><a title="Enregistrer l\'évènement" href="#" onclick="#"><input type="button" onclick=\'saveEvent("'+$(target).attr("cellId")+'");return false;\' value="Enregistrer l\'évènement" class="boutonform" style="background-color:#8FA8C1;cursor:pointer;-moz-border-radius:5px 5px 5px 5xp;height:24px;padding:0 3px;vertical-align:middle;border:1px solid #1E364E;"/></a></td></tr></table></div>'
			}
		});
		$("a.project").simpletooltip({ click: true, showEffect: "slideDown", hideEffect: "slideUp", hideOnLeave: true,
			customTooltip: function(target){
				var zImgEtat = "" ;	
				if ($(target).attr("typeetat") == 1){
					zImgEtat += '&nbsp;&nbsp;&nbsp;<img alt="Qualif1" title="Qualif1" src="{/literal}{$j_basepath}{literal}design/front/images/design/pictos/produit.png">&nbsp;&nbsp;&nbsp;<u>Etat de l\'evenement&nbsp;:</u>&nbsp;Qualif1' ;
				}
				if ($(target).attr("typeetat") == 2){
					zImgEtat += '&nbsp;&nbsp;&nbsp;<img alt="Qualif2" title="Qualif2" src="{/literal}{$j_basepath}{literal}design/front/images/design/pictos/annule.png">&nbsp;&nbsp;&nbsp;<u>Etat de l\'evenement&nbsp;:</u>&nbsp;Qualif2' ;
				}
				if ($(target).attr("typeetat") == 3){
					zImgEtat += '&nbsp;&nbsp;&nbsp;<img alt="Qualif3" title="Qualif3" src="{/literal}{$j_basepath}{literal}design/front/images/design/pictos/deplace.png">&nbsp;&nbsp;&nbsp;<u>Etat de l\'evenement&nbsp;:</u>&nbsp;Qualif3' ;
				}

				if (zImgEtat == ""){
					zImgEtat += '&nbsp;&nbsp;&nbsp;<a href="#"><img alt="Qualif1" title="Qualif1" src="{/literal}{$j_basepath}{literal}design/front/images/design/pictos/produit.png"></a>&nbsp;&nbsp;Qualif1&nbsp;&nbsp;&nbsp;<a href="#"><img alt="Qualif2" title="Cours produit" src="{/literal}{$j_basepath}{literal}design/front/images/design/pictos/annule.png"></a>&nbsp;&nbsp;Qualif2&nbsp;&nbsp;&nbsp;<a href="#"><img alt="Qualif3" title="Cours produit" src="{/literal}{$j_basepath}{literal}design/front/images/design/pictos/deplace.png"></a>&nbsp;&nbsp;Qualif3' ;
				}

				return '<div id="tooltip" class="tooltip" style="background-color:'+$(target).attr("colorbg")+'"><table border="0" cellspacing="0" cellpadding="0"><tr><td colspan="2"><p style="background-color: #899EB0; border: 1px solid #1E364E; text-align:center; border-radius: 0px 10px 0px 10px; color: #1E364E; font-size: 1em; font-weight: bold; adding:3px">Evénnement</p></td></tr><tr><td class="left" style="width:auto">Libellé</td><td class="right">'+$(target).attr("titre")+'</td></tr><tr><td class="left" style="width:auto">Type</td><td class="right">'+$(target).attr("types")+'</td></tr><tr><td class="left" style="width:auto">Description</td><td class="right">'+$(target).attr("description")+'</td></tr><tr><td class="left" style="width:auto">Date</td><td class="right">'+$(target).attr("dateFr")+'</td></tr><tr><td class="left" style="width:auto">Heure</td><td class="right">'+$(target).attr("heure")+'</td></tr><tr><td class="left" style="width:auto">Durée</td><td class="right">'+$(target).attr("dure")+' '+$(target).attr("typeDuree")+'</td></tr><tr><td colspan="2"><p style="background-color: #899EB0; border: 1px solid #1E364E; text-align:center; border-radius: 0px 10px 0px 10px; color: #1E364E; font-size: 1em; font-weight: bold; adding:3px">Projet / Post</p></td></tr><tr><td class="left" style="width:auto">Titre</td><td class="right">'+$(target).attr("posttitle")+'</td></tr><tr><td class="left" style="width:auto">Post Status</td><td class="right">'+$(target).attr("poststatus")+'</td></tr><tr><td class="left" style="width:auto">Post Type</td><td class="right">'+$(target).attr("posttype")+'</td></tr><tr><td class="left" style="width:auto">Blog</td><td class="right"><a style="color:black;" title="http://'+$(target).attr("blog")+'" target="_blank" href="http://'+$(target).attr("blog")+'">'+$(target).attr("blog")+'</a></td></tr><tr><td colspan="2"><p style="background-color: #899EB0; border: 1px solid #1E364E; text-align:center; border-radius: 0px 10px 0px 10px; color: #1E364E; font-size: 1em; font-weight: bold; adding:3px">Forma2+</p></td></tr><tr><td class="left" style="width:auto">Responsable de formation&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right">'+$(target).attr("createur")+'</td></tr><tr><td class="left" style="width:auto">Créateur</td><td class="right">'+$(target).attr("createur")+'</td></tr><tr><td class="left" style="width:auto">&nbsp;</td><td class="right">&nbsp;</td></tr><tr style="text-align:right;"><td class="left" style="width:auto">'+zImgEtat+'</td><td class="right"><a title="Modifier l\'évènement" href="'+$(target).attr("url")+'"><input type="button" value="&nbsp;&nbsp;&nbsp;Modifier&nbsp;&nbsp;&nbsp;" class="boutonform" style="background-color:#8FA8C1;cursor:pointer;-moz-border-radius:5px 5px 5px 5xp;height:24px;padding:0 3px;vertical-align:middle;border:1px solid #1E364E;"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a title="Supprimer l\'évènement" href="#" onclick="deleteEvent('+$(target).attr("iEventId")+','+$(target).attr("date")+');"><input type="button" value="Supprimer" class="boutonform" style="background-color:#8FA8C1;cursor:pointer;-moz-border-radius:5px 5px 5px 5xp;height:24px;padding:0 3px;vertical-align:middle;border:1px solid #1E364E;"/></a></td></tr></table></div>';
			}
		});
	}); 
	function editDescriptionEvent(iEventId, cellId){
		$('.tooltiptooltip').find('table').empty() ;
		if (iEventId > 0){
			$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:chargeEvenementParId", iEvenementId:iEventId}, function(row){
				$('.tooltiptooltip').find('table').append("<tr><td><textarea id='evenement_zDescription' name='evenement_zDescription' style='width: 293px; margin-left:0; border: 1px solid; height: auto;'>"+row['evenement_zDescription']+"</textarea></td></tr><tr><td style='text-align:right;'><input type='button' value='OK' class='boutonform' style='background-color:#8FA8C1;cursor:pointer;-moz-border-radius:5px 5px 5px 5xp;vertical-align:middle;border:1px solid #1E364E;' onclick='javascript:saveDesc("+iEventId+","+cellId+");return false;'/></td></tr>") ;
			});		
		}
	}
	function saveDesc(iEventId, cellId){
		var desc = $('#evenement_zDescription').val() ;
		if (desc == ""){
			$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:chargeTypeEvenementParEventId", iEvenementId:iEventId}, function(row){
			$.loader({width:340, height:39, content:'<img src="{/literal}{$j_basepath}design/front/images/design/loading14.gif"/>{literal}'});
				$.ajax({
					 type:"POST",
					 url:$('#urlSaveDescEvent').val(),
					 data:{	iEventId:iEventId, desc:row},
					 async:false,
					 success:function(resultat){
						$('td#'+cellId).html('');
						$('td#'+cellId).html(resultat);
						$('.tooltip').hide() ;
						closeEventRapid () ; 
					 }
				});
				$.loader('close') ;

			});
		}else{
			$.loader({width:340, height:39, content:'<img src="{/literal}{$j_basepath}design/front/images/design/loading14.gif"/>{literal}'});
			$.ajax({
				 type:"POST",
				 url:$('#urlSaveDescEvent').val(),
				 data:{	iEventId:iEventId, desc:desc},
				 async:false,
				 success:function(resultat){
					$('td#'+cellId).html('');
					$('td#'+cellId).html(resultat);
					$('.tooltip').hide() ;
					closeEventRapid () ; 
				 }
			});
			$.loader('close') ;			
		}
	}
	function showCommentEvent (iEventId, cellClass){
		$("a."+cellClass).simpletooltip({ click: true, showEffect: "slideDown", hideEffect: "slideUp", hideOnLeave: true,
			customTooltip: function(target){
				return '<div id="tooltip" class="tooltip tooltiptooltip" style="border-radius: 20px 20px 20px 20px;background-color:'+$(target).attr("colorbg")+'"><table border="0" cellspacing="0" cellpadding="0"><tr><td class="left">'+$(target).attr("description")+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="'+j_basepath+'design/front/images/design/edit.png" style="cursor: pointer;" onclick=\'javascript:editDescriptionEvent('+iEventId+',"'+cellClass+'")\'></td></tr></table></div>'
			}
		});
	}
	function getTooltipProject(cellClass){
		$("a."+cellClass).simpletooltip({ click: true, showEffect: "slideDown", hideEffect: "slideUp", hideOnLeave: true,
			customTooltip: function(target){
				return '<div id="tooltip" class="tooltip" style="background-color:'+$(target).attr("colorbg")+'"><table border="0" cellspacing="0" cellpadding="0"><tr><td colspan="2"><p style="background-color: #899EB0; border: 1px solid #1E364E; text-align:center; border-radius: 0px 10px 0px 10px; color: #1E364E; font-size: 1em; font-weight: bold; adding:3px">Evénnement</p></td></tr><tr><td class="left" style="width:auto">Libellé</td><td class="right">'+$(target).attr("titre")+'</td></tr><tr><td class="left" style="width:auto">Type</td><td class="right">'+$(target).attr("types")+'</td></tr><tr><td class="left" style="width:auto">Description</td><td class="right">'+$(target).attr("description")+'</td></tr><tr><td class="left" style="width:auto">Date</td><td class="right">'+$(target).attr("dateFr")+'</td></tr><tr><td class="left" style="width:auto">Heure</td><td class="right">'+$(target).attr("heure")+'</td></tr><tr><td class="left" style="width:auto">Durée</td><td class="right">'+$(target).attr("dure")+' '+$(target).attr("typeDuree")+'</td></tr><tr><td colspan="2"><p style="background-color: #899EB0; border: 1px solid #1E364E; text-align:center; border-radius: 0px 10px 0px 10px; color: #1E364E; font-size: 1em; font-weight: bold; adding:3px">Projet</p></td></tr><tr><td class="left" style="width:auto">Nom</td><td class="right">'+$(target).attr("nom")+'</td></tr><tr><td class="left" style="width:auto">Prénom</td><td class="right">'+$(target).attr("prenom")+'</td></tr><tr><td class="left" style="width:auto">Tel</td><td class="right">'+$(target).attr("tel")+'</td></tr><tr><td class="left" style="width:auto">Tel pour ce jour</td><td class="right">'+$(target).attr("telDuJour")+'</td></tr><tr><td class="left" style="width:auto">eMail</td><td class="right">'+$(target).attr("mail")+'</td></tr><tr><td class="left" style="width:auto">Catégorie</td><td class="right">'+$(target).attr("societe")+'</td></tr><tr><td colspan="2"><p style="background-color: #899EB0; border: 1px solid #1E364E; text-align:center; border-radius: 0px 10px 0px 10px; color: #1E364E; font-size: 1em; font-weight: bold; adding:3px">Forma2+</p></td></tr><tr><td class="left" style="width:auto">Responsable de formation&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right">'+$(target).attr("createur")+'</td></tr><tr><td class="left" style="width:auto">Créateur</td><td class="right">'+$(target).attr("createur")+'</td></tr><tr><td class="left" style="width:auto">&nbsp;</td><td class="right">&nbsp;</td></tr><tr style="text-align:right;"><td class="left" style="width:auto">&nbsp;</td><td class="right"><a title="Modifier l\'évènement" href="'+$(target).attr("url")+'"><input type="button" value="&nbsp;&nbsp;&nbsp;Modifier&nbsp;&nbsp;&nbsp;" class="boutonform" style="background-color:#8FA8C1;cursor:pointer;-moz-border-radius:5px 5px 5px 5xp;height:24px;padding:0 3px;vertical-align:middle;border:1px solid #1E364E;"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a title="Supprimer l\'évènement" href="#" onclick="deleteEvent('+$(target).attr("iEventId")+','+$(target).attr("date")+');"><input type="button" value="Supprimer" class="boutonform" style="background-color:#8FA8C1;cursor:pointer;-moz-border-radius:5px 5px 5px 5xp;height:24px;padding:0 3px;vertical-align:middle;border:1px solid #1E364E;"/></a></td></tr></table></div>';
			}
		});
	}

	function getSelectBoxTypeEvent(userId, cellClass) {
		$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:getListeTypeEvenementUilisateur", user:userId}, function(row){
			var htmlSelect = '<select id="evenement_iTypeEvenementId" class="text" name="evenement_iTypeEvenementId" style="float: left;font-size:1em;padding: 2px;width: 300px;border:1px solid #000000;height:20px;"><option value="0">---------------------------------Séléctionner---------------------------------</option>';
			var idTypeEvenementCourTelephone = {/literal}{$idTypeEvenementCourTelephone}{literal} ;
			for (i=0 ; i<row.length ; i++){	
				selected="";
				if (row[i].typeevenements_id == idTypeEvenementCourTelephone){
					selected="selected" ; 
				}
				htmlSelect += '<option value="'+row[i].typeevenements_id+'" '+selected+'>'+row[i].typeevenements_zLibelle+'</option>'; 
			}
			htmlSelect += '</select>';
			$("a."+cellClass).attr('typeEvent', htmlSelect);
		});	
	}
	function showSaisieRapidEvent(userId, cellClass){
		getSelectBoxTypeEvent(userId, cellClass) ;
		$("a."+cellClass).simpletooltip({ click: true, showEffect: "slideDown", hideEffect: "slideUp", hideOnLeave: false,
			customTooltip: function(target){
				afficherMasque(); 
				$('#iStagiaire').val(0);		
				$('.input_zStagiaire').val("");	
				$('#div-stagiaire-liste').hide();
				$('#evenement_zDescription').val("");
				return '<div id="tooltip" class="tooltip"><table border="0" cellspacing="0" cellpadding="0"><tr><td colspan="2"><p style="background-color: #899EB0; border: 1px solid #1E364E; text-align:center; border-radius: 0px 10px 0px 10px; color: #1E364E; font-size: 1em; font-weight: bold; padding:3px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ajouter un évènement rapidement&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td><td><a onclick="closeEventRapid();" rel="close" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="'+j_basepath+'design/front/images/design/pictos/close.png" border="0" /></a></td></tr><tr><td class="left" style="width:auto">&nbsp;</td></tr><tr><td class="left" style="width:auto">Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right">'+$(target).attr("zDate")+'</td></tr><tr><td class="left" style="width:auto">Heure :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right">'+$(target).attr("iTime")+'</td></tr><tr><td class="left" style="width:auto">Type d\'evenement :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right">'+$(target).attr("typeEvent")+'</td></tr>  <tr><td class="left" style="width:auto">Description:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right"><textarea style="width: 293px; margin-left:0; border: 1px solid; height: auto;" name="evenement_zDescription" id="evenement_zDescription"></textarea></td></tr>  <tr><td class="left" style="width:auto">Projet :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right"><input type="hidden" name="zDate" id="zDate" value="'+$(target).attr("zDate")+'" /><input type="hidden" name="iTime" id="iTime" value="'+$(target).attr("iTime")+'" /><input type="hidden" name="iStagiaire" id="iStagiaire" value="" /><input type="text" value="" id="evenement_zStagiaire" name="evenement_zStagiaire" class="text input_zStagiaire" style="border:1px solid #000000;height:20px;width:276px;">&nbsp;<img onclick="rechercherStagiaire();" src="'+j_basepath+'design/front/images/design/rechercher.png" alt="Ajouter un projet" style="cursor:pointer;"/></td></tr><tr class="affichageListeStagiaire" style="display:none;"><td class="left" style="width:auto">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="right"><p class="clear" id="div-stagiaire-liste"><label for="dtcm_event_project">&nbsp;</label><select style="width: 300px; border: 1px solid; margin-left: -4px; height: auto;" name="stagiaire-liste" id="stagiaire-liste" size="10" url=""><option></option></select></p></td></tr><td class="left" style="width:auto">&nbsp;</td><td class="right" style="text-align:right;"><a title="Enregistrer l\'évènement" href="#" onclick="#"><input type="button" onclick=\'saveEvent("'+$(target).attr("cellId")+'");return false;\' value="Enregistrer l\'évènement" class="boutonform" style="background-color:#8FA8C1;cursor:pointer;-moz-border-radius:5px 5px 5px 5xp;height:24px;padding:0 3px;vertical-align:middle;border:1px solid #1E364E;"/></a></td></tr></table></div>'
			}
		});
		return false ;
	}
	function deleteEvent (id, dates){
		if(confirm ("Etes vous sur de vouloir supprimer cet événement?"))
		{
			document.location.href=$("#urlDeleteEvent").val()+'&iEvenementId='+id;
		}
	}
	function rechercherStagiaire() {
		if ($('.input_zStagiaire').val() != '' && $('.input_zStagiaire').val().length > 2){
			$.getJSON(j_basepath + "index.php", {module:"evenement", action:"FoEvenement:autocompleteStagiaireAffectation", q:$('.input_zStagiaire').val()}, function(row){
				html = '';
				var selected = '';
				if (row.length > 0)
				{
					if (row.length < 10)
					{
						$('#stagiaire-liste').attr('size', row.length+2) ;
					}
					for (i=0 ; i<row.length ; i++)
					{	
						html += '<option ondblclick="getClient('+row[i].client_id+')" value="'+ row[i].client_id +'" '+ selected +'>'+ row[i].client_zNom + ' ' + row[i].client_zPrenom +'&nbsp;&nbsp;[' + row[i].client_zTel + ']'+'&nbsp;&nbsp;[' + row[i].societe_zNom + ']'+'&nbsp;&nbsp;[' + row[i].client_zVille + ']' +'</option>';
					}
					$('#stagiaire-liste').show();
					$('#stagiaire-liste').html(html);
					$('.affichageListeStagiaire').show();
				}else{
					alert("Aucun projet correspondant à votre critère!")
				}
			});
		}else{
			alert("Veuillez entrer au moins 3 caractères pour la recherche!")
		}
		//return false ;
	}
	function getClient (id){
		$.getJSON(j_basepath + "index.php", {module:"client", action:"FoClient:chargeParId", iStagiaireId:id}, function(datas){
			$('#iStagiaire').val(datas["client_id"]);
			$('.input_zStagiaire').val(datas["client_zNom"]+' '+datas["client_zPrenom"]);
			$('.affichageListeStagiaire').hide();
		});
	}

	function saveEvent(cellId) {
		if ($('#evenement_iTypeEvenementId').val() == 0)
		{
			alert("Veuillez selectionner le type d'évènement");
		}else{
			$.loader({width:340, height:39, content:'<img src="{/literal}{$j_basepath}design/front/images/design/loading14.gif"/>{literal}'});
			$.ajax({
				 type:"POST",
				 url:$('#urlSaveEvent').val(),
				 data:{	iTypeEvenementId:$('#evenement_iTypeEvenementId').val(), 
						iStagiaire:$('#iStagiaire').val(), 
						zDate:$('#zDate').val(), 
						iTime:$('#iTime').val(), 
						zDescription:$('#evenement_zDescription').val()
					 },
				 async:false,
				 success:function(resultat){
					$('td#'+cellId).html('');
					$('td#'+cellId).html(resultat);
					$('.tooltip').hide() ;
					closeEventRapid () ; 
				 }
			});
			$.loader('close') ;
		}
		return false ;
	}
	function closeEventRapid (){
		$('.tooltip').remove() ;
		$('.tooltip').hide() ;
		$('#masque').hide();
	}

	function getParamFromUrl (){
		var url = window.location.href ; 
		var turl = url.split ('?') ;
		if (turl.length > 1){
			if (turl[1].length > 1){
				var turl1 = turl[1].split('&') ;
				if (turl1.length > 1){
					for (i=0; i<turl1.length; i++)
					{
						if (turl1[i].split('=')[0] == 'date')
						{
							return turl1[i].split('=')[1] ;
						}
					}					
				}else{
					return "";
				}
			}else{
				return "";
			}
		}
	}
	function activetd(affichage, cellId){

		var contentUl = $('td#'+cellId).find('.divajouterEvent').find('ul.conge');
		$('td#'+cellId).find('.divajouterEvent').find('a.ajouterEventHebdo').hide();
		$('td#'+cellId).find('.divajouterEvent').find('a.actionEventHebdo').hide();
		$('td#'+cellId).find('.divajouterEvent').find('a.pasteEventHebdo').hide();
		$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.commentEventHebdo').hide();
		$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.copyEventHebdo').hide();
		$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.cutEventHebdo').hide();
		$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.deleteEventHebdo').hide();

		if (affichage == 1){
			$('td#'+cellId).addClass("activetd");
		}else{
			$('td#'+cellId).removeClass("activetd");
		}
		if (contentUl.length == 0 || contentUl.find('li').html() == " "){
			if ($('td#'+cellId).hasClass('activetd')){
				$('td#'+cellId).find('.divajouterEvent').find('a.ajouterEventHebdo').show();
				$('td#'+cellId).find('.divajouterEvent').find('a.actionEventHebdo').show();
				if ($('#showColler').val() == 1){
					$('td#'+cellId).find('.divajouterEvent').find('a.pasteEventHebdo').show();
				}
			}
		}else{
			if ($('td#'+cellId).hasClass('activetd')){
				$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.commentEventHebdo').show();
				$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.copyEventHebdo').show();
				$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.cutEventHebdo').show();
				$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.deleteEventHebdo').show();
			}else{
				$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.commentEventHebdo').hide();
				$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.copyEventHebdo').hide();
				$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.cutEventHebdo').hide();
				$('td#'+cellId).find('.divajouterEvent').find('ul.conge').find('a.deleteEventHebdo').hide();
			}
		}
	}
	function showModifComment(){
		$('.tooltip').empty() ;
		$("a.eventDesc").simpletooltip({ click: true, showEffect: "slideDown", hideEffect: "slideUp", hideOnLeave: false,
			customTooltip: function(target){
				afficherMasque(); 
				return '<div id="tooltip" class="tooltip"><table border="0" cellspacing="0" cellpadding="0"><tr><td colspan="2">&nbsp;</td><td><a onclick="closeEventRapid();" rel="close" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="'+j_basepath+'design/front/images/design/pictos/close.png" border="0" /></a></td></tr><tr><td class="left" style="width:auto">&nbsp;</td><td class="right"><textarea id="evenement_zDescription" name="evenement_zDescription" style="width: 293px; margin-left:0; border: 1px solid; height: 150px;">'+$(target).attr("desc")+'</textarea></td></tr><td class="left" style="width:auto">&nbsp;</td><td class="right" style="text-align:right;"><a title="Enregistrer l\'évènement" href="#" onclick="#"><input type="button" onclick=\'saveDesc('+$(target).attr("eventId")+', "'+$(target).attr("cellId")+'");return false;\' value="Enregistrer" class="boutonform" style="background-color:#8FA8C1;cursor:pointer;-moz-border-radius:5px 5px 5px 5xp;height:24px;padding:0 3px;vertical-align:middle;border:1px solid #1E364E;"/></a></td></tr></table></div>'
			}
		});
	}
</script>
{/literal}

<div class="main-page">
	<div class="inner-page">
		<!-- Header -->
		{$header}
		<div class="content">
			{$oZonePlanningSelection}
			<div class="blocplaning {if $iAffichage == 2}dayplan{/if} {if $iAffichage == 3}monthplan{/if} clear">
				{$oZonePlanning}
			</div>
		</div>
	</div>
</div>
<div id="loader" style="display:none;z-index:100000;"><img src="{$j_basepath}design/front/images/design/ajax-loader.gif"/></div>
<div id="masque" style="filter:Alpha(Opacity=10)">&nbsp;</div>