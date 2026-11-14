<h1>Vérifier la disponibilité d'une salle</h1>

<form method="post" action="index.php?url=disponibilite">
    
<p>
    <label for="salle">Choisir une salle</label>

    <select name="salle" id="salle" required>
        <option value="">-- Sélectionner une salle --</option>

        <?php foreach ($salles as $salle): ?>
            <option
                value="<?= (int) $salle['id'] ?>"
                <?= ($saisie['salle'] == $salle['id']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($salle['name']) ?>
                - <?= (int) $salle['capacity'] ?> personnes
                - <?= htmlspecialchars($salle['building'] ?? '') ?>
            </option>
        <?php endforeach; ?>

    </select>
</p>

    <p>
        <label>Date
            <input type="date" name="date" required
                   value="<?= htmlspecialchars($saisie['date']) ?>">
        </label>
    </p>
    <p>
        <label>Heure de début
            <input type="time" name="debut" required
                   value="<?= htmlspecialchars($saisie['debut']) ?>">
        </label>
    </p>
    <p>
        <label>Heure de fin
            <input type="time" name="fin" required
                   value="<?= htmlspecialchars($saisie['fin']) ?>">
        </label>
    </p>
    <button type="submit">Vérifier</button>
    <button type="submit" formaction="index.php?url=reservations">
    Réserver
</button>
</form>

<?php if ($resultat !== null): ?>
    <p style="color: <?= $resultat['libre'] ? 'green' : 'red' ?>; font-weight: bold;">
        <?= htmlspecialchars($resultat['message']) ?>
    </p>
<?php endif; ?>