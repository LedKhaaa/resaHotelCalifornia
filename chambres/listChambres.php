<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM chambres ORDER BY num"); 
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC); 
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des Chambres</title>
    <meta charset="UTF-8">
</head>
<body>

    <!-- NAVBAR -->
    <?php include '../includes/navbar.php'; ?>

    <h1>Liste des Chambres</h1>
    <a href="createChambre.php">Ajouter une chambre</a>

    <?php if (isset($_GET['success']) && $_GET['success'] === '1'): ?>
        <p style="color: green; text-align: center;">Chambre ajoutée/modifiée avec succès.</p>
    <?php elseif (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <p style="color: green; text-align: center;">Chambre supprimée avec succès.</p>
    <?php endif; ?>

    <table border="1" style="width: 60%; min-width: 400px; margin: 0 auto;">
        <tr>
            <th>ID</th>
            <th>Numéro</th>
            <th>Capacité</th>
            <th>Actions</th>
        </tr>
        <?php foreach($chambres as $chambre): ?> 
        <tr>
            <td><?= $chambre['chambre_id']; ?></td> 
            <td><?= $chambre['num'] ?></td>
            <td><?= $chambre['capacité'] ?></td> 
            <td>
                <a href="editChambre.php?id=<?= $chambre['chambre_id'] ?>">Modifier</a>
                <a href="deleteChambre.php?id=<?= $chambre['chambre_id'] ?>" onclick="return confirm('Supprimer cette chambre ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
