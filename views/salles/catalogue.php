
<?php require __DIR__ . '/../layout/header.php'; ?>

<h2>Catalogue des salles</h2>

<p>Consultez les salles disponibles et trouvez celle qui correspond à vos besoins.</p>

<!-- Search filters -->
<section style="background:white; padding:20px; border-radius:10px; margin:25px 0;">

    <h3>Rechercher une salle</h3>

    <form method="GET" action="index.php">

        <input type="hidden" name="url" value="salles">

        <div style="display:flex; flex-wrap:wrap; gap:15px;">

            <div>
                <label for="capacite">Capacité minimale</label><br>
                <input
                    type="number"
                    id="capacite"
                    name="capacite"
                    min="1"
                    value="<?= htmlspecialchars($_GET['capacite'] ?? '') ?>"
                    placeholder="Ex : 10"
                >
            </div>

            <div>
                <label for="batiment">Bâtiment</label><br>
                <input
                    type="text"
                    id="batiment"
                    name="batiment"
                    value="<?= htmlspecialchars($_GET['batiment'] ?? '') ?>"
                    placeholder="Ex : Bâtiment A"
                >
            </div>

            <div>
                <label for="equipement">Équipement</label><br>
                <select id="equipement" name="equipement">
                    <option value="">Tous les équipements</option>

                    <?php foreach ($equipements as $equipement): ?>
                        <option
                            value="<?= (int) $equipement['id'] ?>"
                            <?= (($_GET['equipement'] ?? '') == $equipement['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($equipement['name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

        </div>

        <br>

        <button type="submit">Rechercher</button>

        <a href="index.php?url=salles">Réinitialiser</a>

    </form>

</section>

<!-- Room results -->
<h3>Résultats de recherche</h3>

<?php if (empty($salles)): ?>

    <p>Aucune salle ne correspond à votre recherche.</p>

<?php else: ?>

    <div style="display:flex; flex-wrap:wrap; gap:20px;">

        <?php foreach ($salles as $salle): ?>

            <article style="
                background:white;
                padding:20px;
                border-radius:10px;
                width:250px;
                box-shadow:0 2px 8px rgba(0,0,0,0.08);
            ">

                <h3><?= htmlspecialchars($salle['name']) ?></h3>

                <p>
                    <strong>Capacité :</strong>
                    <?= (int) $salle['capacity'] ?> personnes
                </p>

                <p>
                    <strong>Bâtiment :</strong>
                    <?= htmlspecialchars($salle['building'] ?? 'Non précisé') ?>
                </p>

                <p>
                    <strong>Étage :</strong>
                    <?= htmlspecialchars((string) ($salle['floor'] ?? 'Non précisé')) ?>
                </p>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>
