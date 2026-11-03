
<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Infrastructure/Database.php';

try {
    Database::connect();
    echo 'Connexion réussie à la base de données';
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}