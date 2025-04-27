<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/resaHotelCalifornia/index.php">🏨 Hôtel California</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Si connecté -->
                    <li class="nav-item">
                        <a class="nav-link" href="/resaHotelCalifornia/clients/listClients.php">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/resaHotelCalifornia/chambres/listChambres.php">Chambres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/resaHotelCalifornia/reservations/listReservations.php">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/resaHotelCalifornia/logout.php">Se déconnecter</a>
                    </li>
                <?php else: ?>
                    <!-- Si PAS connecté -->
                    <li class="nav-item">
                        <a class="nav-link" href="/resaHotelCalifornia/login.php">Se connecter</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
