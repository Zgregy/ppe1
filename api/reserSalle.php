<?php

include_once "../modele/fonctionSQL.php";

$json = array();

    if (nouvelleReservation($_POST['numSalle'], $_POST['dateDemande'], $_POST['dateDebut'], $_POST['dateFin'], $_POST['ligueNum']) != 0) {
        $json['response'] = "ReservationValidé";
    } else {
        $json['response'] = "ReservationEchoué";
    }

echo json_encode($json);
?>