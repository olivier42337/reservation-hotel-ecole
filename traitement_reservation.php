<?php
include_once 'connexion.php'; // Remplacez par le chemin vers votre script de connexion

$database = new Database();
$db = $database->getConnection();

// Vérifier si les données du formulaire sont présentes

/* NOTE : on utilise isset pour vérifier si c'est défini et empty pour vérifier si ce n'est pas vide */
if (isset($_POST['nom'], $_POST['email'], $_POST['hotelId'], $_POST['date_debut'], $_POST['date_fin'])) {
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $hotelId = $_POST['hotelId'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Recherche d'un client existant ou création d'un nouveau client
    $stmt = $db->prepare("SELECT id FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        $stmt = $db->prepare("INSERT INTO clients (nom, email) VALUES (?, ?)");
        $stmt->execute([$nom, $email]);
        $clientId = $db->lastInsertId();
    } else {
        $clientId = $client['id'];
    }

    // Recherche d'une chambre disponible
    // Recherche d'une chambre disponible
    $query = "SELECT id FROM chambres WHERE hotelId = :hotelId AND id NOT IN (
    SELECT chambreId FROM bookings 
    WHERE NOT (:date_debut >= fin OR :date_fin <= debut)
) LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':hotelId', $hotelId, PDO::PARAM_INT);
    $stmt->bindParam(':date_debut', $date_debut, PDO::PARAM_STR);
    $stmt->bindParam(':date_fin', $date_fin, PDO::PARAM_STR);
    $stmt->execute();
    $chambre = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($chambre) {
        echo "Votre réservation a été effectuée avec succès.";
        /*
    NOTE : manquant en ajustant également la colonne date_creation avec la date courante d'ajout

    $chambre = $chambre["id"];
    $stmt = $db->prepare("INSERT INTO bookings (debut, fin, clientId, chambreId) VALUES (?, ?, ?, ?)");
    $stmt->execute([$date_debut, $date_fin, $clientId, $chambreId]);
    */
    } else {
        echo "Aucune chambre disponible pour les dates sélectionnées.";
    }
} else {
    echo "Veuillez remplir tous les champs du formulaire.";
}
