<?php
require_once __DIR__ . '/../Application/CreerReservation.php';

class ReservationController {
    // Provisoire : sera remplacé par l'utilisateur connecté (login de ta camarade)
    private const UTILISATEUR_PROVISOIRE = 3;

    public function store() {
        $saisie = ['salle' => 0, 'date' => '', 'debut' => '', 'fin' => ''];
        $resultat = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $saisie = [
                'salle' => (int) ($_POST['salle'] ?? 0),
                'date'  => trim($_POST['date'] ?? ''),
                'debut' => trim($_POST['debut'] ?? ''),
                'fin'   => trim($_POST['fin'] ?? ''),
            ];

            $action = new CreerReservation(planningStore());
            $reponse = $action->executer(
                self::UTILISATEUR_PROVISOIRE,
                $saisie['salle'], $saisie['date'], $saisie['debut'], $saisie['fin'],
                new DateTimeImmutable()
            );

            // La vue affiche déjà "libre" en vert et "refus" en rouge
            $resultat = ['libre' => $reponse['reussi'], 'message' => $reponse['message']];
        }

        require __DIR__ . '/../../views/reservations/form.php';
    }
}