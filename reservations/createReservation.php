<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
initialiserSession();
requireRole('standard');

$conn = openDatabaseConnection();

$stmtClients = $conn->query("SELECT client_id, nom FROM clients ORDER BY nom");
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

$stmtChambres = $conn->query("SELECT chambre_id, num, capacité FROM chambres ORDER BY num");
$chambres = $stmtChambres->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$date_arrivee = $date_depart = '';
$nombre_personnes = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'] ?? '';
    $chambre_id = $_POST['chambre_id'] ?? '';
    $date_arrivee = $_POST['date_arrivee'] ?? '';
    $date_depart = $_POST['date_depart'] ?? '';
    $nombre_personnes = (int)($_POST['nombre_personnes'] ?? 1);

    if (!$client_id || !$chambre_id) $errors[] = "Client et chambre obligatoires.";
    if (empty($date_arrivee) || empty($date_depart)) $errors[] = "Les dates sont obligatoires.";
    if ($date_arrivee > $date_depart) $errors[] = "La date de départ doit être après l'arrivée.";
    if ($nombre_personnes <= 0) $errors[] = "Nombre de personnes invalide.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart, nombre_personnes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$client_id, $chambre_id, $date_arrivee, $date_depart, $nombre_personnes]);

        closeDatabaseConnection($conn);
        header('Location: listReservations.php?success=1');
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../fondReservation.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-form {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
            width: 500px;
        }
        .btn-primary {
            background-color: #00BFFF;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #009ACD;
        }
        .btn-secondary:hover {
            background-color: #6c757d;
        }
    </style>
</head>
<body>

<div class="card-form">
    <h2 class="text-center mb-4">Nouvelle Réservation</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Client</label>
            <select name="client_id" class="form-select" required>
                <option value="">-- Sélectionner un client --</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['client_id'] ?>"><?= htmlspecialchars($client['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Chambre</label>
            <select name="chambre_id" class="form-select" required>
                <option value="">-- Sélectionner une chambre --</option>
                <?php foreach ($chambres as $chambre): ?>
                    <option value="<?= $chambre['chambre_id'] ?>">N°<?= $chambre['num'] ?> (<?= $chambre['capacité'] ?> pers)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Date d'arrivée</label>
            <input type="date" name="date_arrivee" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Date de départ</label>
            <input type="date" name="date_depart" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre de personnes</label>
            <input type="number" name="nombre_personnes" class="form-control" min="1" value="1" required>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
