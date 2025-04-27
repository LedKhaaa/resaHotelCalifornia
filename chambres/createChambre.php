<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
    if (!hasRole("directeur")) {
        $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.");
        header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage"); 
        exit;
    }
$conn = openDatabaseConnection();

$errors = [];
$numero = '';
$capacite = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero']);
    $capacite = (int)($_POST['capacite'] ?? 1);

    // Validation du numéro
    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    } elseif (!preg_match('/^[0-9]+$/', $numero)) {
        $errors[] = "Le numéro de chambre doit contenir uniquement des chiffres.";
    } else {
        // Vérifie si le numéro existe déjà
        $check = $conn->prepare("SELECT COUNT(*) FROM chambres WHERE num = ?");
        $check->execute([$numero]);
        if ($check->fetchColumn() > 0) {
            $errors[] = "Une chambre avec ce numéro existe déjà.";
        }
    }

    // Validation de la capacité
    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    // Insertion si tout est OK
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO chambres (num, capacité) VALUES (?, ?)");
        $stmt->execute([$numero, $capacite]);

        closeDatabaseConnection($conn);
        header("Location: listChambres.php?success=1");
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once '../asset/gestionMessage.php'; ?> 
<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Ajouter une chambre</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Numéro de chambre</label>
            <input type="text" name="numero" class="form-control"
                   pattern="[0-9]+" title="Chiffres uniquement"
                   value="<?= htmlspecialchars($numero) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Capacité</label>
            <input type="number" name="capacite" class="form-control" min="1"
                   value="<?= $capacite ?>" required>
        </div>

        <div class="col-12">
            <button class="btn btn-success">Ajouter</button>
            <a href="listChambres.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
