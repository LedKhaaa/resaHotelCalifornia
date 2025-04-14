<?php
require_once '../config/db_connect.php';

function formatDate($date) {
    $timestamp = strtotime($date); 
    return date('d/m/Y', $timestamp);
}

$conn = openDatabaseConnection();
$query = "SELECT r.id, r.date_arrivee, r.date_depart,
                 c.nom AS client_nom, c.telephone AS client_telephone, c.email AS client_email,
                 c.nombre_personnes,
                 ch.num AS chambre_numero, ch.capacité AS chambre_capacite
          FROM reservations r
          JOIN clients c ON r.client_id = c.client_id
          JOIN chambres ch ON r.chambre_id = ch.chambre_id 
          ORDER BY r.date_arrivee DESC";

$stmt = $conn->query($query);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC); 
closeDatabaseConnection($conn);
?>

<!DOCTYPE html> 
<html lang="fr"> 
<head>
    <title>Liste des Réservations</title>
    <meta charset="UTF-8">
</head> 
<body>

    <!-- NAVBAR -->
    <?php include '../includes/navbar.php'; ?>

    <h1>Liste des Réservations</h1>

    <div class="actions">
        <a href="createReservation.php">Nouvelle Réservation</a>
    </div>

    <table border="1" style="width: 90%; margin: 0 auto;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Contact</th>
                <th>Chambre</th>
                <th>Personnes</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($reservations) > 0): ?>
                <?php foreach($reservations as $reservation): ?>
                    <?php
                        $aujourd_hui = date('Y-m-d');
                        if ($reservation['date_depart'] < $aujourd_hui) {
                            $statut = 'Terminée';
                        } elseif ($reservation['date_arrivee'] <= $aujourd_hui && $reservation['date_depart'] >= $aujourd_hui) {
                            $statut = 'En cours';
                        } else {
                            $statut = 'À venir';
                        }
                    ?>
                    <tr>
                        <td><?= $reservation['id'] ?></td>
                        <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                        <td>
                            <strong>Tél:</strong> <?= htmlspecialchars($reservation['client_telephone']) ?><br>
                            <strong>Email:</strong> <?= htmlspecialchars($reservation['client_email']) ?>
                        </td>
                        <td>N° <?= htmlspecialchars($reservation['chambre_numero']) ?> (<?= $reservation['chambre_capacite'] ?> pers.)</td>
                        <td><?= $reservation['nombre_personnes'] ?></td>
                        <td><?= formatDate($reservation['date_arrivee']) ?></td>
                        <td><?= formatDate($reservation['date_depart']) ?></td>
                        <td><?= $statut ?></td>
                        <td>
                            <a href="viewReservation.php?id=<?= $reservation['id'] ?>">Voir</a>
                            <a href="editReservation.php?id=<?= $reservation['id'] ?>">Modifier</a>
                            <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" onclick="return confirm('Supprimer cette réservation ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">Aucune réservation trouvée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body> 
</html>
