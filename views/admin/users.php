<?php $title = 'Utilisateurs'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Utilisateurs enregistrés (<?= count($users) ?>)</h1>
<div style="overflow-x:auto">
<table border="1" cellpadding="6">
    <thead>
        <tr><th>ID</th><th>Nom</th><th>E-mail</th><th>Rôle</th><th>Inscrit le</th></tr>
    </thead>
    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= e($u['id']) ?></td>
            <td><?= e($u['name']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><?= e($u['role']) ?></td>
            <td><?= e($u['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>