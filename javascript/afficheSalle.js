var administrateur = '';
var userLigue = '';

jQuery(document).ready(function () {
    jQuery("#debutRecher").val(moment().format('YYYY-MM-DD'));
    jQuery("#finRecher").val(moment().format('YYYY-MM-DD'));

    demandeSallePArDate();

    jQuery("#salleByDate").click(function() {
        demandeSallePArDate();
    });

    jQuery("#imgCreationSalle").click(function() {
        jQuery("#modalCreationSalle").modal("show");
    });

    jQuery("#annuleCreationSalle").click(function() { 
        viderModal("modalCreationSalle");
    });

    jQuery("#annuleEditionSalle").click(function() { 
        viderModal("modalEditionSalle");
    });

    jQuery("#validEditionSalle").click(function() {
        modifiSalle();
    });

    jQuery("#validCreationSalle").click(function() {
        ajoutSalle()
    });
});

function modifiSalle() {
    if (jQuery("#modalEditionSalle #groupe-label-informatique .active").children().val() == "Non") {
        var informatique = 0;
    } else {
        var informatique = 1;
    }

    jQuery.confirm({
        title: 'Confirmation',
        content: 'Vous êtes sur le point de modifier la salle "'+jQuery("#modalEditionSalle #nomNouvelleSalle").val()+'". Voulez-vous continuer ?',
        buttons: {
            Oui: function () {
                jQuery.ajax({
                    type: 'POST',
                    async: false,
                    url: './api/modifSalle.php',
                    dataType: 'json', 
                    data: {
                        numSalle: jQuery("#modalEditionSalle #numSalle").html(),
                        nomSalle: jQuery("#modalEditionSalle #nomNouvelleSalle").val(),
                        nbPlace: jQuery("#modalEditionSalle #nbPlaceNouvelleSalle").val(),
                        infoSalle: informatique
                    }
                }).done(function(response) {
                    console.log(response);
                    if (response == "modificationValide") {
                        document.location.reload();
                    } else {
                        console.log("Echec de la mofication de la salle");
                    }
                });
            },
            Non: function () {
                jQuery.alert('Annulation de la modification de salle');
            }
        }
    });
}

function ajoutSalle() {

    if (jQuery("#modalCreationSalle  #groupe-label-informatique .active").children().val() == "Non") {
        var informatique = 0;
    } else {
        var informatique = 1;
    }

    jQuery.ajax({
        type: 'POST',
        async: false,
        url: './api/ajoutSalle.php',
        dataType: 'json', 
        data: {
            action : grfeiu,
            nomSalle: jQuery("#modalCreationSalle #nomNouvelleSalle").val(),
            nbPlace: jQuery("#modalCreationSalle #nbPlaceNouvelleSalle").val(),
            infoSalle: informatique
        }
    }).done(function(response) {
        // if (response == "creationValidé") {
            document.location.reload();
        // } else {
            // alert("Echec de la creation de la salle");
        // }
    });
}

function viderModal(idModal) {
    jQuery("#nomNouvelleSalle").val("");
    jQuery("#nbPlaceNouvelleSalle").val(0);
    jQuery("#groupe-label-informatique .active").removeClass("active")
    jQuery("#groupe-label-informatique").children().first().addClass("active")
    jQuery("#"+idModal).modal("hide");
}




function displaySalle(salles, administrateur) {
    //! console.log("entré dans la fonction \"displaySalle\" ?");
    console.log(salles);
    jQuery("#zoneSalle").html("");
    var html = "";
    if (administrateur == 1) {
        html +='<div class="col-md-4 mugnu">'
        html +='    <div class="salle">'
        html +='        <div class="row">'
        html +='            <div class="col-md-12 new-box">'
        html +='                <span title="Créer une salle">'
        html +='                        <img src="./images/img-add.png" id="imgCreationSalle" class="img-thumbnail border-picture size-img-add">'
        html +='                </span>'
        html +='            </div>'
        html +='        </div>'
        html +='    </div>'
        html +='</div>'
    }
    

    for (var i=0; i <salles.length; i++) {
        
        html +='<div class="col-md-4" id="salle_'+salles[i].numSalle+'">';
        html +='    <div class="salle">';
        html +='        <div class="row">';
        html +='            <div class="col-md-12 ">';
        if (salles[i].salleInformatique == 0 && salles[i].libre == 0) {
            html +='            <p class="type-salle titleCaseOccup">Salle basique</p>';
        } else if (salles[i].salleInformatique == 1 && salles[i].libre == 0) {
            html +='            <p class="type-salle titleCaseOccup">Salle informatique</p>';
        } else if (salles[i].salleInformatique == 1 && salles[i].libre == 1) {
            html +='            <p class="type-salle titleCaseLibre">Salle informatique</p>';
        } else if (salles[i].salleInformatique == 0 && salles[i].libre == 1) {
            html +='            <p class="type-salle titleCaseLibre">Salle basique</p>';
        }
        html +='                <div class="row ">';
        html +='                    <div class="col-md-12">';
        html +='                        <h3>'+salles[i].nomSalle+'</h3>';
        html +='                        <p>'+salles[i].placeSalle+' places</p>';
        html +='                        <div class="row justify-content-md-center mugnu">';

        if (administrateur == 1) {
            html +='                        <div class="col-md-auto">';
            html +='                            <i class="icon far fa-trash-alt" onclick="suppSalle('+salles[i].numSalle+')" title="Supprimer"></i>';
            html +='                        </div>';
            html +='                            <div class="col-md-auto">';
            html +='                                    <i class="icon fas fa-pen" onclick="EditionDeSalle('+salles[i].numSalle+', \''+salles[i].nomSalle+'\', '+salles[i].placeSalle+', '+salles[i].salleInformatique+')" title="Editer la salle"></i>';
            html +='                            </div>';
        }
        html +='                        </div>';
        if (salles[i].libre == 1) {
            html +='                        <div class="row justify-content-md-center">';
            html +='                            <div class="col-md-auto padding-top">';
            html +='                                    <i id="btn-reserv-salle-'+salles[i].numSalle+'" class="icon far fa-calendar-plus" onclick="reservationSalle('+salles[i].numSalle+', \''+salles[i].nomSalle+'\')" title="réserver"></i>';
            html +='                            </div>';
            html +='                        </div>';
        }
        html +='                    </div>';
        html +='                </div>';
        html +='            </div>';
        html +='        </div>';
        html +='    </div>';
        html +='</div>';    
    }
    jQuery("#zoneSalle").append(html);

}

