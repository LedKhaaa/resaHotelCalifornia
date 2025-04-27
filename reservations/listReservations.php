<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
if (!hasRole("standard")) {
    $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.");
    header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage"); 
    exit;
}
$conn = openDatabaseConnection();

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

$query = "SELECT r.id, r.date_arrivee, r.date_depart, r.nombre_personnes,
                 c.nom AS client_nom, c.telephone AS client_telephone, c.email AS client_email,
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
    <title>Liste des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include_once '../asset/gestionMessage.php'; ?> 
<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Liste des Réservations</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">✅ Réservation enregistrée avec succès.</div>
    <?php endif; ?>

    <div class="mb-3 text-end">
        <a href="createReservation.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nouvelle Réservation
        </a>
    </div>

    <table class="table table-bordered table-striped align-middle text-center">
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
            <?php if (count($reservations) > 0): ?>
                <?php foreach ($reservations as $reservation): ?>
                    <?php
                        $aujourd_hui = date('Y-m-d');
                        $statut_class = '';
                        $statut = '';

                        if ($reservation['date_depart'] < $aujourd_hui) {
                            $statut_class = 'table-secondary';
                            $statut = 'Terminée';
                        } elseif ($reservation['date_arrivee'] <= $aujourd_hui && $reservation['date_depart'] >= $aujourd_hui) {
                            $statut_class = 'table-success';
                            $statut = 'En cours';
                        } else {
                            $statut_class = 'table-warning';
                            $statut = 'À venir';
                        }
                    ?>
                    <tr class="<?= $statut_class ?>">
                        <td><?= $reservation['id'] ?></td>
                        <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                        <td>
                            <small><strong>Tél:</strong> <?= htmlspecialchars($reservation['client_telephone']) ?><br>
                            <strong>Email:</strong> <?= htmlspecialchars($reservation['client_email']) ?></small>
                        </td>
                        <td>N° <?= htmlspecialchars($reservation['chambre_numero']) ?> (<?= $reservation['chambre_capacite'] ?> pers.)</td>
                        <td><?= $reservation['nombre_personnes'] ?></td>
                        <td><?= formatDate($reservation['date_arrivee']) ?></td>
                        <td><?= formatDate($reservation['date_depart']) ?></td>
                        <td><strong><?= $statut ?></strong></td>
                        <td>
                            <a href="editReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer cette réservation ?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Aucune réservation trouvée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
