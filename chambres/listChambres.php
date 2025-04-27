<?php
require_once '../auth/authFunctions.php';
initialiserSession();
requireRole('standard');

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
    <style>
        body {
            background: url('../fondChambre.png') no-repeat center center fixed;
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
    <h1 class="text-center mb-4">Liste des Chambres</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">✅ Chambre enregistrée avec succès.</div>
    <?php endif; ?>

    <!-- Bouton Ajouter une chambre -->
    <div class="text-end mb-3">
        <a href="createChambre.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter une chambre
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
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
                        <td><?= $chambre['chambre_id'] ?></td>
                        <td><?= htmlspecialchars($chambre['num']) ?></td>
                        <td><?= htmlspecialchars($chambre['capacité']) ?> personnes</td>
                        <td>
                            <a href="editChambre.php?id=<?= $chambre['chambre_id'] ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="deleteChambre.php?id=<?= $chambre['chambre_id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer cette chambre ?');">
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
