<?php $title = 'Inscription'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Inscription</h1>
<?php foreach ($errors as $err): ?><p role="alert"><?= e($err) ?></p><?php endforeach; ?>
<form method="post" action="index.php?url=inscription">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <label>Nom <input type="text" name="name" value="<?= e($old['name']) ?>" required></label>
    <label>E-mail <input type="email" name="email" value="<?= e($old['email']) ?>" required></label>
    <label>Mot de passe <input type="password" name="password" minlength="8" required></label>
    <label>Confirmation <input type="password" name="password_confirm" minlength="8" required></label>
    <button type="submit">Créer mon compte</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>