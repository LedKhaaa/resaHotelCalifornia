<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();

// Récupère les clients
$stmtClients = $conn->query("SELECT client_id, nom FROM clients ORDER BY nom");
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

// Récupère les chambres
$stmtChambres = $conn->query("SELECT chambre_id, num, capacité FROM chambres ORDER BY num");
$chambres = $stmtChambres->fetchAll(PDO::FETCH_ASSOC);

// Initialisation
$errors = [];
$date_arrivee = $date_depart = '';
$nombre_personnes = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $chambre_id = $_POST['chambre_id'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_depart = $_POST['date_depart'];
    $nombre_personnes = (int)$_POST['nombre_personnes'];

    if (!$client_id) $errors[] = "Veuillez sélectionner un client.";
    if (!$chambre_id) $errors[] = "Veuillez sélectionner une chambre.";
    if (empty($date_arrivee) || empty($date_depart)) $errors[] = "Les dates sont obligatoires.";
    if ($nombre_personnes <= 0) $errors[] = "Nombre de personnes invalide.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES (?, ?, ?, ?)");
        $stmt->execute([$client_id, $chambre_id, $date_arrivee, $date_depart]);

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
    <title>Créer une Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Nouvelle Réservation</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Client</label>
            <select name="client_id" class="form-select" required>
                <option value="">-- Sélectionnez un client --</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['client_id'] ?>" <?= isset($client_id) && $client_id == $client['client_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($client['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Chambre</label>
            <select name="chambre_id" class="form-select" required>
                <option value="">-- Sélectionnez une chambre --</option>
                <?php foreach ($chambres as $chambre): ?>
                    <option value="<?= $chambre['chambre_id'] ?>" <?= isset($chambre_id) && $chambre_id == $chambre['chambre_id'] ? 'selected' : '' ?>>
                        N°<?= $chambre['num'] ?> (<?= $chambre['capacité'] ?> pers.)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Date d'arrivée</label>
            <input type="date" name="date_arrivee" class="form-control" value="<?= $date_arrivee ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Date de départ</label>
            <input type="date" name="date_depart" class="form-control" value="<?= $date_depart ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Nombre de personnes</label>
            <input type="number" name="nombre_personnes" class="form-control" value="<?= $nombre_personnes ?>" min="1" required>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Créer la réservation</button>
            <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
