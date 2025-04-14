<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = (int)$_POST['capacite'];

    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    }

    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    if (empty($errors)) {
        $conn = openDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO chambres (num, capacité) VALUES (?, ?)");
        $stmt->execute([$numero, $capacite]);
        closeDatabaseConnection($conn);

        header("Location: listChambres.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une Chambre</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Ajouter une Chambre</h1>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Numéro de Chambre :</label><br>
        <input type="text" name="numero" required><br><br>

        <label>Capacité :</label><br>
        <input type="number" name="capacite" min="1" required><br><br>

        <button type="submit">Ajouter</button>
        <a href="listChambres.php">Annuler</a>
    </form>
</body>
</html>
