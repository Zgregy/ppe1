<?php

include_once "../modele/fonctionSQL.php";

$json = array();

$json = sallePourCalendrier();

echo json_encode($json);
?>