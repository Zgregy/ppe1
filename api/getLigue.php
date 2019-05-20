<?php
include_once "../modele/fonctionSQL.php";

$json = array();
$ligue = getLigue();

$json = $ligue;

echo json_encode($json);