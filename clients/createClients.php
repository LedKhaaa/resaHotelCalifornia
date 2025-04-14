<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $nombre_personnes = (int)$_POST['nombre_personnes'];

    // Validation simple
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email est invalide.";
    }
    if ($nombre_personnes <= 0) {
        $errors[] = "Le nombre de personnes doit être supérieur à zéro.";
    }

    if (empty($errors)) {
        $conn = openDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO clients (nom, telephone, email, nombre_personnes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $telephone, $email, $nombre_personnes]);
        closeDatabaseConnection($conn);

        header("Location: listClients.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un client</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Ajouter un nouveau client</h1>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Nom :</label><br>
        <input type="text" name="nom" required><br><br>

        <label>Téléphone :</label><br>
        <input type="text" name="telephone" required><br><br>

        <label>Email :</label><br>
        <input type="email" name="email" required><br><br>

        <label>Nombre de personnes :</label><br>
        <input type="number" name="nombre_personnes" min="1" required><br><br>

        <button type="submit">Ajouter</button>
        <a href="listClients.php">Annuler</a>
    </form>
</body>
</html>
