<?php

include_once "../modele/fonctionSQL.php";


if (modifSalle($_POST['numSalle'], $_POST['nomSalle'], $_POST['nbPlace'], $_POST['infoSalle']) == 0) {
    $json = "echecModification";
} else {
    $json = "modificationValide";
}

echo json_encode($json);
?>