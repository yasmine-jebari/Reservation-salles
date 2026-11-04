<?php
require_once __DIR__ . '/src/Infrastructure/Database.php';

require_once __DIR__ . '/src/Application/PlanningStore.php';
require_once __DIR__ . '/src/Infrastructure/PlanningMySQL.php';

function planningStore(): PlanningStore {
    return new PlanningMySQL();
}