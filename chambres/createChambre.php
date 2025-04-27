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

    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    } elseif (!preg_match('/^[0-9]+$/', $numero)) {
        $errors[] = "Le numéro de chambre doit contenir uniquement des chiffres.";
    } else {
        $check = $conn->prepare("SELECT COUNT(*) FROM chambres WHERE num = ?");
        $check->execute([$numero]);
        if ($check->fetchColumn() > 0) {
            $errors[] = "Une chambre avec ce numéro existe déjà.";
        }
    }

    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

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
    <style>
        body {
            background: url('../fondChambre.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 100px; /* Laisse de la place pour la navbar */
        }
        .card-form {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 20px;
            color: white;
            width: 500px;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
        }
        .btn-success {
            background-color: #00BFFF;
            border: none;
            transition: 0.3s;
        }
        .btn-success:hover {
            background-color: #009ACD;
        }
        .btn-secondary:hover {
            background-color: #6c757d;
        }
    </style>
</head>
<body>

<?php include_once '../asset/gestionMessage.php'; ?>
<?php include '../asset/navbar.php'; ?>

<div class="form-container">
    <div class="card-form">
        <h2 class="text-center mb-4">Ajouter une Chambre</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
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
                       value="<?= htmlspecialchars($capacite) ?>" required>
            </div>

            <div class="col-12 d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-success">Ajouter</button>
                <a href="listChambres.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
