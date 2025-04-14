<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../asset/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Ajouter une Chambre</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Numéro</label>
            <input type="text" class="form-control" name="numero" value="<?= htmlspecialchars($numero ?? '') ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Capacité</label>
            <input type="number" class="form-control" name="capacite" value="<?= $capacite ?? 1 ?>" min="1" required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Enregistrer</button>
            <a href="listChambres.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
