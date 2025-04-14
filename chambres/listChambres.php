<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM chambres ORDER BY num");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chambres - Hôtel California</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include '../asset/navbar.php'; ?>

<div class="container mt-4">
    <h1 class="text-center">Liste des Chambres</h1>
    <a href="createChambre.php" class="btn btn-success my-3"><i class="bi bi-plus-square"></i> Ajouter une chambre</a>

    <?php if (isset($_GET['success']) && $_GET['success'] === '1'): ?>
        <div class="alert alert-success text-center">Chambre ajoutée/modifiée avec succès.</div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success text-center">Chambre supprimée avec succès.</div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Numéro</th>
                <th>Capacité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($chambres as $chambre): ?>
            <tr>
                <td><?= $chambre['chambre_id']; ?></td>
                <td><?= htmlspecialchars($chambre['num']) ?></td>
                <td><?= $chambre['capacité'] ?></td>
                <td>
                    <a href="editChambre.php?id=<?= $chambre['chambre_id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                    <a href="deleteChambre.php?id=<?= $chambre['chambre_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette chambre ?')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
