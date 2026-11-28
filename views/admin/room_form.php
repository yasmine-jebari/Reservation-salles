<?php
$title = $mode === 'creation' ? 'Ajouter une salle' : 'Modifier la salle';
$action = $mode === 'creation'
    ? 'index.php?url=admin-salle-nouvelle'
    : 'index.php?url=admin-salle-modifier&id=' . (int) $salle['id'];
require __DIR__ . '/../layout/header.php';
?>
<h1><?= e($title) ?></h1>
<?php foreach ($errors as $err): ?><p role="alert"><?= e($err) ?></p><?php endforeach; ?>
<form method="post" action="<?= e($action) ?>">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <label>Nom <input type="text" name="name" value="<?= e($salle['name']) ?>" required></label>
    <label>Capacité <input type="number" name="capacity" min="1" value="<?= e($salle['capacity']) ?>" required></label>
    <label>Bâtiment <input type="text" name="building" value="<?= e($salle['building']) ?>"></label>
    <label>Étage <input type="number" name="floor" value="<?= e($salle['floor']) ?>"></label>
    <button type="submit">Enregistrer</button>
</form>
<p><a href="index.php?url=admin-salles">← Retour à la liste</a></p>
<?php require __DIR__ . '/../layout/footer.php'; ?>