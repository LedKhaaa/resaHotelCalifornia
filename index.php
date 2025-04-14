<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Hôtel California - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1582719478250-06d89f4b74f3'); /* image d'hôtel luxe */
            background-size: cover;
            background-position: center;
            color: white;
            text-shadow: 1px 1px 2px #000;
        }
        .hero {
            background: rgba(0,0,0,0.6);
            padding: 60px;
            border-radius: 15px;
            margin-top: 100px;
        }
        .btn-glow {
            box-shadow: 0 0 10px #fff;
        }
    </style>
</head>
<body>

<?php include 'asset/navbar.php'; ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="text-center hero">
        <h1 class="mb-4">Bienvenue à l’Hôtel California 🌴</h1>
        <p class="lead mb-4">Gérez les réservations, les clients et les chambres avec style.</p>
        <div class="d-grid gap-3 d-md-block">
            <a href="clients/listClients.php" class="btn btn-light btn-lg btn-glow me-2"><i class="bi bi-people"></i> Clients</a>
            <a href="chambres/listChambres.php" class="btn btn-light btn-lg btn-glow me-2"><i class="bi bi-door-closed"></i> Chambres</a>
            <a href="reservations/listReservations.php" class="btn btn-light btn-lg btn-glow"><i class="bi bi-calendar2-check"></i> Réservations</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
