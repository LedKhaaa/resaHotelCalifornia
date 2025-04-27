<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="/resaHotelCalifornia/index.php">🏨 Hotel California</a>

    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">

            <li class="nav-item">
                <a class="nav-link" href="/resaHotelCalifornia/clients/listClients.php">Clients</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/resaHotelCalifornia/chambres/listChambres.php">Chambres</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/resaHotelCalifornia/reservations/listReservations.php">Réservations</a>
            </li>

            <?php if (isset($_SESSION['username'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/resaHotelCalifornia/monCompte.php">
                        Mon Compte (<?= htmlspecialchars($_SESSION['username']) ?>)
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/resaHotelCalifornia/login.php">Se connecter</a>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</nav>
