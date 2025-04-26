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
    <title>Liste des Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include_once '../asset/gestionMessage.php'; ?> 
<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Liste des Clients</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">✅ Client enregistré avec succès.</div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'has_reservations'): ?>
        <div class="alert alert-danger text-center">❌ Ce client a des réservations et ne peut pas être supprimé.</div>
    <?php endif; ?>

    <div class="mb-3 text-end">
        <a href="createClients.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un client
        </a>
    </div>

    <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clients) > 0): ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['client_id'] ?></td>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['telephone']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td>
                            <a href="editClient.php?id=<?= $client['client_id'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="deleteClient.php?id=<?= $client['client_id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer ce client ?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Aucun client trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
