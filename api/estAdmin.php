<?php
/*
 * isAdmin content 0
 * isAdmin content 1
*/

// include_once "sql/fonctionSQL.php";

session_start();

// $id = $_SESSION['num'];
// $result = isAdmin($id);
$json["isAdmin"] = $_SESSION['admin'];
// echo json_encode($json);

?>