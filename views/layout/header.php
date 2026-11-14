
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation des salles</title>

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

        .container {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }
    </style>
</head>

<body>

<header>
    <h1>Réservation des espaces</h1>

    <nav>
        <a href="index.php?url=salles">Catalogue des salles</a>
        <a href="index.php?url=mes-reservations">Mes réservations</a>
    </nav>
</header>

<main class="container">
