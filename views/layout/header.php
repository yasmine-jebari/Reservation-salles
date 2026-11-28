<?php $u = currentUser(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Réservation des salles') ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6fb;
            color: #263238;
        }

        header {
            background-color: #344b80;
            color: white;
            padding: 20px 40px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav {
            margin-top: 12px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        nav form {
            display: inline;
        }

        nav button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: inherit;
            font-family: inherit;
            padding: 0;
        }

        nav button:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        main form label {
            display: block;
            margin-bottom: 16px;
            font-weight: bold;
        }

        main form input[type="text"],
        main form input[type="email"],
        main form input[type="password"],
        main form input[type="number"] {
            display: block;
            width: 100%;
            max-width: 380px;
            margin-top: 6px;
            padding: 10px;
            border: 1px solid #b0bec5;
            border-radius: 6px;
            font-size: 15px;
        }

        main form button {
            background-color: #344b80;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        main form button:hover {
            background-color: #2a3c66;
        }

        main form fieldset {
            max-width: 380px;
            margin-bottom: 16px;
            border: 1px solid #b0bec5;
            border-radius: 6px;
        }

        [role="alert"] {
            color: #c62828;
        }
    </style>
</head>

<body>

<header>
    <h1>Réservation des espaces</h1>

    <nav>
        <a href="index.php?url=salles">Catalogue des salles</a>

        <?php if ($u): ?>
            <a href="index.php?url=mes-reservations">Mes réservations</a>

            <?php if ($u['role'] === 'admin'): ?>
                <a href="index.php?url=admin-utilisateurs">Utilisateurs</a>
                <a href="index.php?url=admin-salles">Gestion des salles</a>
            <?php endif; ?>

            <a href="index.php?url=profil">Profil (<?= e($u['name']) ?>)</a>
            <form method="post" action="index.php?url=deconnexion">
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                <button type="submit">Déconnexion</button>
            </form>
        <?php else: ?>
            <a href="index.php?url=connexion">Connexion</a>
            <a href="index.php?url=inscription">Inscription</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container">