<?php
require_once __DIR__ . '/src/Infrastructure/Database.php';

// Planning (Personne B)
require_once __DIR__ . '/src/Application/PlanningStore.php';
require_once __DIR__ . '/src/Infrastructure/PlanningMySQL.php';

function planningStore(): PlanningStore {
    return new PlanningMySQL();
}

// Catalogue des salles (Personne A)
require_once __DIR__ . '/src/Application/CatalogueStore.php';
require_once __DIR__ . '/src/Infrastructure/CatalogueMySQL.php';

function catalogueStore(): CatalogueStore {
    return new CatalogueMySQL();
}