<?php

require_once __DIR__ . '/../Application/BloquerMaintenance.php';

class MaintenanceController
{
    // Identité PROVISOIRE : l'id doit exister dans ta table users.
    // Quand ta camarade aura le login, ces deux valeurs viendront de la session.
    private const ADMIN_PROVISOIRE_ID = 7;
    private const ROLE_PROVISOIRE = 'admin';

    public function index(): void
    {
        $saisie = ['salle' => '', 'date' => '', 'debut' => '', 'fin' => '', 'motif' => ''];
        $resultat = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $saisie = [
                'salle' => $_POST['salle'] ?? '',
                'date'  => $_POST['date'] ?? '',
                'debut' => $_POST['debut'] ?? '',
                'fin'   => $_POST['fin'] ?? '',
                'motif' => $_POST['motif'] ?? '',
            ];

            $action = new BloquerMaintenance(planningStore());
            $resultat = $action->executer(
                self::ADMIN_PROVISOIRE_ID,
                self::ROLE_PROVISOIRE,
                (int) $saisie['salle'],
                $saisie['date'],
                $saisie['debut'],
                $saisie['fin'],
                $saisie['motif'],
                new DateTimeImmutable()
            );
        }

        $salles = catalogueStore()->listerSalles();
        require __DIR__ . '/../../views/admin/maintenance.php';
    }
}