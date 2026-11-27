<?php

require_once __DIR__ . '/../Application/LireCalendrier.php';

class CalendrierController
{
    // PROVISOIRE : viendra de la session quand le login sera prêt
    private const ROLE_PROVISOIRE = 'admin';

    public function index(): void
    {
        $action = new LireCalendrier(planningStore());
        $calendrier = $action->executer(
            self::ROLE_PROVISOIRE,
            $_GET['date'] ?? '',
            $_GET['vue'] ?? 'jour',
            new DateTimeImmutable()
        );

        require __DIR__ . '/../../views/admin/calendrier.php';
    }
}