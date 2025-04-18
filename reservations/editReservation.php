<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();

$errors = [];

$reservation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($reservation_id <= 0) {
    header("Location: listReservations.php");
    exit;
}

// Récup clients et chambres
$stmtClients = $conn->query("SELECT client_id, nom FROM clients ORDER BY nom");
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

$stmtChambres = $conn->query("SELECT chambre_id, num, capacité FROM chambres ORDER BY num");
$chambres = $stmtChambres->fetchAll(PDO::FETCH_ASSOC);

$today = date('Y-m-d');

// Charger la réservation
$stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    closeDatabaseConnection($conn);
    echo "<h2 class='text-center text-danger mt-5'>Réservation introuvable.</h2>";
    exit;
}

// Bloquer la modification si déjà terminée
if ($reservation['date_depart'] < $today) {
    closeDatabaseConnection($conn);
    echo "<h2 class='text-center text-danger mt-5'>Impossible de modifier une réservation déjà terminée.</h2>";
    exit;
}

$client_id = $reservation['client_id'];
$chambre_id = $reservation['chambre_id'];
$date_arrivee = $reservation['date_arrivee'];
$date_depart = $reservation['date_depart'];
$nombre_personnes = $reservation['nombre_personnes'];

// Si POST : mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'] ?? '';
    $chambre_id = $_POST['chambre_id'] ?? '';
    $date_arrivee = $_POST['date_arrivee'] ?? '';
    $date_depart = $_POST['date_depart'] ?? '';
    $nombre_personnes = (int)($_POST['nombre_personnes'] ?? 1);

    if (!$client_id) $errors[] = "Client obligatoire.";
    if (!$chambre_id) $errors[] = "Chambre obligatoire.";

    if (empty($date_arrivee) || empty($date_depart)) {
        $errors[] = "Les dates sont obligatoires.";
    } else {
        if ($date_arrivee < $today) $errors[] = "La date d'arrivée ne peut pas être dans le passé.";
        if ($date_depart < $today) $errors[] = "La date de départ ne peut pas être dans le passé.";
        if ($date_arrivee > $date_depart) $errors[] = "La date de départ doit être après la date d'arrivée.";
    }

    if ($nombre_personnes <= 0) {
        $errors[] = "Nombre de personnes invalide.";
    } else {
        // Vérifier la capacité de la chambre sélectionnée
        $chambreCapacite = null;
        foreach ($chambres as $chambre) {
            if ($chambre['chambre_id'] == $chambre_id) {
                $chambreCapacite = (int)$chambre['capacité'];
                break;
            }
        }

        if ($chambreCapacite !== null && $nombre_personnes > $chambreCapacite) {
            $errors[] = "Le nombre de personnes dépasse la capacité maximale de la chambre ($chambreCapacite).";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE reservations SET client_id = ?, chambre_id = ?, date_arrivee = ?, date_depart = ?, nombre_personnes = ? WHERE id = ?");
        $stmt->execute([$client_id, $chambre_id, $date_arrivee, $date_depart, $nombre_personnes, $reservation_id]);

        closeDatabaseConnection($conn);
        header("Location: listReservations.php?success=1");
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Modifier la réservation</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3" novalidate>
        <div class="col-md-6">
            <label class="form-label">Client</label>
            <select name="client_id" class="form-select" required>
                <option value="">-- Sélectionner un client --</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['client_id'] ?>" <?= $client['client_id'] == $client_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($client['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Chambre</label>
            <select name="chambre_id" class="form-select" required>
                <option value="">-- Sélectionner une chambre --</option>
                <?php foreach ($chambres as $chambre): ?>
                    <option value="<?= $chambre['chambre_id'] ?>" <?= $chambre['chambre_id'] == $chambre_id ? 'selected' : '' ?>>
                        N°<?= $chambre['num'] ?> (<?= $chambre['capacité'] ?> pers.)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Date d'arrivée</label>
            <input type="date" name="date_arrivee" class="form-control"
                   value="<?= $date_arrivee ?>" min="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Date de départ</label>
            <input type="date" name="date_depart" class="form-control"
                   value="<?= $date_depart ?>" min="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Nombre de personnes</label>
            <input type="number" name="nombre_personnes" class="form-control"
                   value="<?= $nombre_personnes ?>" min="1" required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Enregistrer</button>
            <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
