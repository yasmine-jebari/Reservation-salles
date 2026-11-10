<?php
function afficherLigne(array $r): void {
    $debut = new DateTimeImmutable($r['starts_at']);
    $fin   = new DateTimeImmutable($r['ends_at']);
    ?>
    <tr>
        <td><?= htmlspecialchars($r['salle']) ?></td>
        <td><?= $debut->format('d/m/Y') ?></td>
        <td><?= $debut->format('H:i') ?> - <?= $fin->format('H:i') ?></td>
        <td><?= htmlspecialchars($r['etat']) ?></td>
        <td>
            <?php if ($r['annulable']) : ?>
                <form method="post" action="annuler-reservation">
                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                    <button type="submit">Annuler</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes réservations</title>
</head>
<body>
    <h1>Mes réservations</h1>
    <?php if (!empty($_GET['msg'])) : ?>
        <p style="font-weight: bold; color: <?= ($_GET['ok'] ?? '') === '1' ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($_GET['msg']) ?>
        </p>
    <?php endif; ?>
    <h2>À venir</h2>
    <?php if (empty($aVenir)) : ?>
        <p>Aucune réservation à venir.</p>
    <?php else : ?>
        <table border="1" cellpadding="6">
            <tr><th>Salle</th><th>Date</th><th>Heures</th><th>État</th><th></th></tr>
            <?php foreach ($aVenir as $r) { afficherLigne($r); } ?>
        </table>
    <?php endif; ?>

    <h2>Passées et annulées</h2>
    <?php if (empty($passees)) : ?>
        <p>Aucune réservation passée.</p>
    <?php else : ?>
        <table border="1" cellpadding="6">
            <tr><th>Salle</th><th>Date</th><th>Heures</th><th>État</th><th></th></tr>
            <?php foreach ($passees as $r) { afficherLigne($r); } ?>
        </table>
    <?php endif; ?>
</body>
</html>