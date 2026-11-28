<?php $title = 'Mon profil'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Mon profil</h1>
<?php if ($success): ?><p>Profil mis à jour.</p><?php endif; ?>
<?php foreach ($errors as $err): ?><p role="alert"><?= e($err) ?></p><?php endforeach; ?>
<form method="post" action="index.php?url=profil">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <label>Nom <input type="text" name="name" value="<?= e($user['name']) ?>" required></label>
    <label>E-mail <input type="email" name="email" value="<?= e($user['email']) ?>" required></label>
    <fieldset>
        <legend>Changer le mot de passe (optionnel)</legend>
        <label>Mot de passe actuel <input type="password" name="current_password"></label>
        <label>Nouveau mot de passe <input type="password" name="new_password" minlength="8"></label>
    </fieldset>
    <button type="submit">Enregistrer</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>