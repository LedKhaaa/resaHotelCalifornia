<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_connect.php';

$conn = openDatabaseConnection();

// Vérifie que l'ID est bien fourni
$client_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($client_id <= 0) {
    header("Location: listClients.php");
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $nombre_personnes = (int)$_POST['nombre_personnes'];

    $errors = [];

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
        $stmt = $conn->prepare("UPDATE clients SET nom = ?, telephone = ?, email = ?, nombre_personnes = ? WHERE client_id = ?");
        $stmt->execute([$nom, $telephone, $email, $nombre_personnes, $client_id]);

        closeDatabaseConnection($conn);
        header("Location: listClients.php?success=1");
        exit;
    }
} else {
    // Récupère les infos du client
    $stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        closeDatabaseConnection($conn);
        header("Location: listClients.php");
        exit;
    }

    $nom = $client['nom'];
    $telephone = $client['telephone'];
    $email = $client['email'];
    $nombre_personnes = $client['nombre_personnes'];
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un client</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Modifier un client</h1>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Nom :</label><br>
        <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" required><br><br>

        <label>Téléphone :</label><br>
        <input type="text" name="telephone" value="<?= htmlspecialchars($telephone) ?>" required><br><br>

        <label>Email :</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

        <label>Nombre de personnes :</label><br>
        <input type="number" name="nombre_personnes" value="<?= $nombre_personnes ?>" min="1" required><br><br>

        <button type="submit">Enregistrer</button>
        <a href="listClients.php">Annuler</a>
    </form>
</body>
</html>
