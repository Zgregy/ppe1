<?php

function connectionSQL(){
    include_once "confSQL.php";
    try {
        $bdd = new PDO("mysql:host=".HOST.";dbname=".DATABASE.";port=".PORT.";charset=utf8", USER, PASSWORD);
    }catch( Exception $e){
        die('erreur : impossible de se connecter à la bdd');

    }
    return $bdd;
}

function afficheSalle($debut, $fin) {
    $bdd = connectionSQL();
    $results =null;

    $query = ' SELECT salle.salle_num, salle_nom, salle_places, salle_informatise, 0 as libre' 
            .' FROM salle JOIN reservation ON reservation.salle_num = salle.salle_num '
            .' WHERE reservation_fin >= "'.$debut.'" AND reservation_debut <= "'.$fin.'"' 
            .' AND salle_verr IS null'
            .' UNION SELECT salle.salle_num, salle_nom, salle_places, salle_informatise, 1 as libre '
            .' FROM salle'
            .' WHERE `salle_num` NOT IN ('
            .'    SELECT salle.salle_num'
            .'    FROM salle JOIN reservation ON reservation.salle_num = salle.salle_num '
            .'    WHERE reservation_fin >= "'.$debut.'" AND reservation_debut <= "'.$fin.'"'
            .'    AND salle_verr IS null'
            .' )'
            .' AND salle_verr IS null';
    // echo $query;

    $request = $bdd->prepare($query);
    $request->execute();
    $request->bindColumn(1, $numSalle);
    $request->bindColumn(2, $nomSalle);
    $request->bindColumn(3, $placeSalle);
    $request->bindColumn(4, $salleInformatique);
    $request->bindColumn(5, $libre);
    while ($request->fetch()) {
        $data['numSalle'] = $numSalle;
        $data['nomSalle'] = $nomSalle;
        $data['placeSalle'] = $placeSalle;
        $data['salleInformatique'] = $salleInformatique;
        $data['libre'] = $libre;
        $results[] = $data;
    }
    $bdd = null;
    return $results;
}

function sallePourCalendrier() {
    $bdd = connectionSQL();
    $results =null;

    $query = ' SELECT salle.salle_nom, ligue.ligue_nom, reservation_debut, reservation_fin'
            .' FROM reservation'
            .' LEFT JOIN salle ON reservation.salle_num = salle.salle_num'
            .' LEFT JOIN ligue ON reservation.ligue_num = ligue.ligue_num';
    // echo $query;
    $request = $bdd->prepare($query);
    $request->execute();
    $request->bindColumn(1, $nomSalle);
    $request->bindColumn(2, $nomLigue);
    $request->bindColumn(3, $debutReservation);
    $request->bindColumn(4, $finReservation);
    while ($request->fetch()) {
        $data['title'] = $nomSalle." - ".$nomLigue;
        // $data['nomLigue'] = $nomLigue;
        $data['start'] = $debutReservation;
        $data['end'] = $finReservation;
        $results[] = $data;
    }
    $bdd = null;
    return $results;
}

function suppressionSalle($numSalle) {
    $bdd = connectionSQL();
    $results =null;
    
    $query = "UPDATE salle SET salle_verr = NOW() WHERE salle_num =".$numSalle;
    
    $request = $bdd->prepare($query);
    $request->execute();
    $results = $request->fetchAll();

    $bdd = null;
    return $results;
}

function modifSalle($numSalle, $nomSalle, $nbPlace, $infoSalle) {
    $bdd = connectionSQL();
    $results =null;
    
    $query = 'UPDATE salle SET salle_nom = "'.$nomSalle.'", salle_places = "'.$nbPlace.'", salle_informatise = "'.$infoSalle.'" WHERE salle_num = '.$numSalle;

    $request = $bdd->prepare($query);
    $request->execute();
    $results = $request->rowCount();

    $bdd = null;
    return $results;
}

function ajouterSalle($nomSalle, $nbPlace, $infoSalle) {
    $bdd = connectionSQL();
    $results =null;
    
    $query = 'INSERT INTO salle(salle_nom, salle_places, salle_informatise) VALUES ("'.$nomSalle.'", '.$nbPlace.', '.$infoSalle.')';

    $request = $bdd->prepare($query);
    $request->execute();
    $results = $request->rowCount();

    $bdd = null;
    return $results;
}

function nouvelleReservation($numSalle, $dateDemande, $dateDebut, $dateFin, $ligueNum) {
    $bdd = connectionSQL();
    $results =null;
    
    $query = "INSERT INTO reservation(reservation_demande, reservation_debut, reservation_fin, ligue_num, salle_num) VALUES (:dateDemande, :dateDebut, :dateFin, :ligueNum, :numSalle)";
    $request = $bdd->prepare($query);
    $request->bindParam(':dateDemande', $dateDemande);
    $request->bindParam(':dateDebut', $dateDebut);
    $request->bindParam(':dateFin', $dateFin);
    $request->bindParam(':ligueNum', $ligueNum);
    $request->bindParam(':numSalle', $numSalle);
    $request->execute();
    $lastId = $bdd->lastInsertId();
    $results = $request->fetchAll();
    return results
}

function getLigue() {
    $bdd = connectionSQL();

    // $query = "SELECT * FROM ligue";
    $query = "SELECT ligue_nom, ligue_num, ligue_sport FROM ligue WHERE ligue.ligue_verr is null";

    $request = $bdd->prepare($query);
    $request->execute();
    $results = $request->fetchAll();

    $bdd = null;
    return $results;
}

function inscription($identifiant, $mdp, $responsable, $numLigue) {
    $bdd = connectionSQL();
    $results = null;

    $query = 'INSERT INTO utilisateur(utilisateur_identifiant, utilisateur_mdp, utilisateur_admin, utilisateur_responssable, ligue_num) VALUES ("'.$identifiant.'", "'.$mdp.'", 0, '.$responsable.', '.$numLigue.')';

    $request = $bdd->prepare($query);
    $request->execute();
    $results = $request->fetchAll();

    $bdd = null;
    return $results;
}

function recupInfoUser($identifiant){
    $bdd = connectionSQL();
    $results = null;

    $request = $bdd->prepare("SELECT utilisateur_num, utilisateur_identifiant, utilisateur_admin, utilisateur_responssable, ligue_num FROM utilisateur WHERE utilisateur_identifiant like :identifiant ");
    $request->bindParam(':identifiant', $identifiant);
    $request->execute();
    $results = $request->fetchAll();

    // echo $results;

    $bdd = null;
    return $results;
}

function verifMdpAvecIdentifiant($identifiant){
    $bdd = connectionSQL();
    $request = $bdd->prepare("SELECT utilisateur_mdp FROM utilisateur WHERE utilisateur_identifiant = :identifiant");
    if ($request->execute(array(':identifiant' => $identifiant)) && $row = $request->fetch()) {
        $password = $row['utilisateur_mdp'];
        return $password;
    }
    $bdd = null;
}

function listeProduit() {
    $bdd = connectionSQL();
    $results =null;

    $query = ' SELECT id, nom FROM equipement';

    $request = $bdd->prepare($query);
    $request->execute();
    $request->bindColumn(1, $id);
    $request->bindColumn(2, $nomEquipement);
    while ($request->fetch()) {
        $data['idEquipement'] = $id;
        $data['nomEquipement'] = $nomEquipement;
        $results[] = $data;
    }
    $bdd = null;
    return $results;
}