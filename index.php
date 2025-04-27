<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db_connect.php';
$conn = openDatabaseConnection();

$countClients = $conn->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$countChambres = $conn->query("SELECT COUNT(*) FROM chambres")->fetchColumn();
$countReservations = $conn->query("SELECT COUNT(*) FROM reservations")->fetchColumn();

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Hôtel California</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('fondhotel.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            color: white;
            overflow-x: hidden;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
        }
        .container {
            margin-top: 80px;
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }
        .card-counter {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            text-align: center;
        }
        .card-counter h2 {
            font-size: 2.5rem;
            color: #00BFFF;
        }
        .card-counter p {
            margin-top: 10px;
            font-size: 1.2rem;
            color: #ccc;
        }
        h1, p {
            color: white;
        }
    </style>
</head>
<body>

<?php include 'asset/navbar.php'; ?>

<div class="container text-center">
    <?php if (isset($_SESSION['username'])): ?>
        <h1>Bienvenue <?= htmlspecialchars($_SESSION['username']) ?> 🌴</h1>
        <p class="lead">Connecté en tant que <strong><?= htmlspecialchars(ucfirst($_SESSION['role'])) ?></strong>.</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card-counter">
                    <h2><?= $countClients ?></h2>
                    <p>Clients</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-counter">
                    <h2><?= $countChambres ?></h2>
                    <p>Chambres</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-counter">
                    <h2><?= $countReservations ?></h2>
                    <p>Réservations</p>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <a href="/resaHotelCalifornia/logout.php" class="btn btn-danger btn-lg">Se déconnecter</a>
        </div>
    <?php else: ?>
        <h1>Bienvenue à l'Hôtel California 🏨</h1>
        <p class="lead">Un lieu magique pour votre séjour ensoleillé.</p>
        <a href="/resaHotelCalifornia/login.php" class="btn btn-light btn-lg mt-4">Se connecter</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
