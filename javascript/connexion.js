jQuery(document).ready(function () { 

    jQuery("#identifiant").click(function() {
		cacherMessageErreur();
	});

	jQuery("#motDePasse").keyup(function() {
		cacherMessageErreur();
	});

	jQuery("#form-connect").submit(function (e) {
        e.preventDefault();
        console.log(jQuery("#identifiant").val());
        console.log(jQuery("#motDePasse").val());
		
		jQuery.ajax({
            url: "./index.php",
            async: false,
            method : "GET",
            data: {
                action: "connexion",
                utilisateur_identifiant : jQuery("#identifiant").val(),
                utilisateur_mdp : jQuery("#motDePasse").val()
            }
        }).done(function(response){ 
            var json = JSON.parse(response);
            console.log(json);

            if (json.response == "Mot de passe faux") {
                //! console.log("Utilisateur ou mot de passe incorrect");
                afficherMessageErreur("Utilisateur ou mot de passe incorrect");
            } else if (json.response == "Utilisateur connecté") {
                window.location.href = "./index.php?action=vueAfficheSalle";
            }

        });
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