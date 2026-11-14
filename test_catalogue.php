<?php

require_once __DIR__ . '/bootstrap.php';

try {
    $catalogue = catalogueStore();

    $salles = $catalogue->listerSalles();

    echo "<h2>Liste des salles</h2>";

    foreach ($salles as $salle) {
        echo "<p>";
        echo $salle['name'] . " - ";
        echo "Capacité : " . $salle['capacity'];
        echo "</p>";
    }

echo "<h2>Recherche des salles</h2>";

$resultats = $catalogue->rechercherSalles(null,null,1);

foreach ($resultats as $salle) {
    echo "<p>";
    echo $salle['name'] . " - ";
    echo "Capacité : " . $salle['capacity'];
    echo "</p>";
}
    } catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}