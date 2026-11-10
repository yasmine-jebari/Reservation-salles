<h1>Vérifier la disponibilité d'une salle</h1>

<form method="post" action="">
    <p>
        <label>Numéro de la salle
            <input type="number" name="salle" min="1" required
                   value="<?= $saisie['salle'] > 0 ? (int) $saisie['salle'] : '' ?>">
        </label>
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
    <button type="submit" formaction="reservations">Réserver</button>
</form>

<?php if ($resultat !== null): ?>
    <p style="color: <?= $resultat['libre'] ? 'green' : 'red' ?>; font-weight: bold;">
        <?= htmlspecialchars($resultat['message']) ?>
    </p>
<?php endif; ?>