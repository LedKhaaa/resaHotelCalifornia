<?php
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    
    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO clients (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $telephone]);
    closeDatabaseConnection($conn);
    
    header("Location: listClients.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Ajouter un Client</title>
</head>
<body>
    <h1>Ajouter un Client</h1>
    <form method="post">
        <div>
            <label>Nom:</label>
            <input type="text" name="nom" required>
        </div>
        <div>
            <label>Prénom:</label>
            <input type="text" name="prenom" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Téléphone:</label>
            <input type="text" name="telephone" required>
        </div>
        <button type="submit">Enregistrer</button>
    </form>
    <a href="listClients.php">Retour à la liste</a>
</body>
</html>
