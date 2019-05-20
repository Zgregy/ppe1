<?php

include_once "../modele/fonctionSQL.php";

$json = array();


if (ajouterSalle($_POST['nomSalle'], $_POST['nbPlace'], $_POST['infoSalle']) != 0) {
    $json['response'] = "creationValidé";
} else {
    $json['response'] = "creationEchoue";
}


echo json_encode($json);
?>