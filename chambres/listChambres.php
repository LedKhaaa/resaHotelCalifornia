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
    <title>Liste des Chambres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include_once '../asset/gestionMessage.php'; ?> 
<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Liste des Chambres</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">✅ Chambre enregistrée avec succès.</div>
    <?php endif; ?>

    <div class="mb-3 text-end">
        <a href="createChambre.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter une chambre
        </a>
    </div>

    <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Numéro</th>
                <th>Capacité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($chambres) > 0): ?>
                <?php foreach ($chambres as $chambre): ?>
                    <tr>
                        <td><?= $chambre['chambre_id'] ?></td>
                        <td><?= htmlspecialchars($chambre['num']) ?></td>
                        <td><?= $chambre['capacité'] ?> personnes</td>
                        <td>
                            <a href="editChambre.php?id=<?= $chambre['chambre_id'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="deleteChambre.php?id=<?= $chambre['chambre_id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer cette chambre ?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucune chambre trouvée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
