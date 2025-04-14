<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM clients ORDER BY nom");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clients - Hôtel California</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include '../asset/navbar.php'; ?>

<div class="container mt-4">
    <h1 class="text-center">Liste des Clients</h1>
    <a href="createClients.php" class="btn btn-success my-3"><i class="bi bi-person-plus"></i> Ajouter un client</a>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'has_reservations'): ?>
        <div class="alert alert-danger text-center">Ce client a des réservations et ne peut pas être supprimé.</div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success text-center">Client supprimé avec succès.</div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] === '1'): ?>
        <div class="alert alert-success text-center">Client ajouté/modifié avec succès.</div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= $client['client_id']; ?></td>
                <td><?= htmlspecialchars($client['nom']); ?></td>
                <td><?= htmlspecialchars($client['email']); ?></td>
                <td>
                    <a href="editClient.php?id=<?= $client['client_id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                    <a href="deleteClient.php?id=<?= $client['client_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce client ?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
