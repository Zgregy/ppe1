<?php

    include_once "../modele/fonctionSQL.php";
    session_start();

    $json = array();

    $json['allSalles'] = afficheSalle($_POST['dateDebut'], $_POST['dateFin']);
    $json['ligueUser'] = $_SESSION['numLigue'];

    echo json_encode($json);
?>