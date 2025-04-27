<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();

$errors = [];

$client_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($client_id <= 0) {
    header("Location: listClients.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);

    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ' -]+$/", $nom)) {
        $errors[] = "Le nom ne doit contenir que des lettres.";
    }

    if (empty($telephone)) {
        $errors[] = "Le téléphone est obligatoire.";
    } elseif (!preg_match("/^0[67][0-9]{8}$/", $telephone)) {
        $errors[] = "Le téléphone doit commencer par 06 ou 07 et contenir exactement 10 chiffres.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE clients SET nom = ?, telephone = ?, email = ? WHERE client_id = ?");
        $stmt->execute([$nom, $telephone, $email, $client_id]);

        closeDatabaseConnection($conn);
        header("Location: listClients.php?success=1");
        exit;
    }
} else {
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
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../fondClients.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 100px;
        }
        .card-form {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 20px;
            color: white;
            width: 500px;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
        }
        .btn-primary {
            background-color: #00BFFF;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
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
        <h2 class="text-center mb-4">Modifier le Client</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="row g-3" novalidate>
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control"
                       pattern="[A-Za-zÀ-ÿ '\-]+" title="Lettres uniquement"
                       value="<?= htmlspecialchars($nom) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control"
                       pattern="^0[67][0-9]{8}$"
                       title="Doit commencer par 06 ou 07 et contenir 10 chiffres"
                       value="<?= htmlspecialchars($telephone) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <div class="col-12 d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="listClients.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
