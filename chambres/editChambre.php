<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: listChambres.php");
    exit;
}

$conn = openDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = (int)$_POST['capacite'];

    $errors = [];

    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    }

    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE chambres SET num = ?, capacité = ? WHERE chambre_id = ?");
        $stmt->execute([$numero, $capacite, $id]);

        header("Location: listChambres.php?success=1");
        exit;
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM chambres WHERE chambre_id = ?");
    $stmt->execute([$id]);
    $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chambre) {
        echo "Chambre introuvable.";
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier une Chambre</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Modifier une Chambre</h1>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Numéro de Chambre:</label><br>
        <input type="text" name="numero" value="<?= htmlspecialchars($chambre['num']) ?>"><br><br>

        <label>Capacité:</label><br>
        <input type="number" name="capacite" value="<?= htmlspecialchars($chambre['capacité']) ?>" min="1"><br><br>

        <button type="submit">Enregistrer</button>
        <a href="listChambres.php">Annuler</a>
    </form>
</body>
</html>
