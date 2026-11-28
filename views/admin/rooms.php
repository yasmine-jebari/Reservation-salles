<?php $title = 'Gestion des salles'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Gestion des salles (<?= count($salles) ?>)</h1>
<p><a href="index.php?url=admin-salle-nouvelle">+ Ajouter une salle</a></p>
<div style="overflow-x:auto">
<table border="1" cellpadding="6">
    <thead>
        <tr><th>Nom</th><th>Capacité</th><th>Bâtiment</th><th>Étage</th><th>Statut</th><th>Action</th></tr>
    </thead>
    <tbody>
    <?php foreach ($salles as $s): ?>
        <tr>
            <td><?= e($s['name']) ?></td>
            <td><?= e($s['capacity']) ?></td>
            <td><?= e($s['building']) ?></td>
            <td><?= e($s['floor']) ?></td>
            <td><?= $s['is_active'] ? 'Active' : 'Désactivée' ?></td>
            <td><a href="index.php?url=admin-salle-modifier&amp;id=<?= (int) $s['id'] ?>">Modifier</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>