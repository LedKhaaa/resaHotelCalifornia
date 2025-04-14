<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();

$errors = [];
$nom = $telephone = $email = '';
$nombre_personnes = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $nombre_personnes = (int)$_POST['nombre_personnes'];

    if (empty($nom)) $errors[] = "Le nom est obligatoire.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if ($nombre_personnes <= 0) $errors[] = "Nombre de personnes doit être > 0.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO clients (nom, telephone, email, nombre_personnes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $telephone, $email, $nombre_personnes]);

        closeDatabaseConnection($conn);
        header("Location: listClients.php?success=1");
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Ajouter un Client</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Téléphone</label>
            <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($telephone) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Nombre de personnes</label>
            <input type="number" class="form-control" name="nombre_personnes" value="<?= $nombre_personnes ?>" min="1" required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Enregistrer</button>
            <a href="listClients.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
