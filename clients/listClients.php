<?php
require_once '../auth/authFunctions.php';
initialiserSession();
requireRole('standard');

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
    <style>
        body {
            background: url('../fondClients.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: white;
        }
        .container {
            margin-top: 80px;
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
        }
        th, td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>

<?php include_once '../asset/navbar.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">Liste des Clients</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">✅ Client enregistré avec succès.</div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'has_reservations'): ?>
        <div class="alert alert-danger text-center">❌ Ce client a des réservations et ne peut pas être supprimé.</div>
    <?php endif; ?>

    <!-- Le bouton Ajouter un client ici -->
    <div class="text-end mb-3">
        <a href="createClients.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un client
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
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
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['client_id'] ?></td>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['telephone']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td>
                            <a href="editClient.php?id=<?= $client['client_id'] ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="deleteClient.php?id=<?= $client['client_id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer ce client ?');">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
