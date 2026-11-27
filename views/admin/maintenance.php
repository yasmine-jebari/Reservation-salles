<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bloquer une salle (maintenance)</title>
</head>
<body>
    <h1>Bloquer une salle pour maintenance</h1>

    <?php if ($resultat !== null): ?>
        <p style="color: <?= $resultat['reussi'] ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($resultat['message']) ?>
        </p>
    <?php endif; ?>

    <form method="post" action="">
        <p>
            <label>Salle :
                <select name="salle" required>
                    <?php foreach ($salles as $salle): ?>
                        <option value="<?= (int) $salle['id'] ?>"
                            <?= ((string) $salle['id'] === (string) $saisie['salle']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($salle['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p><label>Date : <input type="date" name="date" value="<?= htmlspecialchars($saisie['date']) ?>" required></label></p>
        <p><label>Début : <input type="time" name="debut" value="<?= htmlspecialchars($saisie['debut']) ?>" required></label></p>
        <p><label>Fin : <input type="time" name="fin" value="<?= htmlspecialchars($saisie['fin']) ?>" required></label></p>
        <p><label>Motif : <input type="text" name="motif" maxlength="255" value="<?= htmlspecialchars($saisie['motif']) ?>" required></label></p>
        <button type="submit">Bloquer la salle</button>
    </form>
</body>
</html>