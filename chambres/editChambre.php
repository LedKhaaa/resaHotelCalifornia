<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
if (!hasRole("manager")) {
    $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.");
    header("Location: /resaHotelCalifornia/index.php?message=$encodedMessage"); 
    exit;
}

$conn = openDatabaseConnection();

$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: listChambres.php");
    exit;
}

// Récupération chambre
$stmt = $conn->prepare("SELECT * FROM chambres WHERE chambre_id = ?");
$stmt->execute([$id]);
$chambre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chambre) {
    closeDatabaseConnection($conn);
    header("Location: listChambres.php");
    exit;
}

$numero = $chambre['num'];
$capacite = $chambre['capacité'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero']);
    $capacite = (int)($_POST['capacite'] ?? 1);

    // Validation
    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    } elseif (!preg_match('/^[0-9]+$/', $numero)) {
        $errors[] = "Le numéro de chambre doit contenir uniquement des chiffres.";
    } else {
        // Vérifie que le numéro n'existe pas déjà dans une autre chambre
        $check = $conn->prepare("SELECT COUNT(*) FROM chambres WHERE num = ? AND chambre_id != ?");
        $check->execute([$numero, $id]);
        if ($check->fetchColumn() > 0) {
            $errors[] = "Une autre chambre avec ce numéro existe déjà.";
        }
    }

    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    // Mise à jour si pas d'erreurs
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE chambres SET num = ?, capacité = ? WHERE chambre_id = ?");
        $stmt->execute([$numero, $capacite, $id]);

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
    <title>Modifier une chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once '../asset/gestionMessage.php'; ?> 
<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Modifier une chambre</h2>

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
            <button class="btn btn-primary">Enregistrer</button>
            <a href="listChambres.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
