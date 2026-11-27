<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier des salles</title>
</head>
<body>
    <h1>Calendrier des salles</h1>

    <?php if (!$calendrier['reussi']): ?>
        <p style="color: red;"><?= htmlspecialchars($calendrier['message']) ?></p>
    <?php else: ?>
        <p>
            <a href="?vue=jour&date=<?= htmlspecialchars($calendrier['date']) ?>">Jour</a> |
            <a href="?vue=semaine&date=<?= htmlspecialchars($calendrier['date']) ?>">Semaine</a>
        </p>
        <p>
            <a href="?vue=<?= htmlspecialchars($calendrier['vue']) ?>&date=<?= htmlspecialchars($calendrier['precedent']) ?>">&laquo; Précédent</a> |
            <a href="?vue=<?= htmlspecialchars($calendrier['vue']) ?>&date=<?= htmlspecialchars($calendrier['suivant']) ?>">Suivant &raquo;</a>
        </p>

        <?php foreach ($calendrier['jours'] as $jour): ?>
            <h2><?= htmlspecialchars($jour['libelle']) ?></h2>
            <?php if (empty($jour['occupations'])): ?>
                <p>Aucune occupation.</p>
            <?php else: ?>
                <table border="1" cellpadding="6">
                    <tr><th>Salle</th><th>De</th><th>À</th><th>Type</th><th>Détail</th></tr>
                    <?php foreach ($jour['occupations'] as $o): ?>
                        <tr>
                            <td><?= htmlspecialchars($o['salle']) ?></td>
                            <td><?= htmlspecialchars($o['debut']) ?></td>
                            <td><?= htmlspecialchars($o['fin']) ?></td>
                            <td><?= $o['type'] === 'maintenance' ? 'Maintenance' : 'Réservation' ?></td>
                            <td><?= htmlspecialchars($o['detail']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>