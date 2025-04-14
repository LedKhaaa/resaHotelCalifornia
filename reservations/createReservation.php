<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_nom = $_POST['client_nom'];
    $client_telephone = $_POST['client_telephone'];
    $client_email = $_POST['client_email'];
    $nombre_personnes = $_POST['nombre_personnes'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_depart = $_POST['date_depart'];
    $chambre_id = $_POST['chambre_id'];

    // Insérer les informations client
    $stmtClient = $conn->prepare("INSERT INTO clients (nom, telephone, email, nombre_personnes) VALUES (?, ?, ?, ?)");
    $stmtClient->execute([$client_nom, $client_telephone, $client_email, $nombre_personnes]);
    $client_id = $conn->lastInsertId();

    // Insérer la réservation
    $stmtReservation = $conn->prepare("INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES (?, ?, ?, ?)");
    $stmtReservation->execute([$client_id, $chambre_id, $date_arrivee, $date_depart]);

    header('Location: listReservations.php');
    exit();
}

// Récupérer les chambres disponibles
$stmtChambres = $conn->query("SELECT chambre_id, num, capacité FROM chambres");
$chambres = $stmtChambres->fetchAll(PDO::FETCH_ASSOC);

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Créer une réservation</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Créer une nouvelle réservation</h1>

        <form method="POST">
            <label>Nom du client :</label>
            <input type="text" name="client_nom" required>

            <label>Téléphone :</label>
            <input type="text" name="client_telephone" required>

            <label>Email :</label>
            <input type="email" name="client_email" required>

            <label>Nombre de personnes :</label>
            <input type="number" name="nombre_personnes" required>

            <label>Date d'arrivée :</label>
            <input type="date" name="date_arrivee" required>

            <label>Date de départ :</label>
            <input type="date" name="date_depart" required>

            <label>Chambre :</label>
            <select name="chambre_id" required>
                <?php foreach ($chambres as $chambre): ?>
                    <option value="<?= $chambre['chambre_id'] ?>">
                        <?= htmlspecialchars($chambre['num']) ?> (<?= $chambre['capacité'] ?> pers.)
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-success">Créer</button>
            <a href="reservations.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>