<?php
include_once "../modele/fonctionSQL.php";

$json = array();

if (isset($_POST['utilisateur_identifiant'])){
    $json['content'] = $_POST['utilisateur_identifiant'];
}else {
    $json['content'] = "Pas d'indentifiant";
}

if(!isset($_POST['$utilisateur_identifiant']) && (!isset($_POST['utilisateur_mdp']))){
    $json['response'] = "Informations manquantes";
}else {
    $identifiant = $_POST['utilisateur_identifiant'];
    $password = $_POST['utilisateur_mdp'];
    $dbpassword = verifMdpAvecIdentifiant($identifiant);
    if($password != $dbpassword) {
        $json['response'] = "Mot de passe faux";
    }else {
        //! connecter
        $json['response'] = "Utilisateur connecté";
        session_start();
        $results = recuperationInfosUtilisateurViaIdentifiant($identifiant);

        foreach ($results as $result){
            $_SESSION['num'] = $result['utilisateur_num'];
            $_SESSION['identifiant'] = $result['utilisateur_identifiant'];
            $_SESSION['admin'] = $result['utilisateur_admin'];
            $_SESSION['responsable'] = $result['utilisateur_responssable'];
            $_SESSION['numLigue'] = $result['ligue_num'];

        }
    }
}

echo json_encode($json);