<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM clients ORDER BY nom"); 
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC); 
closeDatabaseConnection($conn);
?>

<!DOCTYPE html> 
<html>
<head>
    <title>Liste des Clients</title> 
</head>
<body>
    <h1>Liste des Clients</h1>
    <a href="createClient.php">Ajouter un client</a>
    <table border="1" style="width: 60%; min-width: 400px; margin: 0 auto;">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach($clients as $client): ?> 
        <tr>
            <td><?= $client['client_id']; ?></td> 
            <td><?= htmlspecialchars($client['nom']); ?></td>
            <td><?= htmlspecialchars($client['email']); ?></td> 
            <td>
                <a href="editClient.php?id=<?= $client['client_id'] ?>">Modifier</a>
                <a href="deleteClient.php?id=<?= $client['client_id'] ?>" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>