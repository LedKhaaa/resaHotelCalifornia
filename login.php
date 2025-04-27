<?php
session_start();
require_once 'config/db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } else {
        $conn = openDatabaseConnection();

        $stmt = $conn->prepare("SELECT * FROM employes WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        closeDatabaseConnection($conn);

        if ($user) {
            // Vérifie le mot de passe
            if (password_verify($password, $user['password'])) {
                // Authentification réussie
                $_SESSION['user_id'] = $user['employes_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: index.php?message=Connexion réussie');
                exit;
            } else {
                $errors[] = "Mot de passe incorrect.";
            }
        } else {
            $errors[] = "Utilisateur inconnu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Connexion Employé 🔐</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3 mx-auto" style="max-width: 400px;">
        <div class="col-12">
            <label class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="col-12">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary mt-3">Se connecter</button>
        </div>
    </form>
</div>

</body>
</html>
