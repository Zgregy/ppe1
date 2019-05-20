<?php

include_once "../modele/fonctionSQL.php";

$json = array();

if(!isset($_POST['numSalle'])) {
    $json['response'] = "emptyFields";
}else {
    $numSalle = $_POST['numSalle'];

    if (suppressionSalle($numSalle) != 0) {
        $json['response'] = "suppressionValidé";
    } else {
        $json['response'] = "echecSuppression";
    }
}

echo json_encode($json);
?>