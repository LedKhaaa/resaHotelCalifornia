<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
initialiserSession();
requireRole('standard');

$conn = openDatabaseConnection();

$errors = [];
$nom = $email = $telephone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);

    if (empty($nom) || empty($email) || empty($telephone)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $nom)) {
        $errors[] = "Le nom ne doit contenir que des lettres.";
    } elseif (!preg_match('/^(06|07)[0-9]{8}$/', $telephone)) {
        $errors[] = "Le numéro de téléphone doit commencer par 06 ou 07 et avoir 10 chiffres.";
    } else {
        $stmt = $conn->prepare("INSERT INTO clients (nom, email, telephone) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $telephone]);

        closeDatabaseConnection($conn);
        header('Location: listClients.php?success=1');
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../fondClients.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-form {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
            width: 450px;
        }
        .btn-primary {
            background-color: #00BFFF;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #009ACD;
        }
        .btn-secondary {
            transition: 0.3s;
        }
        .btn-secondary:hover {
            background-color: #6c757d;
        }
    </style>
</head>
<body>

<div class="card-form">
    <h2 class="text-center mb-4">Ajouter un Client</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($telephone) ?>" required>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="listClients.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
