<?php
    include_once "modele/fonctionSQL.php";

    session_start();

    $json = array();
    

    // if(!isset($_GET['action'])) {
    //     $_GET['action']="connexion";
    // }

    switch($_GET['action']) {
        case "vueInscription": vues("inscription"); break;                                                                      // Affiche la page d'inscription.
        case "vueConnexion": vues("connexion"); break;                                                                          // Affiche la page de connexion.
        case "vueAfficheSalle": if (estConnecte()) { vues("affichageDeSalle");} else { vues("connexion"); } break;              // Affiche de reservation de salle.
        case "vueGestionUtilisateur": if (estConnecte()) { vues("gestionUtilisateur");} else { vues("connexion"); } break;      // Affiche la page de gestion des droits utilisateur.
        case "vueCalendrier": if (estConnecte()) { vues("calendrier");} else { vues("connexion"); } break;                      // Affiche la page de calendrier.

        case "inscription": // Formulaire d'ajout d'un nouvel utilisateur.
            if(!isset($_GET['utilisateur_identifiant']) && (!isset($_GET['utilisateur_mdp'])) && (!isset($_GET['utilisateur_reponsable'])) && (!isset($_GET['ligue_num']))) {
                $json['response'] = "champVide";
            }else {
                $options = [
                    'cost' => 12,
                ];
                $password = password_hash($_GET['utilisateur_mdp'], PASSWORD_BCRYPT, $options);
                inscription($_GET['utilisateur_identifiant'], $password, $_GET['utilisateur_reponsable'], $_GET['ligue_num']);
                $json['response'] = "inscriptionReussi";
            }
        break;
        
        case "connexion": // Connecte un utilisateur.
            if(!isset($_GET['$utilisateur_identifiant']) && (!isset($_GET['utilisateur_mdp']))){
                $json['response'] = "Informations manquantes";
            }else {
                $password = $_GET['utilisateur_mdp'];
                $dbpassword = verifMdpAvecIdentifiant($_GET['utilisateur_identifiant']);
                if($password != $dbpassword) {
                    $json['response'] = "Mot de passe faux";
                }else {
                    //! connecter
                    $json['response'] = "Utilisateur connecté";
                    $results = recupInfoUser($_GET['utilisateur_identifiant']);

                    $_SESSION['num'] = $results[0]['utilisateur_num'];
                    $_SESSION['identifiant'] = $results[0]['utilisateur_identifiant'];
                    $_SESSION['admin'] = $results[0]['utilisateur_admin'];
                    $_SESSION['responsable'] = $results[0]['utilisateur_responssable'];
                    $_SESSION['numLigue'] = $results[0]['ligue_num'];
                }
            }
        break;

        case "afficheDesSalle": // Affiche les salles dans les tranches horaires.
            $json['allSalles'] = afficheSalle($_GET['dateDebut'], $_GET['dateFin']);
            $json['ligueUser'] = $_SESSION['numLigue'];
            if ($_SESSION['admin'] == 1) {
                $json['admin'] = $_SESSION['admin'];
            } 
        break;

        case "suppSalle": // Supprime une salle.
            if ($_SESSION['admin']) {
                if(!isset($_GET['numSalle'])) {
                    $json['response'] = "emptyFields";
                }else {
                    if (suppressionSalle($_GET['numSalle']) != 0) {
                        $json['response'] = "suppressionValidé";
                    } else {
                        $json['response'] = "echecSuppression";
                    }
                }
            } else {
                $json['response'] = "pasLesDroits";
            }
        break;

        case "ajouterSalle": // Ajoute une salle.
            if (estAdmin()) {
                if (ajouterSalle($_GET['nomSalle'], $_GET['nbPlace'], $_GET['infoSalle']) != 0) {
                    $json['response'] = "creationValidé";
                } else {
                    $json['response'] = "creationEchoue";
                }
            } else {
                $json['response'] = "pasLesDroits";
            }
        break;
        
        case "modifierSalle": // Formulaire de modification de salle.
            if (estAdmin()) {   
                if (modifSalle($_GET['numSalle'], $_GET['nomSalle'], $_GET['nbPlace'], $_GET['infoSalle']) == 0) {
                    $json = "echecModification";
                } else {
                    $json = "modificationValide";
                }
            } else {
                $json['response'] = "pasLesDroits";
            }

        break;
        
        case "reserverSalle": // Réservation d'une salle.
            if (nouvelleReservation($_GET['numSalle'], $_GET['dateDemande'], $_GET['dateDebut'], $_GET['dateFin'], $_GET['ligueNum']) != 0) {
                $json['response'] = "ReservationValidé";
            } else {
                $json['response'] = "ReservationEchoué";
            }
        break;
        
        case "calendrier": // Affiche les reservations pour le calendrier.
            $json = sallePourCalendrier();
        break;

        case "btnglobal":
            if (estconnecte()) {
                $json = '<div class="offset-md-2 text-center"><input type="submit" id="btnDeconnexion" value="Déconnexion" class="wpcf7-form-control wpcf7-submit btn btn-primary" onclick="disconnect()"></div>';
            } else {

            }
        break;
        
        case "deconnexion":
            $json = session_destroy();
        break;

        default:
        if (estConnecte() == true) {
            header("Location: index.php?action=vueAfficheSalle");  //Page par défautsi un utilisateur est connecté
        } else {
            header("Location: index.php?action=vueConnexion");  //Page par défautsi un utilisateur n'est pas connecté
        }
    }
    if ($json != null) {
        echo json_encode($json);
    }

    function vues($vue) {
        include("vue/header.html");    
        include("vue/".$vue.".html");
        include("vue/footer.html");
    }

    function estAdmin() {
        return $_SESSION['admin'] == 1;
    }
    
    function estConnecte() {
        return isset($_SESSION['identifiant']);
    }
?>