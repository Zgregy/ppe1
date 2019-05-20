<?php
/*
 * connected contenu in
 * connected contenu out
 */
if ( empty($_SESSION) ) {
	session_start();
}

// echo $_SESSION['id'];

if ( isset($_SESSION['num']) ) {
	$json["connected"] = 'in';
} else {
    $json["connected"] = 'out';
    
}

echo json_encode($json, JSON_UNESCAPED_UNICODE);
?>