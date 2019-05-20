jQuery(document).ready(function () {	
	jQuery.ajax({ url : "./api/getLigue", async: false}).done(function(content){

		var json = JSON.parse(content);
		var html = "";
		for (var i=0; i < json.length; i++) {
			html += "<option>"+json[i].ligue_num+" - "+json[i].ligue_nom+" - "+json[i].ligue_sport+"</option>"
		}
		jQuery("#inputState").append(html);
	});

	jQuery("#inputState").click(function() {
		cacherMessageErreur();
	});

	jQuery("#identifiant").keyup(function() {
		cacherMessageErreur();
	});

	jQuery("#motDePAsse").keyup(function() {
		cacherMessageErreur();
	});

	jQuery("#reMotDePasse").keyup(function() {
		cacherMessageErreur();
	});

	

	jQuery("#valideInscription").click(function() {
		var str = jQuery("#inputState").val().split("-", 1)[0];
		var responsable = "";
		if (jQuery("#groupe-label-responsable .active input").val() == "Oui") {
			responsable = 1;
		} else {
			responsable = 0;
		}

		if (jQuery("#identifiant").val().trim() == "" || jQuery("#motDePAsse").val().trim() == "" || jQuery("#reMotDePasse").val().trim() == "" ) {
			//! alert("Veuillez saisir tous les champs.");
			afficherMessageErreur("Veuillez saisir tous les champs.");
		} else if (jQuery("#motDePAsse").val() != jQuery("#reMotDePasse").val()) {
			//! alert("Veuillez saisir deux mots de passes identiques");
			afficherMessageErreur("Veuillez saisir deux mots de passes identiques.");
		} else if (jQuery("#inputState").val() == "Sélectionner une ligue") {
			//! alert("Veuillez selectionner une ligue");
			afficherMessageErreur("Veuillez selectionner une ligue.");
		} else {
			jQuery.ajax({
				url : "./index.php",
				async: false,
				method : "GET",
				data: {
					action : "inscription",
					utilisateur_identifiant : jQuery("#identifiant").val(),
					utilisateur_mdp :  jQuery("#motDePAsse").val(),
					utilisateur_reponsable : responsable,
					ligue_num : str
				}
			}).done(function(response) {
				var json = JSON.parse(response);
				console.log(json.response);
				if (json.response == "inscriptionReussi") {
					// window.location.href = "./index.php?action=connexion";
					jQuery("#modalValideInscription").modal('show');
				}
			});
		} 			
	});
})

function afficherMessageErreur(msg) {
	jQuery("#msgErreur p").html(msg);
	jQuery("#msgErreur").removeClass( "cacher" );
}

function cacherMessageErreur() {
	jQuery("#msgErreur p").html("");
	jQuery("#msgErreur").addClass( "cacher" );
}