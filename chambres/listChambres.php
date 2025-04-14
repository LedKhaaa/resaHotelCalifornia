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
</head>
<body>
    <h1>Liste des Chambres</h1>
    <a href="createChambre.php">Ajouter une chambre</a>
    <table border="1" style="width: 60%; min-width: 400px; margin: 0 auto;">
        <tr>
            <th>ID</th>
            <th>Numéro</th>
            <th>Capacité</th>
            <th>Actions</th>
        </tr>
        <?php foreach($chambres as $chambre): ?> 
        <tr>
            <td><?php echo $chambre['chambre_id']; ?></td> 
            <td><?= $chambre['num'] ?></td>
            <td><?= $chambre['capacité'] ?></td> 
            <td>
                <a href="editChambre.php?id=<?= $chambre['chambre_id'] ?>">Modifier</a>
                <a href="deleteChambre.php?id=<?= $chambre['chambre_id'] ?>" onclick="return confirm('Êtes-vous
sûr?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body> </html>