<?php
include_once "../modele/fonctionSQL.php";
$json = array();

if(!isset($_POST['utilisateur_identifiant']) && (!isset($_POST['utilisateur_mdp'])) && (!isset($_POST['utilisateur_reponsable'])) && (!isset($_POST['ligue_num']))) {
    $json['response'] = "champVide";
}else {
    inscription($_POST['utilisateur_identifiant'], $_POST['utilisateur_mdp'], $_POST['utilisateur_reponsable'], $_POST['ligue_num']);
    $json['response'] = "inscriptionReussi";
}
echo json_encode($json);