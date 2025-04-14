<?php
require_once '../config/db_connect.php';

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
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
    <meta charset="UTF-8">
    <title>Réservations - Hôtel California</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include '../asset/navbar.php'; ?>

<div class="container mt-4">
    <h1 class="text-center">Liste des Réservations</h1>
    <a href="createReservation.php" class="btn btn-success my-3"><i class="bi bi-calendar-plus"></i> Nouvelle Réservation</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
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
        <?php foreach ($reservations as $reservation): ?>
            <?php
                $today = date('Y-m-d');
                if ($reservation['date_depart'] < $today) {
                    $statut = 'Terminée';
                    $class = 'text-secondary';
                } elseif ($reservation['date_arrivee'] <= $today && $reservation['date_depart'] >= $today) {
                    $statut = 'En cours';
                    $class = 'text-success';
                } else {
                    $statut = 'À venir';
                    $class = 'text-primary';
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
                <td class="<?= $class ?> fw-bold"><?= $statut ?></td>
                <td>
                    <a href="editReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                    <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette réservation ?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
