<?php

require_once __DIR__ . '/../Application/CalculerTauxOccupation.php';

class StatistiquesController
{
    // PROVISOIRE : viendra de la session
    private const ROLE_PROVISOIRE = 'admin';

    public function index(): void
    {
        // Période par défaut : aujourd'hui de 08:00 à 18:00
        $du = $_GET['du'] ?? date('Y-m-d') . 'T08:00';
        $au = $_GET['au'] ?? date('Y-m-d') . 'T18:00';

        $action = new CalculerTauxOccupation(planningStore());
        $taux = $action->executer(self::ROLE_PROVISOIRE, $du, $au);

        require __DIR__ . '/../../views/admin/statistiques.php';
    }
}