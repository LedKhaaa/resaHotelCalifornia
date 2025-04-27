<?php
session_start();
require_once 'config/db_connect.php';

$username = $_SESSION['username'] ?? 'Visiteur';
$role = $_SESSION['role'] ?? 'Non connecté';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'asset/navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-5">Mon Compte 🔐</h1>

    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body text-center">
            <h4 class="card-title mb-3"><?= htmlspecialchars($username) ?></h4>
            <p class="card-text">Rôle : <strong><?= htmlspecialchars(ucfirst($role)) ?></strong></p>

            <a href="logout.php" class="btn btn-danger mt-4">Se déconnecter</a>
        </div>
    </div>
</div>

</body>
</html>
