<?php $title = 'Connexion'; require __DIR__ . '/../layout/header.php'; ?>
<h1>Connexion</h1>
<?php foreach ($errors as $err): ?><p role="alert"><?= e($err) ?></p><?php endforeach; ?>
<form method="post" action="index.php?url=connexion">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <label>E-mail <input type="email" name="email" value="<?= e($email) ?>" required></label>
    <label>Mot de passe <input type="password" name="password" required></label>
    <button type="submit">Se connecter</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>