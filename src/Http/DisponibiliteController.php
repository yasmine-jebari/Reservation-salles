<?php
require_once __DIR__ . '/../Application/VerifierDisponibilite.php';

class DisponibiliteController {
    public function index() {
        $saisie = ['salle' => 0, 'date' => '', 'debut' => '', 'fin' => ''];
        $resultat = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $saisie = [
                'salle' => (int) ($_POST['salle'] ?? 0),
                'date'  => trim($_POST['date'] ?? ''),
                'debut' => trim($_POST['debut'] ?? ''),
                'fin'   => trim($_POST['fin'] ?? ''),
            ];

            $action = new VerifierDisponibilite(planningStore());
            $resultat = $action->executer(
                $saisie['salle'], $saisie['date'], $saisie['debut'], $saisie['fin'],
                new DateTimeImmutable()
            );
        }

        $salles = catalogueStore()->listerSalles();

        require __DIR__ . '/../../views/reservations/form.php';
    }
}