function EditionDeSalle(numSalle, nomSalle, nbPlace, informatique) {
    viderModal("modalEditionSalle");
    jQuery("#modalEditionSalle").modal('show');
    jQuery("#modalEditionSalle #nomNouvelleSalle").val(nomSalle);
    jQuery("#modalEditionSalle #nbPlaceNouvelleSalle").val(nbPlace);
    jQuery("#modalEditionSalle #numSalle").html(numSalle);
    
    if (informatique == 0) {
        // jQuery("#groupe-label-informatique").children().first().removeClass("active")
        jQuery("#modalEditionSalle #informatisee-0").parent().addClass("active");
    } else {
        jQuery("#modalEditionSalle #informatisee-1").parent().addClass("active");
    }
}

function demandeSallePArDate() {
    jQuery.ajax({
        type: 'GET',
        async: false,
        url : "./index.php",
        dataType: 'json',
        data: {
            action : "afficheDesSalle",
            dateDebut: jQuery("#debutRecher").val(),
            dateFin: jQuery("#finRecher").val()
        }
    }).done(function(response){
        console.table(response);
        userLigue = response.ligueUser;
        displaySalle(response.allSalles, response.admin);

    });    
}

function suppSalle(idSalle) {
    jQuery.ajax({
        type: 'POST',
        async: false,
        url: './index.php?action=suppSalle',
        dataType: 'json', 
        data: {
            // action : "suppSalle",
            numSalle: idSalle
        }
    }).done(function(response) {
        if (response != "echecSuppression") {
            jQuery("#salle_"+idSalle+"").remove();
        }
    });
}

function reservationSalle(idSalle, nomSalle) {
    var dateDebut = moment(jQuery("#debutRecher").val()).locale('fr').format("L");
    var dateFin = moment(jQuery("#finRecher").val()).locale('fr').format('L');
    // confirm('Etes-vous sur de vouloir reserver la salle "'+nomSalle+'" pendant la durée du "'+dateDebut+'" au "'+dateFin+'" ?');
    jQuery.confirm({
        title : 'Réservation',
        content : 'Etes-vous sur de vouloir reserver la salle "'+nomSalle+'" pendant la durée du "'+dateDebut+'" au "'+dateFin+'" ?',
        buttons : {
            Annuler : function () {
                jQuery.alert('Votre demande de reservation a été annulé.');
            },
            Continuer : function () {
                jQuery.ajax({
                    type: 'GET',
                    async: false,
                    url: './index.php',
                    dataType: 'json', 
                    data: {
                        action : "reserverSalle",
                        numSalle: idSalle,
                        dateDemande: jQuery("#debutRecher").val(),
                        dateDebut: jQuery("#debutRecher").val(),
                        dateFin: jQuery("#finRecher").val(),
                        ligueNum: userLigue
                    }
                }).done(function(response) {
                    // console.log(response);
                    if (response === "ReservationValidé") {
                        jQuery("#salle_"+idSalle+" .type-salle").removeClass("titleCaseLibre").addClass("titleCaseOccup");
                        jQuery("#btn-reserv-salle-"+idSalle ).remove();
                    } else {
                        jQuery.alert({
                            title: 'Erreur !',
                            content: 'Une erreur est survenue, veuillez rééssayer ultérieurement.',
                        });
                    }
                });
            }
        }
    });
}