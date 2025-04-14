<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();

// Récupération de l'ID de réservation 
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id === 0) {
    die("ID de réservation invalide.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $client_nom = $_POST['client_nom'];
    $client_telephone = $_POST['client_telephone'];
    $client_email = $_POST['client_email'];
    $nombre_personnes = $_POST['nombre_personnes'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_depart = $_POST['date_depart'];
    $chambre_id = $_POST['chambre_id'];
    $client_id = $_POST['client_id'];

    // Mise à jour du client
    $stmtClient = $conn->prepare("UPDATE clients SET nom = ?, telephone = ?, email = ?, nombre_personnes = ? WHERE client_id = ?");
    $stmtClient->execute([$client_nom, $client_telephone, $client_email, $nombre_personnes, $client_id]);

    // Mise à jour de la réservation
    $stmtReservation = $conn->prepare("UPDATE reservations SET chambre_id = ?, date_arrivee = ?, date_depart = ? WHERE id = ?");
    $stmtReservation->execute([$chambre_id, $date_arrivee, $date_depart, $id]);

    header('Location: listReservations.php');
    exit();
}

// Récupération des données de la réservation actuelle
$stmt = $conn->prepare("SELECT r.*, c.* FROM reservations r JOIN clients c ON r.client_id = c.client_id WHERE r.id = ?");
$stmt->execute([$id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die("Réservation non trouvée.");
}

// Récupération des chambres pour le menu déroulant
$stmtChambres = $conn->query("SELECT chambre_id, num, capacité FROM chambres");
$chambres = $stmtChambres->fetchAll(PDO::FETCH_ASSOC);

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier une réservation</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Modifier la réservation</h1>

        <form method="POST">
            <input type="hidden" name="client_id" value="<?= $reservation['client_id'] ?>">

            <label>Nom :</label>
            <input type="text" name="client_nom" value="<?= htmlspecialchars($reservation['nom']) ?>" required>

            <label>Téléphone :</label>
            <input type="text" name="client_telephone" value="<?= htmlspecialchars($reservation['telephone']) ?>" required>

            <label>Email :</label>
            <input type="email" name="client_email" value="<?= htmlspecialchars($reservation['email']) ?>" required>

            <label>Nombre de personnes :</label>
            <input type="number" name="nombre_personnes" value="<?= $reservation['nombre_personnes'] ?>" required>

            <label>Date d'arrivée :</label>
            <input type="date" name="date_arrivee" value="<?= $reservation['date_arrivee'] ?>" required>

            <label>Date de départ :</label>
            <input type="date" name="date_depart" value="<?= $reservation['date_depart'] ?>" required>

            <label>Chambre :</label>
            <select name="chambre_id" required>
                <?php foreach ($chambres as $chambre): ?>
                    <option value="<?= $chambre['chambre_id'] ?>" <?= $reservation['chambre_id'] == $chambre['chambre_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($chambre['num']) ?> (<?= $chambre['capacité'] ?> pers.)
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
