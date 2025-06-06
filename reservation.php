<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver une chambre</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Réserver une chambre</h1>
    <form action="traitement_reservation.php" method="post">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="hotelId">Hôtel:</label>
            <select id="hotelId" name="hotelId" required>
                <option value="">Sélectionnez un hôtel</option>
                <?php
                include_once 'connexion.php'; // Remplacez par le chemin vers votre script de connexion
                $database = new Database();
                $db = $database->getConnection();

                $query = "SELECT id, nom FROM hotels";
                foreach ($db->query($query) as $row) {
                    echo "<option value=\"{$row['id']}\">{$row['nom']}</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="date_debut">Date d'arrivée:</label>
            <input type="date" id="date_debut" name="date_debut" required>
        </div>
        <div>
            <label for="date_fin">Date de départ:</label>
            <input type="date" id="date_fin" name="date_fin" required>
        </div>
        <div>
            <input type="submit" value="Réserver">
        </div>
    </form>
</body>
</html>
