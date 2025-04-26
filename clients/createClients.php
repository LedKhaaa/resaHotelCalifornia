<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();

$errors = [];
$nom = $telephone = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $telephone = trim($_POST['telephone']);
    $email = trim($_POST['email']);

    // VALIDATION
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
        $stmt = $conn->prepare("INSERT INTO clients (nom, telephone, email) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $telephone, $email]);

        closeDatabaseConnection($conn);
        header("Location: listClients.php?success=1");
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once '../asset/gestionMessage.php'; ?> 
<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Ajouter un client</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
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

        <div class="col-12">
            <button class="btn btn-success">Ajouter</button>
            <a href="listClients.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
