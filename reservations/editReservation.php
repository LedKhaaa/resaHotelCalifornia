<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Ajouter une Réservation</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
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
                    <option value="<?= $client['client_id'] ?>" <?= ($client['client_id'] == $selectedClientId) ? 'selected' : '' ?>>
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
                    <option value="<?= $chambre['chambre_id'] ?>" <?= ($chambre['chambre_id'] == $selectedChambreId) ? 'selected' : '' ?>>
                        N°<?= $chambre['num'] ?> (<?= $chambre['capacité'] ?> pers.)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Date d'arrivée</label>
            <input type="date" class="form-control" name="date_arrivee" value="<?= $date_arrivee ?? '' ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Date de départ</label>
            <input type="date" class="form-control" name="date_depart" value="<?= $date_depart ?? '' ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Nombre de personnes</label>
            <input type="number" class="form-control" name="nombre_personnes" value="<?= $nombre_personnes ?? 1 ?>" min="1" required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Enregistrer</button>
            <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
