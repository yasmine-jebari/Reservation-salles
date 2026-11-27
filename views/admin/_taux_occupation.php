<h2>Taux d'occupation</h2>

<form method="get" action="">
    <label>Du : <input type="datetime-local" name="du" value="<?= htmlspecialchars($du) ?>" required></label>
    <label>Au : <input type="datetime-local" name="au" value="<?= htmlspecialchars($au) ?>" required></label>
    <button type="submit">Calculer</button>
</form>

<?php if (!$taux['reussi']): ?>
    <p style="color: red;"><?= htmlspecialchars($taux['message']) ?></p>
<?php else: ?>
    <table border="1" cellpadding="6">
        <tr><th>Salle</th><th>Temps réservé</th><th>Temps réservable</th><th>Taux</th></tr>
        <?php foreach ($taux['salles'] as $ligne): ?>
            <tr>
                <td><?= htmlspecialchars($ligne['nom']) ?></td>
                <td><?= htmlspecialchars($ligne['occupe']) ?></td>
                <td><?= htmlspecialchars($ligne['reservable']) ?></td>
                <td><?= htmlspecialchars($ligne['taux']) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th><?= htmlspecialchars($taux['global']['nom']) ?></th>
            <th><?= htmlspecialchars($taux['global']['occupe']) ?></th>
            <th><?= htmlspecialchars($taux['global']['reservable']) ?></th>
            <th><?= htmlspecialchars($taux['global']['taux']) ?></th>
        </tr>
    </table>
<?php endif; ?>