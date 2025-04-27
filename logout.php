<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil
header('Location: index.php?message=Déconnecté avec succès');
exit;
?>
