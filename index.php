<?php
require_once 'config/db_connect.php';
$conn = openDatabaseConnection();

// Compter les clients
$stmt = $conn->query("SELECT COUNT(*) FROM clients");
$totalClients = $stmt->fetchColumn();

// Compter les chambres
$stmt = $conn->query("SELECT COUNT(*) FROM chambres");
$totalChambres = $stmt->fetchColumn();

// Compter les réservations à venir
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE date_arrivee >= ?");
$stmt->execute([$today]);
$totalReservationsAVenir = $stmt->fetchColumn();

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Hotel California</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include 'asset/navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-5">Bienvenue à l'Hotel California 🏨</h1>

    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-people-fill fs-1 text-primary"></i>
                    <h3 class="mt-3"><?= $totalClients ?></h3>
                    <p class="text-muted">Clients enregistrés</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-door-closed-fill fs-1 text-success"></i>
                    <h3 class="mt-3"><?= $totalChambres ?></h3>
                    <p class="text-muted">Chambres disponibles</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-calendar-event-fill fs-1 text-warning"></i>
                    <h3 class="mt-3"><?= $totalReservationsAVenir ?></h3>
                    <p class="text-muted">Réservations à venir</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
